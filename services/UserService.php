<?php declare(strict_types=1);
    namespace Services;

    use Database\Query;
    use Models\Entities\User;
    use Libraries\JWT;

    class UserService{

        private $query=null;

        public function __construct()
        {
            $this->query = new Query;
        }

        public function Login(string $user,string $pass)
        {
            try {
                $user = User::Login($user);
                if(!is_object($user))
                    return USER_NOT_FOUND;

                $validPassword = password_verify($pass,$user->PASS) ? true : false;
                if(!$validPassword)
                    return INVALID_USER_PASS;
                
                $user->token = $this->GenerateToken($user);
                $oUser = FactoryUsers::createUser($user);

                if(is_null($oUser))
                    return CREATE_INSTANCE_ERROR;

                if(!User::SetToken($oUser->GetTokenN(),$oUser->GetId()))
                    return ACCESS_TOKEN_ERRORS;

                return $oUser;
                
            } catch (\Throwable $th) {
                return $th->getMessage();
            }
        }

        private function GenerateToken(object $user):string{
            $payload = array(
                'iat'=>time(),
                'iss' => 'localhost',
                'exp' => time() + (24*60*60),
                'userId' => $user->ID,
                'name' => $user->USERNAME,
                'type' => $user->TYPE_USER,
                'companyId' => $user->COMPANY_ID,
                'company' => $user->COMPANY_NAME,
            );

            return JWT::encode($payload,SECRETE_KEY);
        }  
    }