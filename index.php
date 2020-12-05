<?php
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Headers:Authorization');
    header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE');
    header('Content-Type: application/json');
    date_default_timezone_set("America/Lima");

    require_once 'config/config.php';
    require_once 'config/response_codes.php';
    require_once 'helpers/autoload.php';
    require_once 'helpers/global_functions.php';

    use App\Kernel;
    use App\Middleware;

    $url = Kernel::GetUrl();

    Middleware::ValidateToken($url[0]);

    Kernel::Process($url);
    
