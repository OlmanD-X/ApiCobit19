<?php
    namespace Database;

    use \PDO;

    class Query{

        private $dbh = null;
        private $stmt = null;

        public function __construct()
        {
            $this->dbh = DB::GetConnection();
            if(!$this->dbh instanceof PDO)
                throwError(CONNECTION_DATABASE_ERROR,$this->dbh);
        }

        public function Prepare($sql){
            $this->stmt = $this->dbh->prepare($sql);
        }

        public function Bind($parameter,$value,$type = null){
            if(is_null($type)){
                switch (true) {
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                        break;
                }
            }
            $this->stmt->bindValue($parameter,$value,$type);
        }

        public function Execute(){
            return $this->stmt->execute();
        }

        public function GetRecords(){
            $this->Execute();
            $dataSet = $this->stmt->fetchAll(PDO::FETCH_OBJ);
            return $dataSet;
        }

        public function GetRecord(){
            $this->execute();
            $dataSet = $this->stmt->fetch(PDO::FETCH_OBJ);
            return $dataSet;
        }

        public function RowsCount(){
            return $this->stmt->rowCount();
        }

        public function LastInsertId(){
            return $this->dbh->LastInsertId();
        }

    }