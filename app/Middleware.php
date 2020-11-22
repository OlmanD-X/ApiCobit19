<?php
    namespace app;

    use \JWT;
    use Services\User;

    class Middleware
    {
        public static function ValidateToken($url){
            if($url!='Login'){
                try {
                    $token = getBearerToken();
                    $payload = JWT::decode($token,SECRETE_KEY,['HS256']);
                    $id = $payload->userId;
                    $user = new User;
                    $tokenDB = $user->GetToken($id);
                    if(!is_null($tokenDB->TOKEN)){
                        if($token !== $tokenDB->TOKEN){
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
    