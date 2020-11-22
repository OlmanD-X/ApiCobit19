<?php declare(strict_types=1);

    namespace Models\Entities;

    use Models\Entities\User;

    class GeneralAdmin extends User{

        public function __construct(int $id,string $userName,string $pass,int $type,string $token){
            parent::__construct($id,$userName,$pass,$type,$token);
        }

        public function GetCompanyId() : int
        {
            return 1;
        }

        public function GetCompanyName() : string
        {
            return 'Soft';
        }
    }