<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dermonline - Votre santé de peau, notre priorité</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .gradient-bg {
      background: linear-gradient(135deg, #667eea, #764ba2);
    }
    .glass-effect {
      backdrop-filter: blur(10px);
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.2);
    }
    .fade-in {
      animation: fadeInUp 1s ease-out forwards;
      opacity: 0;
      transform: translateY(30px);
    }
    @keyframes fadeInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .floating-animation {
      animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
      0%,100% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
    }
  </style>
</head>
<body>

<!-- HERO -->
<section class="container-fluid gradient-bg text-white text-center py-5">
  <div class="container-fluid">
    <div class="floating-animation mb-4">
      <div class="rounded-circle bg-white bg-opacity-25 p-4 d-inline-flex align-items-center justify-content-center">
        <i class="bi bi-heart-pulse fs-1"></i>
      </div>
    </div>
    <h1 class="display-4 fw-bold fade-in">Votre santé de peau,<br><span class="text-white-50">notre priorité</span></h1>
    <p class="lead mt-3 fade-in">Plateforme de télédermatologie révolutionnaire. Conseils personnalisés, prévention intelligente et rendez-vous avec des spécialistes certifiés.</p>
    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3 mt-4 fade-in">
      <a href="<?php echo e(route('patient.appointments.create')); ?>" class="btn btn-primary btn-lg">
        <i class="bi bi-calendar-check me-2"></i>Prendre rendez-vous
      </a>
      <button onclick="window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});" class="btn btn-light btn-lg text-primary">
        <i class="bi bi-robot me-2"></i>Parler à l'IA
      </button>
    </div>
  </div>
</section>

<!-- CONSEILS DERMATOLOGIQUES -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-primary">Conseils dermatologiques</h2>
      <p class="lead text-muted">Découvrez nos recommandations d'experts pour prendre soin de votre peau au quotidien</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body text-center">
            <div class="bg-warning bg-gradient rounded-circle p-3 d-inline-flex mb-3">
              <i class="bi bi-sun text-white fs-4"></i>
            </div>
            <h5 class="card-title">Prévention des cancers de la peau</h5>
            <p class="card-text">Protégez-vous du soleil, surveillez vos grains de beauté et consultez un dermatologue en cas de doute.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body text-center">
            <div class="bg-info bg-gradient rounded-circle p-3 d-inline-flex mb-3">
              <i class="bi bi-droplet text-white fs-4"></i>
            </div>
            <h5 class="card-title">Hygiène et soins quotidiens</h5>
            <p class="card-text">Nettoyez votre peau matin et soir avec des produits adaptés. Hydratez régulièrement.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body text-center">
            <div class="bg-danger bg-gradient rounded-circle p-3 d-inline-flex mb-3">
              <i class="bi bi-shield-check text-white fs-4"></i>
            </div>
            <h5 class="card-title">MST/IST et santé intime</h5>
            <p class="card-text">Protégez-vous lors des rapports, faites des dépistages réguliers. La prévention est essentielle.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-primary">Nos services</h2>
      <p class="lead text-muted">Une plateforme complète pour tous vos besoins dermatologiques</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <div class="bg-primary text-white rounded-circle p-3 d-inline-flex mb-3">
              <i class="bi bi-calendar-check fs-4"></i>
            </div>
            <h5 class="card-title">Prise de rendez-vous</h5>
            <p class="card-text">Réservez une consultation avec un dermatologue certifié, en ligne ou en présentiel.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <div class="bg-success text-white rounded-circle p-3 d-inline-flex mb-3">
              <i class="bi bi-robot fs-4"></i>
            </div>
            <h5 class="card-title">Assistant IA</h5>
            <p class="card-text">Posez vos questions à notre IA spécialisée en dermatologie 24h/24.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <div class="bg-danger text-white rounded-circle p-3 d-inline-flex mb-3">
              <i class="bi bi-shield-lock fs-4"></i>
            </div>
            <h5 class="card-title">Dossier médical sécurisé</h5>
            <p class="card-text">Accédez à vos résultats et suivis en toute confidentialité avec un chiffrement avancé.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/components/welcome.blade.php ENDPATH**/ ?>