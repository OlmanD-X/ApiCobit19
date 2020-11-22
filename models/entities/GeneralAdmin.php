<?php declare(strict_types=1);

    namespace Models\Entities;

    use Models\Entities\User;
    use Models\Interfaces\IUser;

    class GeneralAdmin extends User implements IUser{

        public function create()
        {
            print_r("Hello World GeneralAdmin!!");
        }
    }