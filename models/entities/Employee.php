<?php declare(strict_types=1);

    namespace Models\Entities;

    use Models\Entities\User;

    class Employee extends User{

        private $companyId;
        private $companyName;

        public function __construct(int $id,string $userName,string $pass,int $type,string $token,int $companyId,string $companyName){
            parent::__construct($id,$userName,$pass,$type,$token);
            $this->companyId = $companyId;
            $this->companyName = $companyName;
        }

        public function GetCompanyId() : int
        {
            return $this->companyId;
        }

        public function GetCompanyName() : string
        {
            return $this->companyName;
        }
    }