<?php declare(strict_types=1);

    namespace Services;

    use Database\Query;

    class ObjectivesServices{

        private $query=null;

        public function __construct()
        {
            $this->query = new Query;
        }

        public function getObjectives()
        {
            try {
                $this->query->Prepare("SELECT B.BG_ID,B.BG_DESC,B.COMPANY_ID,B.PERS_ID,C.COMPANY_NAME,P.DES FROM BUSINESS_GOAL B INNER JOIN COMPANY C ON B.COMPANY_ID = C.COMPANY_ID INNER JOIN PERSPECTIVA P ON B.PERS_ID=P.ID WHERE B.STATE = 1");
                $companies = $this->query->GetRecords();
                return $companies;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function getObjectiveByDesc($desc,$idCompany,$id=null)
        {
            try {
                if(!is_null($id)){
                    $this->query->Prepare("SELECT BG_ID FROM BUSINESS_GOAL WHERE BG_DESC = :desc AND COMPANY_ID = :idCompany AND BG_ID=:id AND STATE = 1");
                    $this->query->Bind(":id",$id);
                }
                else{
                    $this->query->Prepare("SELECT BG_ID FROM BUSINESS_GOAL WHERE BG_DESC = :desc AND COMPANY_ID = :idCompany AND STATE = 1");
                }
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":idCompany",$idCompany);
                $company = $this->query->GetRecord();
                if($company)
                    return true;
                else
                    return false;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function getObjectivesByCompany(int $id)
        {
            try {
                $this->query->Prepare("SELECT B.BG_ID,B.BG_DESC,B.COMPANY_ID,B.PERS_ID,C.COMPANY_NAME,P.DES FROM BUSINESS_GOAL B INNER JOIN COMPANY C ON B.COMPANY_ID = C.COMPANY_ID INNER JOIN PERSPECTIVA P ON B.PERS_ID=P.ID WHERE B.COMPANY_ID=:id AND B.STATE = 1");
                $this->query->Bind(":id",$id);
                $companies = $this->query->GetRecords();
                return $companies;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function getObjective(int $id)
        {
            try {
                $this->query->Prepare("SELECT B.BG_ID,B.BG_DESC,B.COMPANY_ID,B.PERS_ID,C.COMPANY_NAME,P.DES FROM BUSINESS_GOAL B INNER JOIN COMPANY C ON B.COMPANY_ID = C.COMPANY_ID INNER JOIN PERSPECTIVA P ON B.PERS_ID=P.ID WHERE B.BG_ID = :id AND B.STATE = 1");
                $this->query->Bind(":id",$id);
                $company = $this->query->GetRecord();
                return $company;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }   
        }

        public function add(string $desc,int $idCompany,int $idPerspective)
        {
            try {
                $this->query->Prepare("INSERT INTO BUSINESS_GOAL(BG_DESC,COMPANY_ID,PERS_ID) VALUES (:desc,:idCompany,:idPers)");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":idCompany",$idCompany);
                $this->query->Bind(":idPers",$idPerspective);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function update(string $desc,int $idPerspective,int $id)
        {
            try {
                $this->query->Prepare("UPDATE BUSINESS_GOAL SET BG_DESC=:desc,PERS_ID=:idPers WHERE BG_ID=:id");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":idPers",$idPerspective);
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function delete(int $id)
        {
            try {
                $this->query->Prepare("UPDATE BUSINESS_GOAL SET STATE=0 WHERE BG_ID=:id");
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }
    }