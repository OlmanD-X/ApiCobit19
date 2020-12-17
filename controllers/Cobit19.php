<?php
    namespace Controllers;

    use Services\Cobit19Service;

    class Cobit19{
        /**
         * Obtiene las EG del marco COBIT 19. Método http => GET.
         * 
         * URL : /Cobit19/getEG
         * 
         * @return object
         */
        public function getEG()
        {
            if($_SERVER['REQUEST_METHOD']!=='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $cobit19Service = new Cobit19Service;
            $EG = $cobit19Service->getEG();
            
            if(is_string($EG))
                throwError(GET_RECORDS_NOT_COMPLETE,'EG could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'EG successfully obtained ',$EG);
        }

        /**
         * Obtiene las AG del marco COBIT 19. Método http => GET.
         * 
         * URL : /Cobit19/getAG
         * 
         * @return object
         */
        public function getAG()
        {
            if($_SERVER['REQUEST_METHOD']!=='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $cobit19Service = new Cobit19Service;
            $AG = $cobit19Service->getAG();

            if(is_string($AG))
                throwError(GET_RECORDS_NOT_COMPLETE,'AG could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'AG successfully obtained ',$AG);
        }

        /**
         * Obtiene los OC del marco COBIT 19. Método http => GET.
         * 
         * URL : /Cobit19/getOC
         * 
         * @return object
         */

        public function getOC()
        {
            if($_SERVER['REQUEST_METHOD']!=='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $cobit19Service = new Cobit19Service;
            $OC = $cobit19Service->getOC();

            if(is_string($OC))
                throwError(GET_RECORDS_NOT_COMPLETE,'OC could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'OC successfully obtained ',$OC);
        }

        /**
         * Registra un nuevo registro en el historial. Método http => POST.
         * 
         * URL : /Cobit19/addHist
         * 
         * @param string $desc Descripción del historial
         * @param int $id Id de la empresa
         * @param string $relations Relaciones entre los OE y las EG. Formato {oe:[],eg:[]}
         * 
         * @return object
         */

        public function addHist()
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $desc = $_POST['desc'] ?? null;
            $id = $_POST['id'] ?? null;
            $relations = $_POST['relaciones']??null;
            
            if(!is_numeric($id))
                throwError(INVALID_PARAM,'Invalid param Id.');

            $desc = validateAlfaNumeric('Descripción',$desc,'Alfanumeric');
            $arrayRelations = json_decode($relations);

            $arrayOE = $arrayRelations->oe??null;
            $arrayEG = $arrayRelations->eg;
            $arrayEG = array_unique($arrayEG);
            sort($arrayEG);

            $cobit19Service = new Cobit19Service;

            $this->addRecord($id,$desc);
            $idHist = $cobit19Service->getIdMax();

            if($arrayOE!=null)
                $this->addRelations($arrayRelations,$idHist);  

            $this->addEG($arrayEG,$idHist);

            $AG = $cobit19Service->mapAG($arrayEG);

            $this->addAG($AG,$idHist);

            $OC = $cobit19Service->mapOC($AG);

            $this->addOC($OC,$idHist);

            $response = $cobit19Service->getHistById($idHist);
            if(is_string($response))
                throwError(GET_RECORDS_NOT_COMPLETE,'An error ocurred.'.$response);
            
            returnResponse(SUCCESS_RESPONSE,'Data inserted successfully',$response);
        }

        /**
         * Obtiene todo el histórico de una empresa. Método http => GET.
         * 
         * URL : /Cobit19/getHistByCompany/id
         * 
         * @param int $id Id de la empresa
         * @return object
         */

        public function getHistByCompany($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $cobit19Service = new Cobit19Service;
            $response = $cobit19Service->getHistByCompany($id);
            if(is_string($response))
                throwError(GET_RECORDS_NOT_COMPLETE,'An error ocurred.'.$response);

            returnResponse(SUCCESS_RESPONSE,'Data inserted successfully',$response);
        }

        /**
         * Obtiene todo el mapeo cobit de un histórico. Método htpp => GET.
         * 
         * URL : /Cobit19/getHist/id
         * 
         * @param int $id Id del histórico
         * @return object
         */
        public function getHist($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $cobit19Service = new Cobit19Service;
            $response = $cobit19Service->getHistById($id);
            if(is_string($response))
                throwError(GET_RECORDS_NOT_COMPLETE,'An error ocurred.'.$response);
            
            returnResponse(SUCCESS_RESPONSE,'Data inserted successfully',$response);
        }

        /**
         * Registra un nuevo registro en el historial. Método http => POST.
         * 
         * @param int $id Id de la empresa
         * @param string $desc Descripcion del historial
         * 
         * @return mixed
         */

        public function addRecord($id,$desc)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $cobit19Service = new Cobit19Service;

            $VERSION = $cobit19Service->getHistByDesc($desc,$id);
            if(is_string($VERSION))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$VERSION);

            if($VERSION==0){
                $exists = $cobit19Service->getHistByDescOnly($desc,$id);
                if(is_string($exists))
                    throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$exists);
                else if($exists)
                    throwError(INSERTED_RECORDS_NOT_COMPLETE,'La descripción ya ha sido registrada.');
            }   
            $isAdd = $cobit19Service->addHist($id,$desc);
            if(is_string($isAdd))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$isAdd);
            else if(!$isAdd)
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.');
        }

        /**
         * Registra el mapeo de los objetivos estratégicos con las EG. Método http => POST.
         * 
         * @param array $arrayRelations Array del mapeo OE vs EG
         * @param int $idHist Id del historial
         * 
         * @return mixed
         */

        public function addRelations($arrayRelations,$idHist)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $cobit19Service = new Cobit19Service;
            $isAdd = $cobit19Service->addRelations($arrayRelations,$idHist);
            if(is_string($isAdd))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$isAdd);
            else if(!$isAdd)
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.');

        }

        /**
         * Registra las EG resultantes. Método http => POST
         * @param array $arrayEG Array con las EG resultantes
         * @param int $idHist Id del historial
         * 
         * @return mixed
         */

        public function addEG($arrayEG,$idHist)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $cobit19Service = new Cobit19Service;
            $isAdd = $cobit19Service->addEG($arrayEG,$idHist);
            if(is_string($isAdd))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$isAdd);
            else if(!$isAdd)
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.');
        }

        /**
         * Registra las AG resultantes. Método http => POST
         * @param array $AG Array con las AG resultantes
         * @param int $idHist Id del historial
         * 
         * @return mixed
         */

        public function addAG($AG,$idHist)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $cobit19Service = new Cobit19Service;
            $isAdd = $cobit19Service->addAG($AG,$idHist);
            if(is_string($isAdd))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$isAdd);
            else if(!$isAdd)
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.');
            
            // returnResponse(SUCCESS_RESPONSE,'Data inserted successfully',$response);
        }

        /**
         * Registra los OC resultantes. Método http => POST
         * @param array $OC Array con los OC resultantes
         * @param int $idHist Id del historial
         * 
         * @return mixed
         */

        public function addOC($OC,$idHist)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $cobit19Service = new Cobit19Service;
            $isAdd = $cobit19Service->addOC($OC,$idHist);
            if(is_string($isAdd))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$isAdd);
            else if(!$isAdd)
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.');

            // returnResponse(SUCCESS_RESPONSE,'Data inserted successfully',$response);
        }

        /**
         * Asigna el grado de un OC. Método http => POST.
         * 
         * URL : /Cobit19/setGrado/id/desc
         * 
         * @param int $id Id del OC de un histórico
         * @param mixed $grado Descripción del grado
         * 
         * @return mixed
         */
        public function setGrado($id,$grado)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $cobit19Service = new Cobit19Service;
            $isAdd = $cobit19Service->setGrado($id,$grado);
            if(is_string($isAdd))
                throwError(UPDATED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$isAdd);
            else if(!$isAdd)
                throwError(UPDATED_RECORDS_NOT_COMPLETE,'An error ocurred.');

            returnResponse(RECORDS_UPDATE_SUCCESSFULLY,'Data updated successfully');
        }

        /**
         * Registra el mapeo de los objetivos estratégicos con las EG. Método http => POST.
         * 
         * URL : /Cobit19/addRelationsByHist/
         * 
         * @param object $relations Array del mapeo OE vs EG
         * @param int $idHist Id del historial
         * 
         * @return mixed
         */
        public function addRelationsByHist()
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $arrayRelations = json_decode($_POST['relations']);
            $idHist = $_POST['idHist'] ?? NULL;

            $cobit19Service = new Cobit19Service;
            $isAdd = $cobit19Service->addRelations($arrayRelations,$idHist);
            if(is_string($isAdd))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$isAdd);
            else if(!$isAdd)
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.');

            $EG = array_unique($arrayRelations->eg);
            sort($EG);
            $isAdd = $cobit19Service->addEG($EG,$idHist);
            if(is_string($isAdd))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$isAdd);

            returnResponse(SUCCESS_RESPONSE,'Data inserted successfully');
        }

        /**
         * Registra las AG resultantes. Método http => POST.
         * 
         * URL : /Cobit19/addAGByHist/idHist
         * 
         * @param int $idHist Id del historial
         * 
         * @return mixed
         */
        public function addAGByHist($idHist)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $cobit19Service = new Cobit19Service;

            $arrayEG = $cobit19Service->getEGByHist($idHist);
            $EG = array();

            foreach ($arrayEG as $key => $item) {
                array_push($EG,$item->EGID);
            }

            $arrayAG = $cobit19Service->mapAG($EG);

            $isAdd = $cobit19Service->addAG($arrayAG,$idHist);
            if(is_string($isAdd))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$isAdd);
            else if(!$isAdd)
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.');

            returnResponse(SUCCESS_RESPONSE,'Data inserted successfully');
        }

        /**
         * Registra los OC resultantes. Método http => POST.
         * 
         * URL : /Cobit19/addOCByHist/idHist
         * 
         * @param int $idHist Id del historial
         * 
         * @return mixed
         * 
         */
        public function addOCByHist($idHist)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $cobit19Service = new Cobit19Service;
            $arrayOC = $cobit19Service->getOCByHist($idHist);
            $OC = array();

            foreach ($arrayOC as $key => $item) {
                array_push($OC,$item->OCID);
            }
            $isAdd = $cobit19Service->addOC($OC,$idHist);
            if(is_string($isAdd))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$isAdd);
            else if(!$isAdd)
                throwError(INSERTED_RECORDS_NOT_COMPLETE,'An error ocurred.');

            returnResponse(SUCCESS_RESPONSE,'Data inserted successfully');
        }

        /**
         * Obtiene las EG de un histórico. Método http => GET.
         * 
         * URL : /Cobit19/getEGByHist/id
         * 
         * @param int 
         * 
         * @return mixed
         * 
         */

        public function getEGByHist($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $cobit19Service = new Cobit19Service;
            $EG = $cobit19Service->getEGByHist($id);
            
            if(is_string($EG))
                throwError(UPDATED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$EG);

            returnResponse(RECORDS_UPDATE_SUCCESSFULLY,'Data updated successfully',$EG);

        }

        /**
         * Obtiene las AG de un histórico. Método http => GET.
         * 
         * URL : /Cobit19/getAGByHist/id
         * 
         * @param int 
         * 
         * @return mixed
         * 
         */
        public function getAGByHist($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $cobit19Service = new Cobit19Service;
            $AG = $cobit19Service->getAGByHist($id);
            
            if(is_string($AG))
                throwError(UPDATED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$AG);

            returnResponse(RECORDS_UPDATE_SUCCESSFULLY,'Data updated successfully',$AG);

        }

        /**
         * Obtiene los OC de un histórico. Método http => GET.
         * 
         * URL : /Cobit19/getOCByHist/id
         * 
         * @param int 
         * 
         * @return mixed
         * 
         */
        public function getOCByHist($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $cobit19Service = new Cobit19Service;
            $OC = $cobit19Service->getOCByHist($id);
            
            if(is_string($OC))
                throwError(UPDATED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$OC);

            returnResponse(RECORDS_UPDATE_SUCCESSFULLY,'Data updated successfully',$OC);

        }

        /**
         * Elimina un histórico. Método http => DELETE
         * 
         * URL : /Cobit19/deleteHist/id
         * 
         * @param int $id Id del histórico
         * 
         * @return object
         */
        public function deleteHist($id)
        {
            if($_SERVER['REQUEST_METHOD']!='DELETE')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $cobit19Service = new Cobit19Service;
            $isDelete = $cobit19Service->deleteHist($id);

            if(is_string($isDelete))
                throwError(DELETED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$isDelete);

            returnResponse(RECORDS_DELETE_SUCCESSFULLY,'Historial eliminado correctamente');
        }

        /**
         * Da de baja un histórico. Método http => POST
         * 
         * URL : /Cobit19/dischargeHist/id
         * 
         * @param int $id Id del histórico
         * 
         * @return object
         */
        public function dischargeHist($id)
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $cobit19Service = new Cobit19Service;
            $isDelete = $cobit19Service->dischargeHist($id);

            if(is_string($isDelete))
                throwError(DELETED_RECORDS_NOT_COMPLETE,'An error ocurred.'.$isDelete);
            else if(!$isDelete)
                throwError(DELETED_RECORDS_NOT_COMPLETE,'El registro ya tiene una fecha de baja.'.$isDelete);
            returnResponse(RECORDS_DELETE_SUCCESSFULLY,'Historial dado de baja correctamente');
        }
    }