@extends('layouts.medecin')

@section('content')
<style>
    .prescription-detail-container {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .detail-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 2.5rem;
        max-width: 900px;
        margin: 0 auto;
        border: 1px solid #e9ecef;
    }
    
    .detail-header {
        text-align: center;
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f1f3f4;
        position: relative;
    }
    
    .detail-header::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 2px;
        background: linear-gradient(90deg, #17a2b8, #138496);
        border-radius: 1px;
    }
    
    .detail-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
    }
    
    .detail-subtitle {
        color: #6c757d;
        font-size: 1rem;
        margin: 0;
    }
    
    .prescription-card {
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border: 2px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        position: relative;
    }
    
    .prescription-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #17a2b8, #138496);
    }
    
    .card-header-modern {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .prescription-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .card-body-modern {
        padding: 2rem;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .info-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.2rem;
        border-left: 4px solid #17a2b8;
        transition: all 0.3s ease;
    }
    
    .info-item:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .info-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-value {
        font-size: 1.1rem;
        font-weight: 500;
        color: #2c3e50;
        margin: 0;
        line-height: 1.4;
    }
    
    .description-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        border-left: 4px solid #28a745;
        margin: 1.5rem 0;
    }
    
    .description-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .description-content {
        color: #495057;
        line-height: 1.6;
        font-size: 1rem;
        margin: 0;
        white-space: pre-line;
    }
    
    .file-section {
        background: linear-gradient(135deg, #e8f5e8, #f0f8f0);
        border: 2px solid #d4edda;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        margin: 1.5rem 0;
    }
    
    .file-icon-display {
        font-size: 3rem;
        color: #28a745;
        margin-bottom: 1rem;
    }
    
    .file-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #155724;
        margin-bottom: 0.5rem;
    }
    
    .file-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    
    .download-btn {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .download-btn:hover {
        background: linear-gradient(135deg, #218838, #1ea97c);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .no-file-message {
        color: #6c757d;
        font-style: italic;
        text-align: center;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 8px;
        border: 2px dashed #dee2e6;
    }
    
    .actions-section {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 2px solid #f1f3f4;
    }
    
    .btn-modern {
        padding: 0.9rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border: 2px solid;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        min-width: 140px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-modern:hover::before {
        left: 100%;
    }
    
    .btn-warning-modern {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
        border-color: #ffc107;
    }
    
    .btn-warning-modern:hover {
        background: linear-gradient(135deg, #e0a800, #e8590c);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-secondary-modern {
        background: white;
        color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-secondary-modern:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        text-decoration: none;
    }
    
    .status-badge {
        background: linear-gradient(135deg, #d1ecf1, #bee5eb);
        color: #0c5460;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: absolute;
        top: 1rem;
        right: 1rem;
    }
    
    @media (max-width: 768px) {
        .prescription-detail-container {
            padding: 1rem 0;
        }
        
        .detail-wrapper {
            margin: 0 1rem;
            padding: 1.5rem;
        }
        
        .detail-title {
            font-size: 1.6rem;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .actions-section {
            flex-direction: column;
            gap: 0.8rem;
        }
        
        .btn-modern {
            width: 100%;
        }
        
        .card-body-modern {
            padding: 1.5rem;
        }
        
        .prescription-title {
            font-size: 1.2rem;
        }
        
        .status-badge {
            position: static;
            display: inline-block;
            margin-top: 1rem;
        }
    }
    
    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="prescription-detail-container">
    <div class="detail-wrapper fade-in">
        <div class="detail-header">
            <h1 class="detail-title">
                <span>📋</span>
                Détail de l'ordonnance
            </h1>
            <p class="detail-subtitle">Informations complètes de la prescription</p>
        </div>

        <div class="prescription-card">
            <div class="status-badge">Ordonnance Active</div>
            
            <div class="card-header-modern">
                <h2 class="prescription-title">
                    <span>📌</span>
                    {{ $ordonnance->titre }}
                </h2>
            </div>

            <div class="card-body-modern">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <span>👤</span>
                            Patient
                        </div>
                        <p class="info-value">{{ $ordonnance->patient->name ?? 'Non assigné' }}</p>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <span>📅</span>
                            Date de prescription
                        </div>
                        <p class="info-value">{{ \Carbon\Carbon::parse($ordonnance->date_prescription)->format('d/m/Y') }}</p>
                    </div>
                </div>

                @if($ordonnance->description)
                    <div class="description-section">
                        <div class="description-label">
                            <span>📝</span>
                            Description détaillée
                        </div>
                        <p class="description-content">{{ $ordonnance->description }}</p>
                    </div>
                @endif

                @if($ordonnance->fichier)
                    <div class="file-section">
                        <div class="file-icon-display">📄</div>
                        <h4 class="file-title">Fichier joint disponible</h4>
                        <p class="file-subtitle">{{ basename($ordonnance->fichier) }}</p>
                        <a href="{{ asset('storage/' . $ordonnance->fichier) }}" 
                           target="_blank" 
                           class="download-btn">
                            <span>📥</span>
                            Télécharger / Voir le fichier
                        </a>
                    </div>
                @else
                    <div class="no-file-message">
                        <span>📎</span> Aucun fichier joint à cette ordonnance
                    </div>
                @endif
            </div>
        </div>

        <div class="actions-section">
            <a href="{{ route('medecin.ordonnances.edit', $ordonnance) }}" class="btn btn-modern btn-warning-modern">
                <span>✏️</span>
                Modifier
            </a>
            <a href="{{ route('medecin.ordonnances.index') }}" class="btn btn-modern btn-secondary-modern">
                <span>↩️</span>
                Retour à la liste
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'apparition progressive pour les éléments
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    document.querySelectorAll('.info-item, .description-section, .file-section').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });

    // Effet de copie du nom du patient au clic
    document.querySelector('.info-item:first-child .info-value').addEventListener('click', function() {
        const patientName = this.textContent;
        if (navigator.clipboard && patientName !== 'Non assigné') {
            navigator.clipboard.writeText(patientName).then(() => {
                // Feedback visuel temporaire
                const originalBg = this.parentElement.style.backgroundColor;
                this.parentElement.style.backgroundColor = '#d4edda';
                setTimeout(() => {
                    this.parentElement.style.backgroundColor = originalBg;
                }, 1000);
            });
        }
    });

    // Confirmation avant modification
    document.querySelector('.btn-warning-modern').addEventListener('click', function(e) {
        if (!confirm('Voulez-vous modifier cette ordonnance ?')) {
            e.preventDefault();
        }
    });

    // Tracking des clics sur le fichier
    const downloadBtn = document.querySelector('.download-btn');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            console.log('Fichier consulté:', this.href);
        });
    }
});
</script>

@endsection