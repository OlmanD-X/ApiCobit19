<?php declare(strict_types=1);

    namespace Services;

    use Database\Query;

    class AreaService{

        private $query=null;

        public function __construct()
        {
            $this->query = new Query;
        }

        public function getAreas($id)
        {
            try {
                $this->query->Prepare("SELECT ID,DES,COMPANY_ID FROM AREAS WHERE COMPANY_ID = :id");
                $this->query->Bind(':id',$id);
                $areas = $this->query->GetRecords();
                return $areas;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function getAreaByDes($des,$id)
        {
            try {
                $this->query->Prepare("SELECT ID FROM AREAS WHERE COMPANY_ID = :id AND DES=:des");
                $this->query->Bind(":id",$id);
                $this->query->Bind(":des",$des);
                $area = $this->query->GetRecord();
                if($area)
                    return true;
                else
                    return false;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function getAreaByDes2($des,$id,$idCompany)
        {
            try {
                $this->query->Prepare("SELECT ID FROM AREAS WHERE ID != :id AND DES=:des AND COMPANY_ID = :idCompany");
                $this->query->Bind(":id",$id);
                $this->query->Bind(":des",$des);
                $this->query->Bind(":idCompany",$idCompany);
                $area = $this->query->GetRecord();
                if($area)
                    return true;
                else
                    return false;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function getArea(int $id)
        {
            try {
                $this->query->Prepare("SELECT ID,DES,COMPANY_ID FROM AREAS WHERE ID = :id");
                $this->query->Bind(":id",$id);
                $area = $this->query->GetRecord();
                return $area;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }   
        }

        public function add(string $des,int $id)
        {
            try {
                $this->query->Prepare("INSERT INTO AREAS(DES,COMPANY_ID) VALUES (:des,:id)");
                $this->query->Bind(":des",$des);
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function update(string $des,int $id)
        {
            try {
                $this->query->Prepare("UPDATE AREAS SET DES=:des WHERE ID=:id");
                $this->query->Bind(":des",$des);
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function delete(int $id)
        {
            try {
                $this->query->Prepare("DELETE FROM AREAS WHERE ID=:id");
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }
    }