<?php  
    
    namespace App;

    use Controllers\Login;

    class Kernel
    {
        private static $controller = null;
        private static $method = null;
        private static $parameters = array();

        public static function Process($url)
        {
            header('Access-Control-Allow-Origin:*');
            header('Access-Control-Allow-Headers:Authorization');
            header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE');
            header('Content-Type: application/json');
            date_default_timezone_set("America/Lima");

            // $url = self::GetUrl();
            self::GetNameController($url);
            self::SetController();
            self::GetMethod($url);
            call_user_func_array(array(self::$controller, self::$method), self::$parameters);
        }

        public static function GetUrl(){
            if(isset($_GET['url'])){
                $url = rtrim($_GET['url'],'/');
                $url = filter_var($url,FILTER_SANITIZE_URL);
                $url = explode('/',$url);
                return $url;
            }
            else {
                throwError(ROUTE_NOT_SPECIFIED,'Route not specified.');
            }
        }

        private static function GetNameController(&$url){
            if(isset($url[0])){
                if(file_exists('controllers/'.ucwords($url[0]).'.php')){
                    self::$controller = ucwords($url[0]);
                    unset($url[0]);
                }
                else{
                    throwError(CONTROLLER_NOT_EXISTS,'The method does not exist');
                }
            }
        }

        private static function SetController(){
            switch (self::$controller) {
                case 'Login':
                    self::$controller = new Login;
                    break;
                // case 'Users': 
                //     self::$controller = new Users;
                //     break;
                default:
                    # code...
                    break;
            }
        }

        private static function GetMethod(&$url){
            if(isset($url[1])){
                if(method_exists(Kernel::$controller,ucwords($url[1]))){
                    self::$method = ucwords($url[1]);
                    unset($url[1]);
                }
                else{
                    throwError(METHOD_NOT_EXISTS,'The method does not exist');
                }
            }
            else{
                throwError(METHOD_NOT_EXISTS,'The method does not exist');
            }

            self::$parameters = $url? array_values($url) : array();
        }
    }