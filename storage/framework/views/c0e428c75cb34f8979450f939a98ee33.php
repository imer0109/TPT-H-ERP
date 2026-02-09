<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Certificat de Travail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            margin: 0;
            text-transform: uppercase;
        }
        
        .header p {
            margin: 5px 0;
            font-size: 16px;
        }
        
        .content {
            margin: 30px 0;
        }
        
        .content p {
            margin: 10px 0;
            text-align: justify;
        }
        
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        
        .signature-place {
            font-style: italic;
            margin-bottom: 60px;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 40px;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        .stamp {
            position: absolute;
            right: 50px;
            top: 150px;
            border: 2px solid #000;
            padding: 10px;
            text-align: center;
            width: 150px;
        }
        
        .stamp-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .stamp-text {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo e(config('app.name', 'TPT-H ERP')); ?></h1>
        <p><?php echo e(config('company.address', 'Adresse de l\'entreprise')); ?></p>
        <p>Tél: <?php echo e(config('company.phone', '')); ?> | Email: <?php echo e(config('company.email', '')); ?></p>
    </div>
    
    <div class="stamp">
        <div class="stamp-title">CACHET</div>
        <div class="stamp-text">Entreprise</div>
        <div class="stamp-text">Agissant</div>
        <div class="stamp-text">Sous</div>
        <div class="stamp-text">Signature</div>
    </div>
    
    <div class="content">
        <h2 style="text-align: center; text-decoration: underline;">CERTIFICAT DE TRAVAIL</h2>
        <p style="text-align: center; margin-bottom: 30px;">N°: CT-<?php echo e(date('Y')); ?>-<?php echo e(str_pad($employee->id, 4, '0', STR_PAD_LEFT)); ?></p>
        
        <p>Je soussigné(e), <?php echo e(config('company.representative', 'Représentant de l\'entreprise')); ?>, certifie par la présente que :</p>
        
        <p><strong><?php echo e($employee->full_name); ?></strong>, de nationalité <?php echo e($employee->nationality ?? 'Congolaise'); ?>, 
        né(e) le <?php echo e($employee->birth_date ? $employee->birth_date->format('d/m/Y') : 'N/A'); ?> à <?php echo e($employee->birth_place ?? 'N/A'); ?>,
        demeurant à <?php echo e($employee->address ?? 'N/A'); ?>, matricule <?php echo e($employee->matricule ?? 'N/A'); ?>,
        a été employé(e) dans notre entreprise en qualité de <strong><?php echo e($employee->currentPosition->title ?? 'N/A'); ?></strong>
        du <?php echo e($employee->date_embauche ? $employee->date_embauche->format('d/m/Y') : 'N/A'); ?>

        <?php echo e($end_date ? 'au ' . $end_date->format('d/m/Y') : 'à ce jour'); ?>.</p>
        
        <p>Son comportement a été <?php echo e($reason === 'licenciement' ? 'correct jusqu\'à son licenciement' : 'exemplaire'); ?> 
        et ses rapports avec la hiérarchie et ses collègues ont toujours été excellents.</p>
        
        <?php if($additional_info): ?>
        <p><?php echo e($additional_info); ?></p>
        <?php endif; ?>
        
        <p>Ce certificat lui est délivré à sa demande pour servir et valoir ce que de droit.</p>
    </div>
    
    <div class="signature">
        <p class="signature-place">Fait à <?php echo e(config('company.city', 'Brazzaville')); ?>, le <?php echo e($generated_date->format('d/m/Y')); ?></p>
        <p class="signature-name"><?php echo e(config('company.representative', 'Représentant de l\'entreprise')); ?></p>
        <p><?php echo e(config('company.representative_title', 'Titre du représentant')); ?></p>
    </div>
    
    <div class="footer">
        <p><?php echo e(config('app.name', 'TPT-H ERP')); ?> - Certificat de Travail - Document officiel</p>
    </div>
</body>
</html><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\hr\documents\pdf\work-certificate.blade.php ENDPATH**/ ?>