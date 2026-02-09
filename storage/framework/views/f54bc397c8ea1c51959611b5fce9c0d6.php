

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Modifier un Employé</h2>
        </div>

        <form action="<?php echo e(route('hr.employees.update', $employee)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="p-6 space-y-8">
                <!-- Informations Personnelles -->
                <div>
                    <h3 class="text-xl font-semibold text-blue-700 mb-5">Informations Personnelles</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Photo -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Photo</label>
                            <div class="flex items-center space-x-6">
                                <?php if($employee->photo): ?>
                                    <img src="<?php echo e(asset('storage/' . $employee->photo)); ?>" alt="Photo de l'employé" class="w-20 h-20 rounded-full object-cover">
                                <?php endif; ?>
                                <div>
                                    <input type="file" id="photo" name="photo" accept="image/*" class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-lg file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100">
                                    <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Nom*</label>
                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                value="<?php echo e(old('last_name', $employee->last_name)); ?>"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                                required
                            >
                            <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom*</label>
                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                value="<?php echo e(old('first_name', $employee->first_name)); ?>"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                                required
                            >
                            <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Matricule -->
                        <div>
                            <label for="matricule" class="block text-sm font-medium text-gray-700">Matricule</label>
                            <input
                                type="text"
                                id="matricule"
                                name="matricule"
                                value="<?php echo e(old('matricule', $employee->matricule)); ?>"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                            >
                            <?php $__errorArgs = ['matricule'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700">Date de Naissance</label>
                            <input
                                type="date"
                                id="birth_date"
                                name="birth_date"
                                value="<?php echo e(old('birth_date', $employee->birth_date ? $employee->birth_date->format('Y-m-d') : '')); ?>"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                            >
                            <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Birth Place -->
                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700">Lieu de Naissance</label>
                            <input
                                type="text"
                                id="birth_place"
                                name="birth_place"
                                value="<?php echo e(old('birth_place', $employee->birth_place)); ?>"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                            >
                            <?php $__errorArgs = ['birth_place'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Nationality -->
                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700">Nationalité</label>
                            <input
                                type="text"
                                id="nationality"
                                name="nationality"
                                value="<?php echo e(old('nationality', $employee->nationality)); ?>"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                            >
                            <?php $__errorArgs = ['nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Genre</label>
                            <select
                                id="gender"
                                name="gender"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                            >
                                <option value="">Sélectionner le genre</option>
                                <option value="M" <?php echo e(old('gender', $employee->gender) == 'M' ? 'selected' : ''); ?>>Masculin</option>
                                <option value="F" <?php echo e(old('gender', $employee->gender) == 'F' ? 'selected' : ''); ?>>Féminin</option>
                            </select>
                            <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- ID Card Number -->
                        <div>
                            <label for="id_card_number" class="block text-sm font-medium text-gray-700">Numéro de pièce d'identité</label>
                            <input
                                type="text"
                                id="id_card_number"
                                name="id_card_number"
                                value="<?php echo e(old('id_card_number', $employee->id_card_number)); ?>"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                            >
                            <?php $__errorArgs = ['id_card_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- CNSS Number -->
                        <div>
                            <label for="cnss_number" class="block text-sm font-medium text-gray-700">Numéro CNSS</label>
                            <input
                                type="text"
                                id="cnss_number"
                                name="cnss_number"
                                value="<?php echo e(old('cnss_number', $employee->cnss_number)); ?>"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                            >
                            <?php $__errorArgs = ['cnss_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- NUI Number -->
                        <div>
                            <label for="nui_number" class="block text-sm font-medium text-gray-700">Numéro NUI</label>
                            <input
                                type="text"
                                id="nui_number"
                                name="nui_number"
                                value="<?php echo e(old('nui_number', $employee->nui_number)); ?>"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                            >
                            <?php $__errorArgs = ['nui_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                            <textarea
                                id="address"
                                name="address"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                                rows="3"
                            ><?php echo e(old('address', $employee->address)); ?></textarea>
                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Informations Professionnelles -->
                <div>
                    <h3 class="text-xl font-semibold text-blue-700 mb-5">Informations Professionnelles</h3>

                    <div class="space-y-5">
                        <!-- Company -->
                        <div>
                            <label for="current_company_id" class="block text-sm font-medium text-gray-700">Entreprise*</label>
                            <select id="current_company_id" name="current_company_id"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                                required>
                                <option value="">Sélectionner une entreprise</option>
                                <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($id); ?>" <?php echo e(old('current_company_id', $employee->current_company_id) == $id ? 'selected' : ''); ?>>
                                    <?php echo e($name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['current_company_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Agency -->
                        <div>
                            <label for="current_agency_id" class="block text-sm font-medium text-gray-700">Agence*</label>
                            <select id="current_agency_id" name="current_agency_id"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                                required>
                                <option value="">Sélectionner une agence</option>
                                <?php $__currentLoopData = $agencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($id); ?>" <?php echo e(old('current_agency_id', $employee->current_agency_id) == $id ? 'selected' : ''); ?>>
                                    <?php echo e($name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['current_agency_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Warehouse -->
                        <div>
                            <label for="current_warehouse_id" class="block text-sm font-medium text-gray-700">Dépôt</label>
                            <select id="current_warehouse_id" name="current_warehouse_id"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                                <option value="">Sélectionner un dépôt</option>
                                <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($id); ?>" <?php echo e(old('current_warehouse_id', $employee->current_warehouse_id) == $id ? 'selected' : ''); ?>>
                                    <?php echo e($name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['current_warehouse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Position -->
                        <div>
                            <label for="current_position_id" class="block text-sm font-medium text-gray-700">Poste*</label>
                            <select id="current_position_id" name="current_position_id"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                                required>
                                <option value="">Sélectionner un poste</option>
                                <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($position->id); ?>" <?php echo e(old('current_position_id', $employee->current_position_id) == $position->id ? 'selected' : ''); ?>>
                                    <?php echo e($position->title); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['current_position_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Professionnel*</label>
                            <input type="email" id="email" name="email"
                                   value="<?php echo e(old('email', $employee->email)); ?>"
                                   class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                                   required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone*</label>
                            <input type="tel" id="phone" name="phone"
                                   value="<?php echo e(old('phone', $employee->phone)); ?>"
                                   class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                                   required>
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Hire Date -->
                        <div>
                            <label for="date_embauche" class="block text-sm font-medium text-gray-700">Date d'Embauche</label>
                            <input type="date" id="date_embauche" name="date_embauche"
                                   value="<?php echo e(old('date_embauche', $employee->date_embauche ? $employee->date_embauche->format('Y-m-d') : '')); ?>"
                                   class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                            <?php $__errorArgs = ['date_embauche'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Supervisor -->
                        <div>
                            <label for="supervisor_id" class="block text-sm font-medium text-gray-700">Superviseur</label>
                            <select id="supervisor_id" name="supervisor_id"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                                <option value="">Sélectionner un superviseur</option>
                                <?php $__currentLoopData = $supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supervisor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($supervisor->id); ?>" <?php echo e(old('supervisor_id', $employee->supervisor_id) == $supervisor->id ? 'selected' : ''); ?>>
                                    <?php echo e($supervisor->first_name); ?> <?php echo e($supervisor->last_name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['supervisor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Base Salary -->
                        <div>
                            <label for="salaire_base" class="block text-sm font-medium text-gray-700">Salaire de Base (FCFA)</label>
                            <input type="number" id="salaire_base" name="salaire_base" step="0.01"
                                   value="<?php echo e(old('salaire_base', $employee->salaire_base)); ?>"
                                   class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                            <?php $__errorArgs = ['salaire_base'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="mt-10 flex justify-end gap-4 border-t pt-6 px-6">
                <a href="<?php echo e(route('hr.employees.show', $employee)); ?>"
                   class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200 shadow-sm">
                   Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                        Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\employees\edit.blade.php ENDPATH**/ ?>