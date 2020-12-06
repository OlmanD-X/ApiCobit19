<?php
    namespace Controllers;

    use Services\UserService;

    class Users{
        
        /**
         * Lista todos los usuarios. Método http => GET.
         * 
         * URL : /Users/getUsers
         * 
         * @return object
         */
        public function getUsers()
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $userService = new UserService;
            $users = $userService->getUsers();

            if(is_string($users))
                throwError(GET_RECORDS_NOT_COMPLETE,'Users could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'Users successfully obtained ',$users);
        }

        /**
         * Lista los usuarios de una empresa. Método http => GET.
         * 
         * URL : /Users/getUsersByCompany/id
         * 
         * @param int $id Id de la empresa
         * 
         * @return object
         */
        public function getUsersByCompany($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $userService = new UserService;
            $users = $userService->getUsersByCompany($id);

            if(is_string($users))
                throwError(GET_RECORDS_NOT_COMPLETE,'Users could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'Users successfully obtained ',$users);
        }

        /**
         * Obtiene un usuario. Método http => GET.
         * 
         * URL : /Users/getUser/id
         * 
         * @param int $id Id del usuario
         * 
         * @return object
         */
        public function getUser($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $userService = new UserService;
            $user = $userService->getUser($id);

            if(is_string($user))
                throwError(GET_RECORDS_NOT_COMPLETE,'User could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'User successfully obtained ',$user);
        }

        /**
         * Registra un usuario. Método http => POST
         * 
         * URL : /Users/add
         * 
         * @param string $username Nombre de usuario
         * @param string $pass Contraseña del usuario
         * @param int $type Id del tipo de usuario
         * @param int $idCompany Id de la empresa
         * 
         * @return object
         * 
         */
        public function add($username,$pass,$type,$idCompany)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $username = $_POST['username'] ?? NULL;
            $pass = $_POST['pass'] ?? NULL;
            $type = $_POST['type'] ?? NULL;
            $idCompany = $_POST['idCompany'] ?? NULL;

            $pass = password_hash($pass,PASSWORD_DEFAULT);

            $userService = new UserService;

            $isAdd = $userService->add($username,$pass,$type,$idCompany);
            if(is_string($isAdd))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'User could not be registered.'. $isAdd);

            returnResponse(RECORDS_INSERT_SUCCESSFULLY,'User successfully obtained ');
        }

        /**
         * Elimina un usuario. Método htpp => DELETE
         * 
         * URL : /Users/delete/id
         * 
         * @param int $id Id del usuario
         * 
         * @return object
         */
        public function delete($id)
        {
            if($_SERVER['REQUEST_METHOD']!='DELETE')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $userService = new UserService;
            $isDelete = $userService->delete($id);
            if(is_string($isDelete))
                throwError(DELETED_RECORDS_NOT_COMPLETE,'User could not be deleted.'. $isDelete);

            returnResponse(RECORDS_DELETE_SUCCESSFULLY,'User successfully deleted');
        }

        /**
         * Actualiza un usuario. Método http => POST
         * 
         * URL : /Users/add
         * 
         * @param string $username Nombre de usuario
         * @param string $pass Contraseña del usuario
         * @param int $type Id del tipo de usuario
         * @param int $id Id del usuario
         * 
         * @return object
         * 
         */
        public function update($username,$pass,$type,$id)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $username = $_POST['username'] ?? NULL;
            $pass = $_POST['pass'] ?? NULL;
            $type = $_POST['type'] ?? NULL;
            $id = $_POST['id'] ?? NULL;

            $pass = password_hash($pass,PASSWORD_DEFAULT);

            $userService = new UserService;
            $isAdd = $userService->edit($username,$pass,$type,$id);

            if(is_string($isAdd))
                throwError(UPDATED_RECORDS_NOT_COMPLETE,'User could not be updated.'. $isAdd);

            returnResponse(RECORDS_UPDATE_SUCCESSFULLY,'User successfully update');
        }

        /**
         * Asigna los permisos al usuario. Método http => POST
         * 
         * URL : /Users/setPermissions/id
         * 
         * @param int $id Id del usuario
         * @param object $actions Acciones permitidas al usuario
         * 
         * @return object
         */
        public function setPermissions($id,$actions)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $arrayActions = json_decode($_POST['actions']);

            $userService = new UserService;

            $isAdd = $userService->setPermissions($id, $arrayActions);
            if(is_string($isAdd))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'Permissions could not be inserted.'. $isAdd);

            returnResponse(RECORDS_INSERT_SUCCESSFULLY,'Permissions successfully inserted');
        }
    }