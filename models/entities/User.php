<?php declare(strict_types=1);

    namespace Models\Entities;

    use Database\Query;
    use Models\Interfaces\IUser;
    use Exception;

    abstract class User implements IUser{

        protected $id;
        protected $userName;
        protected $password;
        protected $type;
        protected $token;
        private static $query;

        public function __construct(int $id,string $userName,string $password,int $type,string $token){
            $this->id = $id;
            $this->userName = $userName;
            $this->password = $password;
            $this->type = $type;
            $this->token = $token;
        }

        public static function GetToken(int $id) : string{
            try {
                self::$query = new Query;
                self::$query->Prepare("SELECT TOKEN FROM USERS WHERE ID=:id");
                self::$query->Bind(':id',$id);
                $token = self::$query->GetRecord();
                return $token->TOKEN;
            } catch (\Throwable $th) {
                throw new Exception($th);
            }
        }

        public static function SetToken(string $token,int $id){
            try {
                self::$query = new Query;
                self::$query->Prepare("UPDATE USERS SET TOKEN=:token WHERE ID=:id");
                self::$query->Bind(':token',$token);
                self::$query->Bind(':id',$id);
                return self::$query->Execute();
            } catch (\Throwable $th) {
                throw new Exception($th);
            }
        }

        public static function Login(string $userName)
        {
            try {
                self::$query = new  Query;
                self::$query->Prepare("SELECT U.ID,U.PASS,U.USERNAME,U.TYPE_USER,U.COMPANY_ID,C.COMPANY_NAME FROM USERS U INNER JOIN COMPANY C ON U.COMPANY_ID = C.COMPANY_ID  WHERE U.USERNAME = :username AND U.STATE = '1'");
                self::$query->Bind(':username',$userName);
                return self::$query->GetRecord();
            } catch (\Throwable $th) {
                throw new Exception($th);
            }
            
        }

        public function GetId() : int
        {
            return $this->id;
        }

        public function GetUserName() : string
        {
            return $this->userName;
        }

        public function GetTokenN() : string
        {
            return $this->token;
        }

        public function GetType() : int
        {
            return $this->type;
        }

        public abstract function GetCompanyId() : int;

        public abstract function GetCompanyName() : string;
    }