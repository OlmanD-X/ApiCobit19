<?php
    namespace App;

    use Libraries\JWT;
    use Models\Entities\User;

    class Middleware
    {
        public static function ValidateToken($url){
            if($url!='Login'){
                try {
                    $token = getBearerToken();
                    $payload = JWT::decode($token,SECRETE_KEY,['HS256']);
                    $id = $payload->userId;
                    $tokenDB = User::GetToken((int) $id);
                    if(!is_null($tokenDB)){
                        if($token !== $tokenDB){
                            returnResponse(INVALID_ACCESS_TOKEN,'Invalid Token. Please login.');
                        }
                    }
                    else{
                        returnResponse(INVALID_ACCESS_TOKEN,'Not logged in. Please login.');
                    }
                } catch (\Throwable $th) {
                    throwError(ACCESS_TOKEN_ERRORS,$th->getMessage());
                }
            }
        }


    }
    