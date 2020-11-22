<?php
    spl_autoload_register(
        function($className){
            $className = APP_PATH.DS.str_replace("\\",DS,$className).'.php';
            if (!file_exists($className)) {
                throw new Exception("Error Processing Request.$className Not Found", 1);
            }
            require_once $className;
        }
    );