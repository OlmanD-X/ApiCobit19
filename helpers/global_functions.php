<?php
    function throwError($code,$message)
    {
        $errorMsg = json_encode(array('status' => $code,'response' => array('message'=>$message)));
        echo $errorMsg;exit;
    }

    function returnResponse($code,$message,$data=null)
    {
        $responseMsg = json_encode(array('status' => $code,'response' => array('message'=>$message,'data'=>$data)));
        echo $responseMsg;exit;
    }

    function getAuthorizationHeader(){
        $headers = null;
        if(isset($_SERVER['Authorization'])){
            $headers = trim($_SERVER['Authorization']);
        }
        else if(isset($_SERVER['HTTP_AUTHORIZATION'])){ //Nginx or fast CGI
            $headers = trim($_SERVER['HTTP_AUTHORIZATION']);
        }
        else if(function_exists('apache_request_headers')){
            $requestHeaders = apache_request_headers(); //Server side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords',array_keys($requestHeaders)),array_values($requestHeaders));
            if(isset($requestHeaders['Authorization'])){
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    function getBearerToken(){
        $headers = getAuthorizationHeader();
        if(!empty($headers)){
            if(preg_match('/Bearer\s(\S+)/',$headers,$matches)){
                return $matches[1];
            }
        }
        throwError(AUTHORIZATION_HEADER_NOT_FOUND,'Access Token Not Found');
    }

    function validateRuc($ruc){
        if(strlen((string) $ruc)!=11){
            throwError(INVALID_PARAM,'El ruc '.$ruc.' debe tener 11 dígitos');
        }
        return $ruc;
    }

    function validateEmail($email){
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            throwError(INVALID_PARAM,'El email '.$email.' no es válido');
        }
        return $email;
    }

    function validatePhone($phone){
        $numbersOfPhone = strlen((string) $phone);
        if($numbersOfPhone!=6 && $numbersOfPhone!=9){
            throwError(INVALID_PARAM,'El telefono '.$phone.' debe tener 6 o 9 dígitos');
        }
        return $phone;
    }

    function validateAlfaNumeric($field,$value,$type){
        $regex = '';
        switch ($type) {
            case 'Alfanumeric':
                $regex = '/^[a-zA-Z0-9]+(\.?\s?[a-zA-Z0-9]*)*/';
                break;
            case 'Alfa':
                $regex = '/^[a-zA-Z]+(\.?\s?[a-zA-Z]*)*/';
                break;
            default:
                # code...
                break;
        }
        if(!preg_match($regex,$value)){
            throwError(INVALID_PARAM,'El campo '.$field.' no es válido');
        }
        return $value;
    }