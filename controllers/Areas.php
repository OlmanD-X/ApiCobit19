<?php
    namespace Controllers;

    use Services\AreaService;

    class Areas{

        public function getAreas($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $areaService = new AreaService;
            $areas = $areaService->getAreas($id);

            if(is_string($areas))
                throwError(GET_RECORDS_NOT_COMPLETE,'Areas could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'Areas successfully obtained ',$areas);
        }

        public function getArea($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            if(!is_numeric($id))
                throwError(INVALID_PARAM,'Invalid param Id.');

            $areaService = new AreaService;
            $area = $areaService->getArea((int)$id);

            if(is_string($area))
                throwError(GET_RECORDS_NOT_COMPLETE,'Area could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'Area successfully obtained ',$area);
        }

        public function add()
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $des = $_POST['des'] ?? null;

            $name = validateAlfaNumeric('Area',$des,'Alfanumeric');
            $idCompany = $_POST['idCompany'] ?? NULL;

            $areaService = new AreaService;

            $exists = $areaService->getAreaByDes($name,$idCompany);
            if($exists)
                returnResponse(GET_RECORDS_NOT_COMPLETE,'Area already exists.');


            $ok = $areaService->add($des,$idCompany);

            if(is_string($ok))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,$ok);
            else if($ok)
                returnResponse(RECORDS_INSERT_SUCCESSFULLY,'Successfully registered area');
            else
                returnResponse(INSERTED_RECORDS_NOT_COMPLETE,'Area could not be registered');
        }

        public function delete($id)
        {
            if($_SERVER['REQUEST_METHOD']!='DELETE')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            if(!is_numeric($id))
                throwError(INVALID_PARAM,'Invalid param Id.');

            $areaService = new AreaService;
            $ok = $areaService->delete((int)$id);

            if(is_string($ok))
                throwError(DELETED_RECORDS_NOT_COMPLETE,'Area could not be deleted.');

            returnResponse(RECORDS_DELETE_SUCCESSFULLY,'Area successfully deleted.');
        }

        public function update()
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $des = $_POST['des'] ?? null;
            $id = $_POST['id'] ?? null;
            $idCompany = $_POST['idCompany'] ?? null;

            $name = validateAlfaNumeric('Area',$des,'Alfanumeric');

            $areaService = new AreaService;

            $exists = $areaService->getAreaByDes2($name,$id,$idCompany);
            if($exists)
                returnResponse(GET_RECORDS_NOT_COMPLETE,'Area already exists.');

             $ok = $areaService->update($des,$id);

            if(is_string($ok))
                throwError(UPDATED_RECORDS_NOT_COMPLETE,$ok);
            else if($ok)
                returnResponse(RECORDS_UPDATE_SUCCESSFULLY,'Successfully updated area');
            else
                returnResponse(UPDATED_RECORDS_NOT_COMPLETE,'Area could not be updated');
        }
    }