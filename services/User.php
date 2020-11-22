<?php
    namespace Services;

    use Database\Query;

    class User{

        private $query=null;

        public function __construct()
        {
            $this->query = new Query;
        }

        public function Login()
        {
            
            $this->query->Prepare("SELECT * FROM TYPE_USER");
            $data = $this->query->GetRecords();
            return $data;
        }

        public function GetToken($id){
            try {
                $this->query->Prepare("SELECT TOKEN FROM USERS WHERE ID=:id");
                $this->query->Bind(':id',$id);
                return $this->query->GetRecord();
            } catch (\Throwable $th) {
                return $th->getMessage();
            }
        }
    }