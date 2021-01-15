<?php declare(strict_types=1);

    namespace Services;

    use Database\Query;

    class InitiativeService{

        private $query=null;

        public function __construct()
        {
            $this->query = new Query;
        }

        public function getInitiatives($id)
        {
            try {
                $this->query->Prepare("SELECT ID,DES,FECHA_MAX,COMPANY_ID FROM INI WHERE ESTADO=1 AND COMPANY_ID=:id");
                $this->query->Bind(":id",$id);
                $initiatives = $this->query->GetRecords();
                foreach ($initiatives as $key => $value) {
                    $initiatives[$key]->OE = $this->getOEvsINI($value->ID);
                }
                
                return $initiatives;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getInitiative($id)
        {
            try {
                $this->query->Prepare("SELECT ID,DES,FECHA_MAX,COMPANY_ID FROM INI WHERE ID=:id");
                $this->query->Bind(":id",$id);
                $initiative = $this->query->GetRecord();
                $initiative->OE = $this->getOEvsINI($initiative->ID);
                return $initiative;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getInitiativeByDesc($desc,$id)
        {
            try {
                $this->query->Prepare("SELECT ID,DES,FECHA_MAX,COMPANY_ID FROM INI WHERE DES=:desc AND COMPANY_ID=:id AND ESTADO=1");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":id",$id);
                $initiative = $this->query->GetRecord();
                return $initiative;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getInitiativeByDescEdit($desc,$id,$idCompany)
        {
            try {
                $this->query->Prepare("SELECT ID,DES,FECHA_MAX,COMPANY_ID FROM INI WHERE DES=:desc AND ID!=:id AND COMPANY_ID=:idCompany");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":id",$id);
                $this->query->Bind(":idCompany",$idCompany);
                $initiative = $this->query->GetRecord();
                return $initiative;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function add($desc,$id,$fecha,$relations)
        {
            try {
                $this->query->Prepare("INSERT INTO INI(DES,FECHA_MAX,COMPANY_ID) VALUES(:desc,:fecha,:id)");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":fecha",$fecha);
                $this->query->Bind(":id",$id);
                $bool =  $this->query->Execute();
                if($bool){
                    $this->query->Prepare("SELECT MAX(ID) AS ID FROM INI");
                    $ID = $this->query->GetRecord();
                    $ID = $ID->ID;
                    foreach ($relations as $key => $value) {
                        $bool = $this->addOEvsINI($ID,$value);
                    }
                    return $bool;
                }
                return false;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function delete($id)
        {
            try {
                $this->query->Prepare("UPDATE INI SET ESTADO=0 WHERE ID=:id");
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function update($desc,$id,$fecha,$relations)
        {
            try {
                $this->query->Prepare("UPDATE INI SET DES=:desc,FECHA_MAX=:fecha WHERE ID=:id");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":fecha",$fecha);
                $this->query->Bind(":id",$id);
                $bool =  $this->query->Execute();
                if($bool){
                    $bool = $this->deleteOEvsINI($id);
                    if($bool){
                        foreach ($relations as $key => $value) {
                            $bool = $this->addOEvsINI($id,$value);
                        }
                        return $bool;
                    }
                }
                return false;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function addOEvsINI($idINI,$idOE)
        {
            try {
                $this->query->Prepare("INSERT INTO REL_INI_OE(INI,OE) VALUES(:ini,:oe)");
                $this->query->Bind(":ini",$idINI);
                $this->query->Bind(":oe",$idOE);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function deleteOEvsINI($id)
        {
            try {
                $this->query->Prepare("DELETE FROM REL_INI_OE WHERE INI=:id");
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            }
        }

        public function getOEvsINI($id)
        {
            try {
                $this->query->Prepare("SELECT R.INI,R.OE,BG.BG_DESC FROM REL_INI_OE R INNER JOIN BUSINESS_GOAL BG ON R.OE=BG.BG_ID WHERE INI=:id");
                $this->query->Bind(":id",$id);
                $data = $this->query->GetRecords();
                return $data;
            return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            }
        }
    }