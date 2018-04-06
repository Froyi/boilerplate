<?php
return [
    'js-packages' => [
        'fancybox' => '/js/fancybox.min.js',
        'ckeditor' => '/ckeditor/ckeditor.js',
        'responsive-slides' => '/js/responsiveSlides.min.js',
        'jquery' => '/js/jquery-3.2.1.min.js',
        'pageslide' => '/js/jquery.pageslide.min.js',
        'main' => '/js/main.js',
    ],
    'js-boxes' => [
        // add here route name or add something to main package
        'main' => [
            'jquery' => true,
            'responsive-slides' => true,
            'pageslide' => true,
            'main' => true,
        ]
    ]
];