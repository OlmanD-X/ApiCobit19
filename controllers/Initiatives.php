<?php
    namespace Controllers;

    use Services\InitiativeService;

    class Initiatives{
        
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

        public function add($id)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $desc = $_POST['desc']??NULL;
            $fecha = $_POST['fecha']??NULL;
            $relations = $_POST['relations']??NULL;
            $relations = json_decode($relations);
            $desc = validateAlfaNumeric('Descripción',$desc,'Alfanumeric');
            
            $initiativeService = new InitiativeService;

            $exists = $initiativeService->getInitiativeByDesc($desc,$id);
            if(is_string($exists))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,$exists);
            else if(is_object($exists))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,"Ya existe un registro con esta descripción");

            $data = $initiativeService->add($desc,$id,$fecha,$relations);

            if(is_string($data))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,$data);

            returnResponse(RECORDS_INSERT_SUCCESSFULLY,'Iniciativa registrada exitosamente');
        }

        public function edit($id)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $desc = $_POST['desc']??NULL;
            $idCompany = $_POST['idCompany']??NULL;
            $relations = $_POST['relations']??NULL;
            $relations = json_decode($relations);
            $fecha = $_POST['fecha']??NULL;
            $desc = validateAlfaNumeric('Descripción',$desc,'Alfanumeric');
            
            $initiativeService = new InitiativeService;

            $exists = $initiativeService->getInitiativeByDescEdit($desc,$id,$idCompany);
            if(is_string($exists))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,$exists);
            else if(is_object($exists))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,"Ya existe un registro con esta descripción");

            $data = $initiativeService->update($desc,$id,$fecha,$relations);

            if(is_string($data))
                throwError(UPDATED_RECORDS_NOT_COMPLETE,$data);

            returnResponse(RECORDS_UPDATE_SUCCESSFULLY,'Iniciativa actualizada exitosamente');
        }

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