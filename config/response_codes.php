<?php

    /*Security*/
    define('SECRETE_KEY', 'appProcess');

    /* Error Codes*/
    define('CONTENT_TYPE_NOT_VALID',        100);
    define('REQUEST_METHOD_NOT_VALID',      102);
    define('INVALID_USER_PASS', 			103);
    define('INVALID_ACCESS_TOKEN',          104);
    define('METHOD_NOT_EXISTS',             105);
    define('CONTROLLER_NOT_EXISTS',         106);
    define('ROUTE_NOT_SPECIFIED',           107);
    define('USER_NOT_FOUND',                108);

    /*Server Errors*/
    define('JWT_PROCESSING_ERROR',			    301);
	define('AUTHORIZATION_HEADER_NOT_FOUND',    302);
    define('ACCESS_TOKEN_ERRORS',			    303);
    define('CONNECTION_DATABASE_ERROR',         304);
    define('CREATE_INSTANCE_ERROR',             305);
    define('LOGIN_ERROR',                       306);
    define('INSERTED_RECORDS_NOT_COMPLETE',     307);
    define('UPDATED_RECORDS_NOT_COMPLETE',      308);
    define('DELETED_RECORDS_NOT_COMPLETE',      309);
    define('GET_RECORDS_NOT_COMPLETE',          310);
    


    /*Success Codes*/
    define('SUCCESS_RESPONSE', 				200);
    define('RECORDS_INSERT_SUCCESSFULLY',   201);
    define('RECORDS_UPDATE_SUCCESSFULLY',   202);
    define('RECORDS_DELETE_SUCCESSFULLY',   203);
    define('GET_RECORDS_SUCCESSFULLY',      204);

    /*Data Type*/
	define('BOL', 	    1);
	define('INT64', 	2);
    define('STR', 	    3);
    define('DECIMAL', 	4);
    define('NUMERIC', 	5);
    define('VECTOR', 	6);
    define('OBJ', 	    7);
    define('FILE', 	    8);