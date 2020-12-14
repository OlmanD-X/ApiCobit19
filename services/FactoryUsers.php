<?php declare(strict_types=1);

    namespace Services;

    use Models\Entities\Admin;
    use Models\Entities\Employee;
    use Models\Entities\GeneralAdmin;
    use Models\Interfaces\IUser;

    class FactoryUsers{

        public static function createUser(object $user):IUser{

            try {
                switch ((int) $user->TYPE_USER) {
                    case 1:
                        return new GeneralAdmin((int) $user->ID,$user->USERNAME,$user->PASS,(int) $user->TYPE_USER,$user->token);
                        break;
                    case 2:
                        return new Admin((int) $user->ID,$user->USERNAME,$user->PASS,(int) $user->TYPE_USER,$user->token,(int)$user->COMPANY_ID,$user->COMPANY_NAME);
                        break;
                    case 3:
                        return new Employee((int) $user->ID,$user->USERNAME,$user->PASS,(int) $user->TYPE_USER,$user->token,(int)$user->COMPANY_ID,$user->COMPANY_NAME);
                        break;
                }
                return null;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }
            
            
        }
    }