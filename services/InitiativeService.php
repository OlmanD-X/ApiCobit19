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
                $this->query->Prepare("SELECT ID,DES,OC_ID FROM INI WHERE OC_ID=:id");
                $this->query->Bind(":id",$id);
                $initiatives = $this->query->GetRecords();
                return $initiatives;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getInitiative($id)
        {
            try {
                $this->query->Prepare("SELECT ID,DES,OC_ID FROM INI WHERE ID=:id");
                $this->query->Bind(":id",$id);
                $initiative = $this->query->GetRecord();
                return $initiative;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getInitiativeByDesc($desc,$id)
        {
            try {
                $this->query->Prepare("SELECT ID,DES,OC_ID FROM INI WHERE DES=:desc AND OC_ID=:id");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":id",$id);
                $initiative = $this->query->GetRecord();
                return $initiative;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getInitiativeByDescEdit($desc,$id)
        {
            try {
                $this->query->Prepare("SELECT ID,DES,OC_ID FROM INI WHERE DES=:desc AND ID=:id");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":id",$id);
                $initiative = $this->query->GetRecord();
                return $initiative;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function add($desc,$id)
        {
            try {
                $this->query->Prepare("INSERT INTO INI(DES,OC_ID) VALUES(:desc,:id)");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function delete($id)
        {
            try {
                $this->query->Prepare("DELETE FROM INI WHERE ID=:id");
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function update($desc,$id)
        {
            try {
                $this->query->Prepare("UPDATE SET DES=:desc WHERE ID=:id");
                $this->query->Bind(":desc",$desc);
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }
    }