<?php

return [
    'mode'                       => '',
    'format'                     => 'A4',
    'default_font_size'          => '16',
    'default_font'               => 'thsarabun',
    'margin_left'                => 10,
    'margin_right'               => 10,
    'margin_top'                 => 5,
    'margin_bottom'              => 10,
    'margin_header'              => 10,
    'margin_footer'              => 1,
    'orientation'                => 'P',
    'title'                      => 'ใบปะหน้า รายการสั่งซื้อ',
    'author'                     => '',
    'watermark'                  => '',
    'show_watermark'             => false,
    'show_watermark_image'       => false,
    'watermark_font'             => 'sans-serif',
    'display_mode'               => 'fullpage',
    'watermark_text_alpha'       => 0.1,
    'watermark_image_path'       => '',
    'watermark_image_alpha'      => 0.2,
    'watermark_image_size'       => 'D',
    'watermark_image_position'   => 'P',
    'custom_font_dir'            => base_path('resources/fonts/'), // don't forget the trailing slash!
    'custom_font_data'           => [
        'thsarabun' => [
            'R' => 'THSarabunNew.ttf',
            'B' => 'THSarabunNewBold.ttf',
        ]
    ],
    'auto_language_detection'    => false,
    'temp_dir'                   => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
    'pdfa'                       => false,
    'pdfaauto'                   => false,
    'use_active_forms'           => false,
    'showImageErrors'            => true,
];
