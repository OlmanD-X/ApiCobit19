<?php

    /*Security*/
    define('SECRETE_KEY', 'appProcess');

    /* Error Codes*/
    define('VALIDATE_PARAMETER_REQUIRED', 	100);
    define('VALIDATE_PARAMETER_DATATYPE', 	101);
    define('PARAMETER_IS_INVALID',          102);
    define('CONTENT_TYPE_NOT_VALID',        103);
    define('SIZE_FILE_NOT_VALID',           104);
    define('EXTENSION_FILE_NOT_VALID',      105);
    define('FILE_IS_NULL',                  106);
    define('RUC_IS_INVALID',                107);
    define('EMAIL_IS_INVALID',              108);
    define('PHONE_IS_INVALID',              109);
    define('UNIT_IS_INVALID',               110);
    define('INVALID_USER_PASS', 			110);
    define('USER_NOT_ACTIVE', 				111);
    define('INVALID_ACCESS_TOKEN',          112);
    define('METHOD_NOT_EXISTS',             113);
    define('CONTROLLER_NOT_EXISTS',         114);
    define('ROUTE_NOT_SPECIFIED',           115);

    /*Server Errors*/
    define('JWT_PROCESSING_ERROR',			    301);
	define('AUTHORIZATION_HEADER_NOT_FOUND',    302);
    define('ACCESS_TOKEN_ERRORS',			    303);
    define('FILE_UPLOAD_NOT_COMPLETE',          304);
    define('INSERTED_RECORDS_NOT_COMPLETE',     305);
    define('UPDATED_RECORDS_NOT_COMPLETE',      306);
    define('DELETED_RECORDS_NOT_COMPLETE',      307);
    define('GET_RECORDS_NOT_COMPLETE',          308);
    define('CONNECTION_DATABASE_ERROR',         309);


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