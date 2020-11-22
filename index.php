<?php

    require_once 'config/config.php';
    require_once 'config/response_codes.php';
    require_once 'helpers/autoload.php';

    use App\Kernel;
    use App\Middleware;

    $url = Kernel::GetUrl();

    Middleware::ValidateToken($url[0]);

    Kernel::Process();
    
