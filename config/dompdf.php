<?php

return array(
    'font_dir' => storage_path('fonts/'), // The directory where fonts are stored
    'font_cache' => storage_path('fonts/'), // The directory where font cache files are stored
    'temp_dir' => sys_get_temp_dir(), // The directory where temporary files are stored
    'chroot' => realpath(base_path()), // The directory that DOMPDF can access
    'enable_font_subsetting' => false,
    'pdf_backend' => 'CPDF',
    'default_media_type' => 'screen',
    'default_paper_size' => 'a4',
    'default_font' => 'serif',
    'dpi' => 96,
    'enable_php' => false,
    'enable_javascript' => true,
    'enable_remote' => true, // Allow remote URLs
    'font_height_ratio' => 1.1,
    'enable_html5_parser' => true,
    'allowed_external_domains' => [
        'api.qrserver.com', // Allow the QR code service
        'chart.googleapis.com', // Common chart service
        'www.gravatar.com', // Common avatar service
    ],
);