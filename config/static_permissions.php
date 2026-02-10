<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Static Permissions Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration defines static permissions for user management.
    | These permissions are fixed and cannot be changed dynamically.
    |
    */

    'users' => [
        'administrateur' => [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.export',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete'
        ],
        
        'admin' => [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.export',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete'
        ],
                
        'manager' => [
            'users.view',
            'users.create',
            'users.edit',
            'roles.view',
            'permissions.view',
            'hr.view',
            'hr.create',
            'hr.edit',
            'hr.dashboard',
            'accounting.view',
            'accounting.create',
            'accounting.edit',
            'accounting.dashboard',
            'purchases.view',
            'purchases.create',
            'purchases.edit',
            'purchases.dashboard',
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.dashboard',
            'cash.view',
            'cash.create',
            'cash.edit',
            'cash.dashboard',
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.dashboard'
        ],
        
        'supervisor' => [
            'users.view',
            'users.edit',
            'roles.view',
            'hr.view',
            'hr.dashboard',
            'accounting.view',
            'accounting.dashboard',
            'purchases.view',
            'purchases.dashboard',
            'clients.view',
            'clients.dashboard',
            'cash.view',
            'cash.dashboard',
            'suppliers.view',
            'suppliers.dashboard'
        ],
        
        'employee' => [
            'users.view'
        ],
        
        'guest' => []
    ],

    'hr' => [
        'administrateur' => [
            'hr.view',
            'hr.create',
            'hr.edit',
            'hr.delete',
            'hr.dashboard'
        ],
        'admin' => [
            'hr.view',
            'hr.create',
            'hr.edit',
            'hr.delete',
            'hr.dashboard'
        ],
        'manager' => [
            'hr.view',
            'hr.create',
            'hr.edit',
            'hr.dashboard'
        ],
        'hr' => [
            'hr.view',
            'hr.create',
            'hr.edit',
            'hr.dashboard',
            'users.view',
            'users.create',
            'users.edit',
            'roles.view',
            'permissions.view'
        ]
    ],

    'accounting' => [
        'administrateur' => [
            'accounting.view',
            'accounting.create',
            'accounting.edit',
            'accounting.delete',
            'accounting.dashboard'
        ],
        'admin' => [
            'accounting.view',
            'accounting.create',
            'accounting.edit',
            'accounting.delete',
            'accounting.dashboard'
        ],
        'manager' => [
            'accounting.view',
            'accounting.create',
            'accounting.edit',
            'accounting.dashboard'
        ],
        'accounting' => [
            'accounting.view',
            'accounting.create',
            'accounting.edit',
            'accounting.dashboard',
            'accounting.delete'
        ]
    ],

    'purchases' => [
        'administrateur' => [
            'purchases.view',
            'purchases.create',
            'purchases.edit',
            'purchases.delete',
            'purchases.dashboard'
        ],
        'admin' => [
            'purchases.view',
            'purchases.create',
            'purchases.edit',
            'purchases.delete',
            'purchases.dashboard'
        ],
        'manager' => [
            'purchases.view',
            'purchases.create',
            'purchases.edit',
            'purchases.dashboard'
        ],
        'purchases' => [
            'purchases.view',
            'purchases.create',
            'purchases.edit',
            'purchases.dashboard',
            'purchases.delete',
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.dashboard'
        ]
    ],

    'clients' => [
        'administrateur' => [
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',
            'clients.dashboard'
        ],
        'admin' => [
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',
            'clients.dashboard'
        ],
        'manager' => [
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.dashboard'
        ],
        'commercial' => [
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.dashboard',
            'clients.delete'
        ]
    ],

    'cash' => [
        'administrateur' => [
            'cash.view',
            'cash.create',
            'cash.edit',
            'cash.delete',
            'cash.dashboard'
        ],
        'admin' => [
            'cash.view',
            'cash.create',
            'cash.edit',
            'cash.delete',
            'cash.dashboard'
        ],
        'manager' => [
            'cash.view',
            'cash.create',
            'cash.edit',
            'cash.dashboard'
        ],
        'cash' => [
            'cash.view',
            'cash.create',
            'cash.edit',
            'cash.dashboard',
            'cash.delete'
        ]
    ],

    'suppliers' => [
        'administrateur' => [
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.delete',
            'suppliers.dashboard'
        ],
        'admin' => [
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.delete',
            'suppliers.dashboard'
        ],
        'manager' => [
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.dashboard'
        ],
        'purchases' => [
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.dashboard'
        ],
        'supplier' => [
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.dashboard',
            'purchases.view',
            'purchases.dashboard'
        ],
        'suppliers' => [
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.dashboard',
            'purchases.view',
            'purchases.dashboard'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Descriptions
    |--------------------------------------------------------------------------
    |
    | Descriptions for each permission to help with understanding.
    |
    */

    'viewer' => [ // Consultant
        'administrateur' => [
            'viewer.view',
            'viewer.dashboard'
        ],
        'admin' => [
            'viewer.view',
            'viewer.dashboard'
        ],
        'manager' => [
            'viewer.view',
            'viewer.dashboard'
        ],
        'consultant' => [
            'viewer.view',
            'viewer.dashboard'
        ],
        'viewer' => [
            'viewer.view',
            'viewer.dashboard'
        ]
    ],

    'operational' => [ // Agent Opérationnel
        'administrateur' => [
            'operational.view',
            'operational.dashboard'
        ],
        'admin' => [
            'operational.view',
            'operational.dashboard'
        ],
        'manager' => [
            'operational.view',
            'operational.dashboard'
        ],
        'operational' => [
            'operational.view',
            'operational.dashboard'
        ],
        'agent_operationnel' => [
            'operational.view',
            'operational.dashboard',
            'users.view',
            'roles.view'
        ]
    ],

    'inventory' => [
        'administrateur' => [
            'inventory.view',
            'inventory.create',
            'inventory.edit',
            'inventory.delete',
            'inventory.dashboard'
        ],
        'admin' => [
            'inventory.view',
            'inventory.create',
            'inventory.edit',
            'inventory.delete',
            'inventory.dashboard'
        ],
        'manager' => [
            'inventory.view',
            'inventory.create',
            'inventory.edit',
            'inventory.dashboard'
        ],
        'inventory' => [
            'inventory.view',
            'inventory.create',
            'inventory.edit',
            'inventory.dashboard'
        ]
    ],

    'security' => [
        'administrateur' => [
            'security.view',
            'security.audit',
            'security.logs',
            'validations.workflows',
            'validations.requests'
        ],
        'admin' => [
            'security.view',
            'security.audit',
            'security.logs',
            'validations.workflows',
            'validations.requests'
        ],
        'manager' => [
            'security.view',
            'validations.workflows',
            'validations.requests'
        ]
    ],

    'api' => [
        'administrateur' => [
            'api.view',
            'api.create',
            'api.edit',
            'api.delete',
            'api.connectors',
            'api.logs'
        ],
        'admin' => [
            'api.view',
            'api.create',
            'api.edit',
            'api.delete',
            'api.connectors',
            'api.logs'
        ],
        'manager' => [
            'api.view',
            'api.connectors',
            'api.logs'
        ]
    ],

    'reports' => [
        'administrateur' => [
            'reports.view',
            'reports.generate',
            'reports.export'
        ],
        'admin' => [
            'reports.view',
            'reports.generate',
            'reports.export'
        ],
        'manager' => [
            'reports.view',
            'reports.generate'
        ]
    ],

    'services' => [
        'administrateur' => [
            'services.view',
            'services.create',
            'services.edit',
            'services.delete',
            'services.dashboard'
        ],
        'admin' => [
            'services.view',
            'services.create',
            'services.edit',
            'services.delete',
            'services.dashboard'
        ],
        'manager' => [
            'services.view',
            'services.create',
            'services.edit',
            'services.dashboard'
        ],
        'hr' => [
            'services.view',
            'services.create',
            'services.edit',
            'services.dashboard'
        ]
    ],

    'descriptions' => [
        'users.view' => 'Voir les informations utilisateurs',
        'users.create' => 'Créer de nouveaux utilisateurs',
        'users.edit' => 'Modifier les utilisateurs existants',
        'users.delete' => 'Supprimer des utilisateurs',
        'users.export' => 'Exporter les données utilisateurs',
        'roles.view' => 'Voir les rôles',
        'roles.create' => 'Créer de nouveaux rôles',
        'roles.edit' => 'Modifier les rôles existants',
        'roles.delete' => 'Supprimer des rôles',
        'permissions.view' => 'Voir les permissions',
        'permissions.create' => 'Créer de nouvelles permissions',
        'permissions.edit' => 'Modifier les permissions existantes',
        'permissions.delete' => 'Supprimer des permissions',
        'viewer.view' => 'Voir le tableau de bord consultant',
        'viewer.dashboard' => 'Accéder au tableau de bord consultant',
        'accounting.view' => 'Voir le tableau de bord comptable',
        'accounting.dashboard' => 'Accéder au tableau de bord comptable',
        'operational.view' => 'Voir le tableau de bord opérationnel',
        'operational.dashboard' => 'Accéder au tableau de bord opérationnel',
        'hr.view' => 'Voir le tableau de bord RH',
        'hr.dashboard' => 'Accéder au tableau de bord RH',
        'hr.dashboard' => 'Tableau de bord Ressources Humaines',
        'purchases.dashboard' => 'Tableau de bord Achats',
        'suppliers.dashboard' => 'Tableau de bord Fournisseurs',
        'clients.dashboard' => 'Tableau de bord Clients',
        'cash.dashboard' => 'Tableau de bord Caisse',
        'inventory.dashboard' => 'Tableau de bord Stock',
        'accounting.balance' => 'Accéder à la balance',
        'accounting.general_ledger' => 'Accéder au grand livre',
        'validations.workflows' => 'Gérer les workflows de validation'
    ]
];