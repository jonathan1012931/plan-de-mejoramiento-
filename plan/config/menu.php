<?php

return [
    [
        'id' => 'dashboard',
        'etiqueta' => '🏠 Dashboard',
        'href' => 'dashboard.php',
        'roles' => ['administrador', 'vendedor', 'consultor'],
    ],
    [
        'id' => 'productos',
        'etiqueta' => '📦 Catálogo de Productos',
        'href' => 'productos.php',
        'roles' => ['administrador', 'vendedor', 'consultor'],
    ],
    [
        'id' => 'usuarios',
        'etiqueta' => '👤 Usuarios',
        'href' => 'usuarios.php',
        'roles' => ['administrador'],
    ],
    [
        'id' => 'componentes',
        'etiqueta' => '🎨 Componentes',
        'href' => 'componentes.html',
        'roles' => ['administrador', 'vendedor', 'consultor'],
    ],

   ['id' => 'prueba', 'etiqueta' => '🧪 Prueba', 'href' => '#', 'roles' => ['administrador','vendedor','consultor'],
   ]
];
   