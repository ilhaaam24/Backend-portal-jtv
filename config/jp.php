<?php

return [
    'api_paginate' => env('API_PAGINATE', 10),
    'data_paginate' => env('DATA_PAGINATE', 10),
    'related_news' => env('RELATED_NEWS', 10),
    'fall_img' => env('FALL_IMG'),
    'fall_thumb' => env('FALL_THUMB'),
    'maxlimit' => env('API_MAXLIMIT', 100),
    'path_img' => env('PATH_IMG', 'assets/upload-gambar/'),
    'path_img_berita' => env('PATH_IMG_BERITA', 'uploads/gallery/'),
    'path_img_profile' => env('PATH_IMG_POFILE', 'assets/foto-profil/'),
    'path_img_iklan' => env('PATH_IMG_IKLAN', 'assets/iklan/'),
    'path_img_sorot' => env('PATH_IMG_SOROT', 'assets/sorot/'),

    'path_url_web' => env('PATH_URL_WEB', 'http://cms.portaljtv.com/'),
    'path_url_fe' => env('PATH_URL_FE', 'https://portaljtv.com/'),
    'path_url_be' => env('PATH_URL_BE', 'http://cms.portaljtv.com/'),
    'path_url_no_img' => env('PATH_URL_NO_IMG', 'assets/broken.webp'),
    
];

