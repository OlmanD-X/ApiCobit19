<?php
    namespace Controllers;

    use Services\UserService;
    use Models\Entities\User;

    class Login
    {
        /**
         * Loguea un usuario. Método http => POST
         * 
         * @param string $userName Nombre del usuario
         * @param string $pass Contraseña del usuario
         * @api
         */
        public function login(){

            if($_SERVER['REQUEST_METHOD']!=='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $userName = $_POST['username'] ?? null;
            $pass = $_POST['pass'] ?? null;

            if(is_null($userName) || is_null($pass))
                throwError(INVALID_USER_PASS,'User or password not send');

            $userService = new UserService;
            $user = $userService->Login($userName,$pass);

            $this->ValidateInstance($user);

            $data = array('token' => $user->GetTokenN(),'user'=>$user->GetUserName(),'type'=>$user->GetType(),'company'=>$user->GetCompanyName(),'companyId'=>$user->GetCompanyId());

            returnResponse(SUCCESS_RESPONSE,'Successful login',$data);
        }

        /** 
         * Este método es de uso interno
         * Valida la creación de una instancia de usuario
         * 
         * @param User $user Instancia a validar
         * @return void
         * @access private
        */

        private function ValidateInstance($user)
        {
            $code = 0;
            $message = '';
            $isCorrect = false;

            if(is_string($user))
                throwError(LOGIN_ERROR,'An error occurred on the server.');

            if($user instanceof User)
                return;

            switch ($user) {
                case USER_NOT_FOUND:
                    $code = USER_NOT_FOUND;
                    $message = 'User not found in our database.';
                    break;
                case INVALID_USER_PASS:
                    $code = INVALID_USER_PASS;
                    $message = 'Invalid password.';
                    break;
                case CREATE_INSTANCE_ERROR:
                    $code = CREATE_INSTANCE_ERROR;
                    $message = 'An error occurred on the server.';
                    break;
                case ACCESS_TOKEN_ERRORS:
                    $code = ACCESS_TOKEN_ERRORS;
                    $message = 'The token could not be updated.';
                    break;
                default:
                    $isCorrect = true;
                    break;
            }

            if(!$isCorrect)
                throwError($code,$message);
        }
    }