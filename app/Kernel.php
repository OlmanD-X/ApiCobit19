<?php  
    
    namespace App;

    use Controllers\Areas;
    use Controllers\Cobit19;
    use Controllers\Companies;
    use Controllers\Initiatives;
    use Controllers\Login;
    use Controllers\Objectives;
    use Controllers\Users;

class Kernel
    {
        private static $controller = null;
        private static $method = null;
        private static $parameters = array();

        public static function Process($url)
        {

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
                case 'Companies':
                    self::$controller = new Companies;
                    break;
                case 'Objectives':
                    self::$controller = new Objectives;
                    break;
                case 'Cobit19':
                    self::$controller = new Cobit19;
                    break;
                case 'Initiatives': 
                    self::$controller = new Initiatives;
                break;
                case 'Users': 
                    self::$controller = new Users;
                break;
                case 'Areas': 
                    self::$controller = new Areas;
                break;
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