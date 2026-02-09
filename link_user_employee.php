<?php

require_once __DIR__.'/bootstrap/app.php';

use App\Models\Employee;

// Lier l'utilisateur ID 1 (administrateur) à l'employé ID 3
$employee = Employee::find(3);
if ($employee) {
    $employee->user_id = 1;
    $employee->save();
    echo "L'employé ID 3 a été lié à l'utilisateur ID 1\n";
} else {
    echo "Employé non trouvé\n";
}