<?php

    //DIRECTORY
    define('APP_PATH',dirname(dirname(__FILE__)));
    define('DS',DIRECTORY_SEPARATOR);

    define("WEB_DEVELOPMENT", true);

    //DATABASE
    define('DB_HOST_ENV','localhost');
    define('DB_USER_ENV','root');
    define('DB_PASSWORD_ENV','123456');
    define('DB_NAME_ENV','cobit19');
    define('ROUTE_URL_ENV','apiCobit19.com');

    define('DB_HOST_PROD','');
    define('DB_USER_PROD','');
    define('DB_PASSWORD_PROD','');
    define('DB_NAME_PROD','');
    define('ROUTE_URL_PROD','');

    define('DB_HOST',WEB_DEVELOPMENT ? DB_HOST_ENV : DB_HOST_PROD);
    define('DB_USER',WEB_DEVELOPMENT ? DB_USER_ENV : DB_USER_PROD);
    define('DB_PASSWORD',WEB_DEVELOPMENT ? DB_PASSWORD_ENV : DB_PASSWORD_PROD);
    define('DB_NAME',WEB_DEVELOPMENT ? DB_NAME_ENV : DB_NAME_PROD);
    define('ROUTE_URL',WEB_DEVELOPMENT ? ROUTE_URL_ENV : ROUTE_URL_PROD);