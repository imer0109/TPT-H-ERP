@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pointage</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4>{{ now()->format('d/m/Y') }}</h4>
                        <h1 id="clock" class="display-4">00:00:00</h1>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <form action="{{ route('attendances.store') }}" method="POST" id="check-in-form">
                                @csrf
                                <div class="form-group">
                                    <label for="employee_id">Numéro d'Employé</label>
                                    <input type="text" name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required autofocus>
                                    @error('employee_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="photo">Photo</label>
                                    <div class="text-center">
                                        <video id="video" width="320" height="240" class="img-fluid" style="display:none;"></video>
                                        <canvas id="canvas" width="320" height="240" class="img-fluid" style="display:none;"></canvas>
                                        <img id="photo-preview" src="" alt="" class="img-fluid mb-2" style="display:none;">
                                        <input type="hidden" name="photo" id="photo-data">
                                    </div>
                                    <button type="button" class="btn btn-secondary btn-block" id="start-camera">Activer la Caméra</button>
                                    <button type="button" class="btn btn-primary btn-block" id="take-photo" style="display:none;">Prendre la Photo</button>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-block">Pointer l'Entrée</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Horloge en temps réel
function updateClock() {
    const now = new Date();
    const clock = document.getElementById('clock');
    clock.textContent = now.toLocaleTimeString();
}
setInterval(updateClock, 1000);
updateClock();

// Gestion de la caméra
let video = document.getElementById('video');
let canvas = document.getElementById('canvas');
let photo = document.getElementById('photo-preview');
let startButton = document.getElementById('start-camera');
let takePhotoButton = document.getElementById('take-photo');

startButton.addEventListener('click', async function() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
        video.style.display = 'block';
        takePhotoButton.style.display = 'block';
        startButton.style.display = 'none';
        video.play();
    } catch (err) {
        console.error('Erreur:', err);
        alert('Impossible d\'accéder à la caméra');
    }
});

takePhotoButton.addEventListener('click', function() {
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    const imageData = canvas.toDataURL('image/jpeg');
    photo.src = imageData;
    photo.style.display = 'block';
    document.getElementById('photo-data').value = imageData;
    video.style.display = 'none';
    
    // Arrêter la caméra
    const stream = video.srcObject;
    const tracks = stream.getTracks();
    tracks.forEach(track => track.stop());
});

// Validation du formulaire
document.getElementById('check-in-form').addEventListener('submit', function(e) {
    if (!document.getElementById('photo-data').value) {
        e.preventDefault();
        alert('Veuillez prendre une photo avant de pointer');
    }
});
</script>
@endpush
@endsection