<?php

return [

    'disk' => 'snapshots',

    'default_connection' => null,

    'temporary_directory_path' => storage_path('app/laravel-db-snapshots/temp'),

    'compress' => false,

    'tables' => null,

    'exclude' => null,

    // TAMBAHKAN INI ↓
    'dump' => [
        'dump_binary_path' => 'C:\\Users\\ACER\\AppData\\Local\\com.tinyapp.DBngin\\Binaries\\mysql\\8.4.2\\bin',
    ],
];