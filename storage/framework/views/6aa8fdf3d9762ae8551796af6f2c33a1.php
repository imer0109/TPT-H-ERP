

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pointage</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4><?php echo e(now()->format('d/m/Y')); ?></h4>
                        <h1 id="clock" class="display-4">00:00:00</h1>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <form action="<?php echo e(route('attendances.store')); ?>" method="POST" id="check-in-form">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <label for="employee_id">Numéro d'Employé</label>
                                    <input type="text" name="employee_id" id="employee_id" class="form-control <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required autofocus>
                                    <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\attendances\check-in.blade.php ENDPATH**/ ?>