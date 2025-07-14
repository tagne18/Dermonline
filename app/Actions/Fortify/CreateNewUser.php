<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
{
    Validator::make($input, [
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'phone' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
        'birth_date' => ['required', 'date'],
        'profession' => ['required', 'string'],
        'city' => ['required', 'string'],
        'gender' => ['required', 'in:homme,femme'],
        'password' => [
            'required',
            'confirmed',
            'min:8',
            'regex:/[a-z]/',      // au moins une lettre
            'regex:/[0-9]/',      // au moins un chiffre
            'regex:/[@$!%*#?&]/'  // au moins un caractère spécial
        ],
    ])->validate();

    return User::create([
        'first_name' => $input['first_name'],
        'last_name' => $input['last_name'],
        'name' => $input['first_name'] . ' ' . $input['last_name'], // 👈 Ajouté pour respecter la contrainte
        'email' => $input['email'],
        'phone' => $input['phone'],
        'birth_date' => $input['birth_date'],
        'profession' => $input['profession'],
        'city' => $input['city'],
        'gender' => $input['gender'],
        'password' => Hash::make($input['password']),
    ]);
}

}
