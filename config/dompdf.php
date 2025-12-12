<?php

return [
    'show_warnings' => env('APP_DEBUG', false),
    'public_path' => public_path(),
    'convert_entities' => true,

    'options' => [
        'font_dir' => storage_path('fonts/'),
        'font_cache' => storage_path('fonts/'),
        'temp_dir' => sys_get_temp_dir(),
        'chroot' => realpath(base_path()),

        'allowed_protocols' => [
            'data://' => ['rules' => []],
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],

        'artifactPathValidation' => null,
        'log_output_file' => null,
        'enable_font_subsetting' => false,
        'pdf_backend' => 'CPDF',
        'default_media_type' => 'print',
        'default_paper_size' => 'a4',
        'default_paper_orientation' => 'portrait',
        'default_font' => 'helvetica',
        'dpi' => 96,
        'enable_php' => false,
        'enable_javascript' => false,
        'enable_remote' => true,
        'allowed_remote_hosts' => null,
        'font_height_ratio' => 1.1,
        'enable_html5_parser' => true,
        'is_unicode_enabled' => true,
        'default_encoding' => 'UTF-8',

        'enable_css_float' => false, // Changed from true to false as per instructions
        'enable_file_access' => true,

        // Additional options from the instructions
        'isHtml5ParserEnabled' => true, // Added for compatibility
        'isRemoteEnabled' => true, // Added for compatibility
    ],
];
