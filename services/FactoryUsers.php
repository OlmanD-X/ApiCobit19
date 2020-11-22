<?php declare(strict_types=1);

    namespace Services;

    use Models\Entities\Admin;
    use Models\Entities\Employee;
    use Models\Entities\GeneralAdmin;
    use Models\Interfaces\IUser;

    class FactoryUsers{

        public static function createUser(int $typeUser):IUser{
            switch ($typeUser) {
                case 1:
                    return new GeneralAdmin;
                    break;
                case 2:
                    return new Admin;
                    break;
                case 3:
                    return new Employee;
                    break;
            }
            return null;
        }
    }