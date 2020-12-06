<?php
    namespace Controllers;

    use Services\CompanyService;

    class Companies{

        /**
         * Obtiene todos los registros de las empresas. Método http => GET
         * 
         * @return object
         */
        public function getCompanies()
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $companyService = new CompanyService;
            $companies = $companyService->getCompanies();

            if(is_string($companies))
                throwError(GET_RECORDS_NOT_COMPLETE,'Companies could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'Companies successfully obtained ',$companies);
        }

        /**
         * Busca una empresa por su id. Método http => GET
         * 
         * @param int $id Id de la empresa
         * @return object
         */
        public function getCompany($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            if(!is_numeric($id))
                throwError(INVALID_PARAM,'Invalid param Id.');

            $companyService = new CompanyService;
            $company = $companyService->getCompany((int)$id);

            if(is_string($company))
                throwError(GET_RECORDS_NOT_COMPLETE,'Company could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'Company successfully obtained ',$company);
        }

        /**
         * Registra una empresa. Método http => POST
         * 
         * @param string $company Razon Social de la empresa
         * @param int $ruc Ruc de la empresa
         * @param string $email Email de la empresa
         * @param int phone phone de la empresa
         * @return object
         */
        public function add()
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $name = $_POST['company'] ?? null;
            $ruc = $_POST['ruc'] ?? null;
            $email = $_POST['email'] ?? null;
            $phone = $_POST['phone'] ?? null;

            $name = validateAlfaNumeric('Empresa',$name,'Alfanumeric');
            $ruc = validateRuc($ruc);
            $email = validateEmail($email);
            $phone = validatePhone($phone);

            $companyService = new CompanyService;

            $exists = $companyService->getCompanyByName($name);
            if($exists)
                returnResponse(GET_RECORDS_NOT_COMPLETE,'Company already exists.');

            $exists = $companyService->getCompanyByRuc($ruc);
            if($exists)
                returnResponse(GET_RECORDS_NOT_COMPLETE,'Ruc already exists.');

            $exists = $companyService->getCompanyByPhone($phone);
            if($exists)
                returnResponse(GET_RECORDS_NOT_COMPLETE,'Phone already exists.');

            $ok = $companyService->add($name,$ruc,$email,$phone);

            if(is_string($ok))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,$ok);
            else if($ok)
                returnResponse(RECORDS_INSERT_SUCCESSFULLY,'Successfully registered company');
            else
                returnResponse(INSERTED_RECORDS_NOT_COMPLETE,'Company could not be registered');
        }

        /**
         * Elimina una empresa. Método http => DELETE
         * 
         * @param int $id Id de la empresa
         * @return object
         */
        public function delete($id)
        {
            if($_SERVER['REQUEST_METHOD']!='DELETE')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            if(!is_numeric($id))
                throwError(INVALID_PARAM,'Invalid param Id.');

            $companyService = new CompanyService;
            $ok = $companyService->delete((int)$id);

            if(is_string($ok))
                throwError(DELETED_RECORDS_NOT_COMPLETE,'Company could not be deleted.');

            returnResponse(RECORDS_DELETE_SUCCESSFULLY,'Company successfully deleted.');
        }

        /**
         * Actualiza una empresa. Método http => POST
         * 
         * @param string $company
         * @param int $ruc Ruc de la empresa.
         * @param string $email Email de la empresa.
         * @param int $phone phone de la empresa.
         * @param int $id Id de la empresa.
         * @return object
         */
        public function update()
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $name = $_POST['company'] ?? null;
            $ruc = $_POST['ruc'] ?? null;
            $email = $_POST['email'] ?? null;
            $phone = $_POST['phone'] ?? null;
            $id = $_POST['id'] ?? null;

            $name = validateAlfaNumeric('Empresa',$name,'Alfanumeric');
            $ruc = validateRuc($ruc);
            $email = validateEmail($email);
            $phone = validatePhone($phone);

            $companyService = new CompanyService;

            $exists = $companyService->getCompanyByName($name,$id);
            if($exists)
                returnResponse(GET_RECORDS_NOT_COMPLETE,'Company already exists.');

            $exists = $companyService->getCompanyByRuc($ruc,$id);
            if($exists)
                returnResponse(GET_RECORDS_NOT_COMPLETE,'Ruc already exists.');

            $exists = $companyService->getCompanyByPhone($phone,$id);
            if($exists)
                returnResponse(GET_RECORDS_NOT_COMPLETE,'Phone already exists.');

             $ok = $companyService->update($name,$ruc,$email,$phone,$id);

            if(is_string($ok))
                throwError(UPDATED_RECORDS_NOT_COMPLETE,$ok);
            else if($ok)
                returnResponse(RECORDS_UPDATE_SUCCESSFULLY,'Successfully updated company');
            else
                returnResponse(UPDATED_RECORDS_NOT_COMPLETE,'Company could not be updated');
        }
    }