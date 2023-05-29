<?php
    //autoload classes
    require_once __DIR__ . '/vendor/autoload.php';
    $config = parse_ini_file(__DIR__ . "/config.ini");

    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    use Cloudinary\Cloudinary;
    use Cloudinary\Transformation\Resize; //voor het resizen van de afbeelding

    //aanmaken van cloudinary
    $cloudinary = new Cloudinary(
        [
            'cloud' => [
                'cloud_name'=> $config['cloud_name'],
                'api_key'=> $config['api_key'],
                'api_secret'=> $config['api_secret'],
            ],
        ]
    );

    

