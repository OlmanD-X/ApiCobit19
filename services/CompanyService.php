<?php declare(strict_types=1);

    namespace Services;

    use Database\Query;

    class CompanyService{

        private $query=null;

        public function __construct()
        {
            $this->query = new Query;
        }

        public function getCompanies()
        {
            try {
                $this->query->Prepare("SELECT COMPANY_ID,COMPANY_NAME,RUC,PHONE,EMAIL FROM COMPANY WHERE STATE = 1");
                $companies = $this->query->GetRecords();
                return $companies;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function getCompanyByName($name,$id=null)
        {
            try {
                if(!is_null($id)){
                    $this->query->Prepare("SELECT COMPANY_ID FROM COMPANY WHERE COMPANY_NAME = :company AND COMPANY_ID != :id");
                    $this->query->Bind(":id",$id);
                }
                else{
                    $this->query->Prepare("SELECT COMPANY_ID FROM COMPANY WHERE COMPANY_NAME = :company");
                }
                $this->query->Bind(":company",$name);
                $company = $this->query->GetRecord();
                if($company)
                    return true;
                else
                    return false;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function getCompanyByRuc($ruc,$id=null)
        {
            try {
                if(!is_null($id)){
                    $this->query->Prepare("SELECT COMPANY_ID FROM COMPANY WHERE RUC = :ruc AND COMPANY_ID != :id");
                    $this->query->Bind(":id",$id);
                }
                else{
                    $this->query->Prepare("SELECT COMPANY_ID FROM COMPANY WHERE RUC = :ruc");
                }
                $this->query->Bind(":ruc",$ruc);
                $company = $this->query->GetRecord();
                if($company)
                    return true;
                else
                    return false;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function getCompanyByPhone($phone,$id=null)
        {
            try {
                if(!is_null($id)){
                    $this->query->Prepare("SELECT COMPANY_ID FROM COMPANY WHERE PHONE = :phone AND COMPANY_ID != :id");
                    $this->query->Bind(":id",$id);
                }
                else{
                    $this->query->Prepare("SELECT COMPANY_ID FROM COMPANY WHERE PHONE = :phone");
                }
                $this->query->Bind(":phone",$phone);
                $company = $this->query->GetRecord();
                if($company)
                    return true;
                else
                    return false;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function getCompany(int $id)
        {
            try {
                $this->query->Prepare("SELECT COMPANY_ID,COMPANY_NAME,RUC,PHONE,EMAIL FROM COMPANY WHERE COMPANY_ID = :id");
                $this->query->Bind(":id",$id);
                $company = $this->query->GetRecord();
                return $company;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }   
        }

        public function add(string $name,int $ruc,string $email, int $phone)
        {
            try {
                $this->query->Prepare("INSERT INTO COMPANY(COMPANY_NAME,RUC,PHONE,EMAIL) VALUES (:company,:ruc,:phone,:email)");
                $this->query->Bind(":company",$name);
                $this->query->Bind(":ruc",$ruc);
                $this->query->Bind(":phone",$phone);
                $this->query->Bind(":email",$email);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function update(string $name,int $ruc,string $email, int $phone,int $id)
        {
            try {
                $this->query->Prepare("UPDATE COMPANY SET COMPANY_NAME=:name,RUC=:ruc,PHONE=:phone,EMAIL=:email WHERE COMPANY_ID=:id");
                $this->query->Bind(":name",$name);
                $this->query->Bind(":ruc",$ruc);
                $this->query->Bind(":phone",$phone);
                $this->query->Bind(":email",$email);
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function delete(int $id)
        {
            try {
                $this->query->Prepare("UPDATE COMPANY SET STATE=0 WHERE COMPANY_ID=:id");
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }
    }