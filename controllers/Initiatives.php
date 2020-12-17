<?php
    namespace Controllers;

    use Services\InitiativeService;

    class Initiatives{
        
        /**
         * Lista todas las iniciativas de un objetivo estratégico. Método http => GET.
         * 
         * URL : /Initiatives/getInitiatives/id
         * 
         * @param int $id Id del objetivo estratégico
         * 
         * @return object
         */
        public function getInitiatives($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $initiativeService = new InitiativeService;

            $data = $initiativeService->getInitiatives($id);

            if(is_string($data))
                throwError(UPDATED_RECORDS_NOT_COMPLETE,$data);

            returnResponse(GET_RECORDS_SUCCESSFULLY,'Datos obtenidos exitosamente',$data);
        }

        /**
         * Obtiene una iniciativa por su id. Método http => GET.
         * 
         * URL : /Initiatives/getInitiative/id
         * 
         * @param int $id de la iniciativa
         * 
         * @return object
         */

        public function getInitiative($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $initiativeService = new InitiativeService;

            $data = $initiativeService->getInitiative($id);

            if(is_string($data))
                throwError(GET_RECORDS_NOT_COMPLETE,$data);

            returnResponse(GET_RECORDS_SUCCESSFULLY,'Datos obtenidos exitosamente',$data);
        }

        /**
         * Registra una nueva iniciativa. Método htpp => POST.
         * 
         * URL : /Initiatives/add/desc/id
         * 
         * @param string $desc Descripción de la iniciativa
         * @param int $id Id del objetivo estratégico
         * 
         * @return object
         */

        public function add($id)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $desc = $_POST['desc']??NULL;
            $desc = validateAlfaNumeric('Descripción',$desc,'Alfanumeric');
            
            $initiativeService = new InitiativeService;

            $exists = $initiativeService->getInitiativeByDesc($desc,$id);
            if(is_string($exists))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,$exists);
            else if(is_object($exists))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,"Ya existe un registro con esta descripción");

            $data = $initiativeService->add($desc,$id);

            if(is_string($data))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,$data);

            returnResponse(RECORDS_INSERT_SUCCESSFULLY,'Iniciativa registrada exitosamente');
        }

        /**
         * Edita una iniciativa. Método http => POST.
         * 
         * URL : /Initiatives/edit/desc/id
         * 
         * @param string $desc Descripción de la iniciativa
         * @param int $id Id de la iniciativa
         * 
         * @return object
         */

        public function edit($id)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $desc = $_POST['desc']??NULL;
            $desc = validateAlfaNumeric('Descripción',$desc,'Alfanumeric');
            
            $initiativeService = new InitiativeService;

            $exists = $initiativeService->getInitiativeByDescEdit($desc,$id);
            if(is_string($exists))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,$exists);
            else if(is_object($exists))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,"Ya existe un registro con esta descripción");

            $data = $initiativeService->update($desc,$id);

            if(is_string($data))
                throwError(UPDATED_RECORDS_NOT_COMPLETE,$data);

            returnResponse(RECORDS_UPDATE_SUCCESSFULLY,'Iniciativa actualizada exitosamente');
        }

        /**
         * Elimina una iniciativa. Método http => DELETE.
         * 
         * URL : /Initiatives/delete/id
         * 
         * @param int $id Id de la iniciativa
         */

        public function delete($id)
        {
            if($_SERVER['REQUEST_METHOD']!='DELETE')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $initiativeService = new InitiativeService;

            $data = $initiativeService->delete($id);

            if(is_string($data))
                throwError(DELETED_RECORDS_NOT_COMPLETE,$data);

            returnResponse(RECORDS_DELETE_SUCCESSFULLY,'Iniciativa eliminada exitosamente');
        }
    }