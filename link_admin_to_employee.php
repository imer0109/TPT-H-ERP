<?php

require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

// Create a new Capsule instance
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'tptHerp_db',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Récupérer tous les utilisateurs avec le rôle administrateur
$admins = Capsule::table('users')
    ->join('role_user', 'users.id', '=', 'role_user.user_id')
    ->join('roles', 'role_user.role_id', '=', 'roles.id')
    ->where('roles.slug', 'admin')
    ->select('users.id', 'users.nom', 'users.prenom')
    ->get();

echo "Administrateurs trouvés :\n";
foreach ($admins as $admin) {
    echo "- ID: {$admin->id}, Nom: {$admin->prenom} {$admin->nom}\n";
}

// Récupérer tous les employés
$employees = Capsule::table('employees')->get();

echo "\nEmployés trouvés :\n";
foreach ($employees as $employee) {
    echo "- ID: {$employee->id}, Nom: {$employee->first_name} {$employee->last_name}\n";
}

if ($admins->count() > 0 && $employees->count() > 0) {
    $userId = $admins[0]->id;
    $employeeId = $employees[0]->id;
    
    // Mettre à jour l'employé avec l'ID de l'utilisateur
    $result = Capsule::table('employees')
        ->where('id', $employeeId)
        ->update(['user_id' => $userId]);
    
    if ($result) {
        echo "\nL'administrateur a été lié à l'employé avec succès.\n";
    } else {
        echo "\nErreur lors de la liaison de l'administrateur à l'employé.\n";
    }
} else {
    echo "\nImpossible de lier un administrateur à un employé : données insuffisantes.\n";
}