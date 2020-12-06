<?php declare(strict_types=1);

    namespace Services;

    use Database\Query;

    class Cobit19Service{
        private $query=null;

        public function __construct()
        {
            $this->query = new Query;
        }

        public function getEG()
        {
            try {
                $this->query->Prepare("SELECT ID,DES,PERS_ID,COD FROM EG");
                $EG = $this->query->GetRecords();
                return $EG;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getAG()
        {
            try {
                $this->query->Prepare("SELECT ID,DES,COD FROM AG");
                $EG = $this->query->GetRecords();
                return $EG;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getOC()
        {
            try {
                $this->query->Prepare("SELECT ID,DES,COD FROM OC");
                $EG = $this->query->GetRecords();
                return $EG;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function mapAG($EG)
        {
            if(!is_array($EG))
                return INVALID_PARAM;

            $AG = array();
            
            foreach ($EG as $eg) {
                try {
                    $this->query->Prepare("SELECT AG FROM EGvsAG WHERE EG = $eg AND VAL = 'P'");
                    $ag = $this->query->GetRecords();
                    foreach ($ag as $val) {
                        array_push($AG,$val->AG);
                    }
                } catch (\Throwable $th) {
                    return $th->getMessage();
                } 
            }

            $AG = array_unique($AG,SORT_NUMERIC);

            sort($AG);

            return $AG;
        }

        public function mapOC($AG)
        {
            if(!is_array($AG))
                return INVALID_PARAM;

            $OC = array();
            
            foreach ($AG as $ag) {
                try {
                    $this->query->Prepare("SELECT OC FROM AGvsOC WHERE AG = $ag AND VAL = 'P'");
                    $oc = $this->query->GetRecords();
                    foreach ($oc as $val) {
                        array_push($OC,$val->OC);
                    }
                } catch (\Throwable $th) {
                    return $th->getMessage();
                } 
            }

            $OC = array_unique($OC,SORT_NUMERIC);

            sort($OC);

            return $OC;
        }

        public function addHist($id,$desc)
        {
            try {
                $version = $this->getHistByDesc($desc,$id);
                if(is_string($version))
                    return false;
                if($version==0){
                    $this->query->Prepare("INSERT INTO HISTORIAL(DES,COMPANY_ID) VALUES(:desc,:id)");
                }
                else{
                    $idAux = $this->getIdHistForAdd($desc,$id,$version);
                    $bool = $this->dischargeHist($idAux);
                    if(is_string($bool))
                        return false;
                    $version++;
                    $this->query->Prepare("INSERT INTO HISTORIAL(DES,VERSION,COMPANY_ID) VALUES(:desc,$version,:id)");
                }
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getIdHistForAdd($desc,$idCompany,$version)
        {
            try {
                $this->query->Prepare("SELECT ID AS VERSION FROM HISTORIAL WHERE DES = :desc AND COMPANY_ID=:idCompany AND VERSION=:version");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":idCompany",$idCompany);
                $this->query->Bind(":version",$version);
                $EG = $this->query->GetRecord();
                return $EG->ID;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getHistByDesc($desc,$idCompany)
        {
            try {
                $this->query->Prepare("SELECT MAX(VERSION) AS VERSION FROM HISTORIAL WHERE DES = :desc AND COMPANY_ID=:idCompany");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":idCompany",$idCompany);
                $EG = $this->query->GetRecord();
                $id = 0;
                if(!is_null($EG->VERSION))
                    $id = (int) $EG->VERSION;
                return $id;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getHistByDescOnly($desc,$idCompany)
        {
            try {
                $this->query->Prepare("SELECT DES FROM HISTORIAL WHERE DES = :desc AND COMPANY_ID=:idCompany");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":idCompany",$idCompany);
                $EG = $this->query->GetRecord();
                return $EG;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getIdMax()
        {
            try {
                $this->query->Prepare("SELECT MAX(ID) as ID FROM HISTORIAL");
                $id = $this->query->GetRecord();
                return $id->ID;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function addRelations($array,$idHist)
        {
            try {
                $arrOE = $array->oe;
                $arrEG = $array->eg;
                foreach ($arrOE as $key => $item) {
                    $objectiveService = new ObjectivesServices;
                    $OE = $objectiveService->getObjective($item);
                    $arrOE[$key] = $OE->BG_DESC;
                }
                foreach ($arrEG as $key => $item) {
                    $this->query->Prepare("INSERT INTO REL_OE_EG(BG_DESC,EG_ID,HIS_ID) VALUES(:desc,:idEG,:idHist)");
                    $this->query->Bind(":desc",$arrOE[$key]);
                    $this->query->Bind(":idEG",$item);
                    $this->query->Bind(":idHist",$idHist);
                    if(!$this->query->Execute())
                        return false;
                }
                
                return true;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function addEG($EG,$idHist)
        {
            try {
                foreach ($EG as $item) {
                    $this->query->Prepare("INSERT INTO REL_EG(EG_ID,HIST_ID) VALUES(:idEG,:idHist)");
                    $this->query->Bind(":idEG",$item);
                    $this->query->Bind(":idHist",$idHist);
                    if(!$this->query->Execute())
                        return false;
                }
                
                return true;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function addAG($AG,$idHist)
        {
            try {
                foreach ($AG as $item) {
                    $this->query->Prepare("INSERT INTO REL_AG(AG_ID,HIST_ID) VALUES(:idAG,:idHist)");
                    $this->query->Bind(":idAG",$item);
                    $this->query->Bind(":idHist",$idHist);
                    if(!$this->query->Execute())
                        return false;
                }
                
                return true;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function addOC($OC,$idHist)
        {
            try {              
                foreach ($OC as $item) {
                    $this->query->Prepare("INSERT INTO REL_OC(OC_ID,HIST_ID) VALUES(:idOC,:idHist)");
                    $this->query->Bind(":idOC",$item);
                    $this->query->Bind(":idHist",$idHist);
                    if(!$this->query->Execute())
                        return false;
                }
                
                return true;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getHistByCompany($id)
        {
            try {
                $this->query->Prepare("SELECT H.DES,H.VERSION,H.CREATE_DATE,H.DISCHARGUE_DATE,H.COMPANY_ID,C.COMPANY_NAME FROM HISTORIAL H INNER JOIN COMPANY C ON H.COMPANY_ID = C.COMPANY_ID WHERE COMPANY_ID=:id");
                $this->query->Bind("id",$id);
                $data = $this->query->GetRecords();
                return $data;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getHistById($id)
        {
            try {
                $data = array();
                $this->query->Prepare("SELECT H.DES,H.VERSION,H.CREATE_DATE,H.DISCHARGUE_DATE,H.COMPANY_ID,C.COMPANY_NAME FROM HISTORIAL H INNER JOIN COMPANY C ON H.COMPANY_ID = C.COMPANY_ID WHERE ID=:id");
                $this->query->Bind("id",$id);
                $hist = $this->query->GetRecord();
                array_push($data,['data-hist' => $hist]);
                $relations = $this->getRelationsByHist($id);
                array_push($data,['relations' => $relations]);
                $eg = $this->getEGByHist($id);
                array_push($data,['eg' => $eg]);
                $ag = $this->getAGByHist($id);
                array_push($data,['ag' => $ag]);
                $oc = $this->getOCByHist($id);
                array_push($data,['oc' => $oc]);
                return $data;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getRelationsByHist($id)
        {
            try {
                $this->query->Prepare("SELECT R.ID,R.BG_DESC,R.EG_ID,R.HIS_ID,E.DES,E.COD,E.PERS_ID FROM REL_OE_EG R INNER JOIN EG E ON R.EG_ID = E.ID WHERE R.HIS_ID=:id");
                $this->query->Bind("id",$id);
                $data = $this->query->GetRecordS();
                return $data;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getEGByHist($id)
        {
            try {
                $this->query->Prepare("SELECT R.ID,R.HIST_ID,E.ID AS EGID,E.DES,E.PERS_ID,E.COD FROM REL_EG R INNER JOIN EG E ON R.EG_ID = E.ID WHERE R.HIST_ID=:id");
                $this->query->Bind("id",$id);
                $data = $this->query->GetRecordS();
                return $data;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getAGByHist($id)
        {
            try {
                $this->query->Prepare("SELECT R.ID,R.HIST_ID,A.ID,A.DES,A.COD FROM REL_AG R INNER JOIN AG A ON R.AG_ID = A.ID WHERE R.HIST_ID=:id");
                $this->query->Bind("id",$id);
                $data = $this->query->GetRecordS();
                return $data;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getOCByHist($id)
        {
            try {
                $this->query->Prepare("SELECT R.ID,R.GRADO,R.HIST_ID,O.ID AS OCID,O.DES,O.COD FROM REL_OC R INNER JOIN OC O ON R.OC_ID = O.ID WHERE R.HIST_ID=:id");
                $this->query->Bind("id",$id);
                $data = $this->query->GetRecordS();
                return $data;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function setGrado($id,$grado)
        {
            try {
                $this->query->Prepare("UPDATE REL_OC SET GRADO = :grado WHERE ID=:id");
                $this->query->Bind("id",$id);
                $this->query->Bind("grado",$grado);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function deleteHist($id)
        {
            try {
                $this->query->Prepare("UPDATE HISTORIAL SET STATE = 0 WHERE ID=:id");
                $this->query->Bind("id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function dischargeHist($id)
        {
            try {
                $now = date("Y-m-d H:i:s");
                $this->query->Prepare("UPDATE HISTORIAL SET DISCHARGUE_DATE = :date WHERE ID=:id");
                $this->query->Bind("id",$id);
                $this->query->Bind("date",$now);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }
    }