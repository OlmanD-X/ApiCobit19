<?php
    namespace Database;

    use \PDO;
    use \PDOException;

    class DB{
        private static $host = DB_HOST;
        private static $user = DB_USER;
        private static $password = DB_PASSWORD;
        private static $db_name = DB_NAME;

        private static $instance = null;

        private static $dbh = null;

        private function __construct() { }

        private static function GetInstance()
        {
            if(is_null(self::$instance))
                self::$instance = new self();
        }

        public static function GetConnection()
        {
            self::GetInstance();
            if (is_null(self::$dbh)) {
                $dsn = 'mysql:host='.self::$host.';dbname='.self::$db_name;
                $options = array(
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                );
                
                try {
                    self::$dbh = new PDO($dsn,self::$user,self::$password,$options);
                    self::$dbh->exec('set names utf8');
                } catch (PDOException $e) {
                    return $e->getMessage();
                }
            }

            return self::$dbh;
        }
    }