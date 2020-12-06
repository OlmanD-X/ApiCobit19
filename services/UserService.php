<?php declare(strict_types=1);
    namespace Services;

    use Database\Query;
    use Models\Entities\User;
    use Libraries\JWT;

    class UserService{

        private $query=null;

        public function __construct()
        {
            $this->query = new Query;
        }

        public function Login(string $user,string $pass)
        {
            try {
                $user = User::Login($user);
                if(!is_object($user))
                    return USER_NOT_FOUND;

                $validPassword = password_verify($pass,$user->PASS) ? true : false;
                if(!$validPassword)
                    return INVALID_USER_PASS;
                
                $user->token = $this->GenerateToken($user);
                $oUser = FactoryUsers::createUser($user);

                if(is_null($oUser))
                    return CREATE_INSTANCE_ERROR;

                if(!User::SetToken($oUser->GetTokenN(),$oUser->GetId()))
                    return ACCESS_TOKEN_ERRORS;

                return $oUser;
                
            } catch (\Throwable $th) {
                return $th->getMessage();
            }
        }

        private function GenerateToken(object $user):string{
            $payload = array(
                'iat'=>time(),
                'iss' => 'localhost',
                'exp' => time() + (24*60*60),
                'userId' => $user->ID,
                'name' => $user->USERNAME,
                'type' => $user->TYPE_USER,
                'companyId' => $user->COMPANY_ID,
                'company' => $user->COMPANY_NAME,
            );

            return JWT::encode($payload,SECRETE_KEY);
        }

        public function getUsers()
        {
            try {
                $this->query->Prepare("SELECT U.ID,U.USERNAME,U.PASS,U.CREATE_DATE,U.TYPE_USER,U.COMPANY_ID,U.STATE,C.COMPANY_NAME,T.TYPE_DES FROM USERS U INNER JOIN COMPANY C ON U.COMPANY_ID = C.COMPANY_ID INNER JOIN TYPE_USER T ON U.TYPE_USER=T.TYPE_ID");
                $users = $this->query->GetRecords();
                return $users;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function getUsersByCompany($id)
        {
            try {
                $this->query->Prepare("SELECT U.ID,U.USERNAME,U.PASS,U.CREATE_DATE,U.TYPE_USER,U.COMPANY_ID,U.STATE,C.COMPANY_NAME,T.TYPE_DES FROM USERS U INNER JOIN COMPANY C ON U.COMPANY_ID = C.COMPANY_ID INNER JOIN TYPE_USER T ON U.TYPE_USER=T.TYPE_ID WHERE U.COMPANY_ID=:id");
                $this->query->Bind(":id",$id);
                $users = $this->query->GetRecords();
                return $users;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function getUser($id)
        {
            try {
                $this->query->Prepare("SELECT U.ID,U.USERNAME,U.PASS,U.CREATE_DATE,U.TYPE_USER,U.COMPANY_ID,U.STATE,C.COMPANY_NAME,T.TYPE_DES FROM USERS U INNER JOIN COMPANY C ON U.COMPANY_ID = C.COMPANY_ID INNER JOIN TYPE_USER T ON U.TYPE_USER=T.TYPE_ID WHERE U.ID=:id");
                $this->query->Bind(":id",$id);
                $user = $this->query->GetRecord();
                return $user;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }          
        }

        public function delete($id)
        {
            try {
                $this->query->Prepare("UPDATE USERS SET STATE = 0 WHERE ID=:id");
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            }  
        }

        public function add($username,$pass,$type,$idCompany)
        {
            try {
                $this->query->Prepare("INSERT INTO USERS(USERNAME,PASS,TYPE_USER,COMPANY_ID) VALUES(:username,:pass,:type,:idCompany)");
                $this->query->Bind(":username",$username);
                $this->query->Bind(":pass",$pass);
                $this->query->Bind(":type",$type);
                $this->query->Bind(":idCompany",$idCompany);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            }  
        }

        public function edit($username,$pass,$type,$id)
        {
            try {
                $this->query->Prepare("UPDATE USERS SET USERNAME=:username,PASS=:pass,TYPE_USER:type WHERE ID=:id");
                $this->query->Bind(":username",$username);
                $this->query->Bind(":pass",$pass);
                $this->query->Bind(":type",$type);
                $this->query->Bind(":id",$id);
                return $this->query->Execute();
            } catch (\Throwable $th) {
                return $th->getMessage();
            }  
        }    
        
        public function setPermissions($id,$arrayActions)
        {
            try {
                $this->query->Prepare("DELETE FROM PERMISSIONS WHERE USER_ID=:id");
                $this->query->Bind(":id",$id);
                $this->query->Execute();

                foreach ($arrayActions as $key => $value) {
                    $this->query->Prepare("INSERT INTO PERMISSIONS(ACTION_ID,USER_ID) VALUES(:action,:id)");
                    $this->query->Bind(":action",$value);
                    $this->query->Bind(":id",$id);
                    $this->query->Execute();
                }
                
                return true;
            } catch (\Throwable $th) {
                return $th->getMessage();
            } 
        }

        public function getTransactions()
        {
            try {
                $this->query->Prepare("SELECT * FROM TRANSACCION");
                $trans = $this->query->GetRecords();
                return $trans;
            } catch (\Throwable $th) {
                return $th->getMessage();
            }  
        }
    }