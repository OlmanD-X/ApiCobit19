<?php declare(strict_types=1);

    namespace Models\Entities;

    use Models\Entities\User;
    use Models\Interfaces\IUser;

    class Admin extends User implements IUser{

        private $companyId;

        public function create()
        {
            print_r("Hello World Admin!!");
        }

    }