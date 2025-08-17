<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeminiController extends Controller
{
    public function analyzeImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096',
        ]);
        $user = auth()->user();

        // Sauvegarde de l'image
        $path = $request->file('image')->store('analyses', 'public');

        // Appel API Gemini Vision (à adapter selon votre clé et modèle)
        $imageContent = base64_encode(file_get_contents($request->file('image')->getRealPath()));
        $apiKey = env('GEMINI_API_KEY');
        $response = \Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-pro-vision:generateContent?key=' . $apiKey, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => "Tu es un assistant dermatologique expert. Analyse l’image transmise et donne une description clinique détaillée des lésions (rougeur, boutons, comédons, plaques, etc.). Propose des hypothèses (acné, eczéma, psoriasis, mycose, etc.) en précisant qu’il s’agit d’une aide informative, jamais d’un diagnostic ferme. Réponds uniquement en français, de façon claire et pédagogique."],
                        ['inlineData' => [
                            'mimeType' => $request->file('image')->getMimeType(),
                            'data' => $imageContent
                        ]]
                    ]
                ]
            ]
        ]);
        \Log::info('Gemini response', ['body' => $response->json()]);
        $result = $response->json('candidates.0.content.parts.0.text') ?? 'Aucune analyse disponible.';
        // Sauvegarde en base
        $analysis = \App\Models\ImageAnalysis::create([
            'user_id' => $user->id,
            'image_path' => $path,
            'result' => $result,
        ]);
        return response()->json([
            'success' => true,
            'response' => $result
        ]);
    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->message;
        
        // Prompt enrichi pour DE-IA : dermatologie, MST/IST, hygiène, prévention, discussion libre
        $systemPrompt = <<<PROMPT
Tu es DE-IA, un assistant spécialisé en dermatologie et santé de la peau, mais tu es aussi capable d'accueillir chaleureusement les patients et de répondre aux salutations ou questions basiques (bonjour, merci, comment ça va, etc.).

**Règles :**
1. Si le message est une salutation ou une question basique (bonjour, merci, comment ça va, etc.), réponds de façon polie, chaleureuse et professionnelle, puis invite la personne à poser sa question dermatologique ou de santé de la peau.
2. Si la question concerne la dermatologie, la peau, les maladies sexuellement transmissibles (MST/IST), l'hygiène intime, la prévention, ou tout autre problème cutané, réponds avec des informations éducatives, de la prévention, des conseils généraux, sans jamais faire de diagnostic définitif.
3. Si la question n'est pas liée à la santé de la peau, explique gentiment que tu es spécialisé uniquement en dermatologie, MST/IST, hygiène et bien-être cutané, et invite à poser une question sur ces sujets.
4. Le patient peut aussi discuter librement avec toi pour obtenir des conseils, du soutien, ou parler de bien-être, d'hygiène, de prévention, etc.
5. Termine toujours par :  
⚠️ IMPORTANT : Ces informations sont données à titre informatif uniquement. Pour un diagnostic précis et un traitement adapté, consultez un dermatologue ou un professionnel de santé spécialisé.

Sois rassurant, bienveillant, professionnel et inclusif.
PROMPT;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=' . env('GEMINI_API_KEY'), [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\nQuestion du patient : " . $userMessage]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 800,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Désolé, je n\'ai pas pu traiter votre demande.';
                
                return response()->json([
                    'success' => true,
                    'response' => $aiResponse,
                    'timestamp' => now()->format('H:i')
                ]);
            } else {
                // Afficher l'erreur réelle de l'API
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? 'Erreur inconnue de l\'API Gemini';
                
                return response()->json([
                    'success' => false,
                    'response' => 'Erreur API Gemini: ' . $errorMessage,
                    'debug' => [
                        'status' => $response->status(),
                        'error' => $errorData
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'response' => 'Erreur de connexion: ' . $e->getMessage(),
                'debug' => [
                    'exception' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
            
        }
    }
}
