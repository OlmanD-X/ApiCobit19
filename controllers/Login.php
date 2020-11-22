<?php
    namespace Controllers;

    use Services\FactoryUsers;
    use Services\User;

    class Login
    {
        public function login(){
            // $i=3;
            // $user = FactoryUsers::createUser($i);
            // $user->Create();
            echo 'hi';
            $user = new User;
            $data = $user->Login();
            print_r($data);
        }
    }