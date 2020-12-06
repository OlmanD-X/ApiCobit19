<?php
    namespace Controllers;

    use Services\ObjectivesServices;

    class Objectives{

        /**
         * Obtiene todos los objetivos de la base de datos. Método http => GET.
         * 
         * URL : /Objectives/getObjectives
         * 
         * @return object
         */
        public function getObjectives()
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            $objectiveService = new ObjectivesServices;
            $objectives = $objectiveService->getObjectives();

            if(is_string($objectives))
                throwError(GET_RECORDS_NOT_COMPLETE,'Objectives could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'Objectives successfully obtained ',$objectives);
        }

        /**
         * Obtiene todos los objetivos de una empresa. Método http => GET.
         * 
         * URL : /Objectives/getObjectivesByCompany/id
         * 
         * @param int $id Id de la empresa
         * @return object
         */
        public function getObjectivesByCompany($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');
            
            if(!is_numeric($id))
                throwError(INVALID_PARAM,'Invalid param Id.');
            
            $objectiveService = new ObjectivesServices;
            $objectives = $objectiveService->getObjectivesByCompany((int) $id);

            if(is_string($objectives))
                throwError(GET_RECORDS_NOT_COMPLETE,'Objectives could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'Objectives successfully obtained ',$objectives);
        }

        /**
         * Obtiene un objetivo estratégico. Método http => GET.
         * 
         * URL : /Objectives/getObjective/id
         * 
         * @param int $id Id del objetivo estratégico
         * @return object
         */
        public function getObjective($id)
        {
            if($_SERVER['REQUEST_METHOD']!='GET')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            if(!is_numeric($id))
                throwError(INVALID_PARAM,'Invalid param Id.');

            $objectiveService = new ObjectivesServices;
            $objective = $objectiveService->getObjective((int)$id);

            if(is_string($objective))
                throwError(GET_RECORDS_NOT_COMPLETE,'Objective could not be obtained.');

            returnResponse(GET_RECORDS_SUCCESSFULLY,'Objective successfully obtained ',$objective);
        }

        /**
         * Registra un objetivo estratégico. Método http => POST.
         * 
         * URL : /Objectives/add
         * 
         * @param string $desc Objetivo estratégico
         * @param int $idCompany Id de la empresa
         * @param int $idPerspective Id de la perspectiva
         * @return object
         */

        public function add()
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $desc = $_POST['desc'] ?? null;
            $idCompany = $_POST['idCompany'] ?? null;
            $idPerspective = $_POST['idPerspective'] ?? null;

            $desc = validateAlfaNumeric('Objetivo',$desc,'Alfanumeric');

            $objectiveService = new ObjectivesServices;

            $exists = $objectiveService->getObjectiveByDesc($desc,$idCompany);
            if($exists)
                returnResponse(GET_RECORDS_NOT_COMPLETE,'Objective already exists.');

            $ok = $objectiveService->add($desc,(int) $idCompany,(int) $idPerspective);

            if(is_string($ok))
                throwError(INSERTED_RECORDS_NOT_COMPLETE,$ok);
            else if($ok)
                returnResponse(RECORDS_INSERT_SUCCESSFULLY,'Successfully registered objective');
            else
                returnResponse(INSERTED_RECORDS_NOT_COMPLETE,'Objective could not be registered');
        }

        /**
         * Registra un objetivo estratégico. Método http => DELETE.
         * 
         * URL : /Objectives/delete/id
         * 
         * @param int $id Id del objetivo estratégico
         * @return object
         */
        public function delete($id)
        {
            if($_SERVER['REQUEST_METHOD']!='DELETE')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            if(!is_numeric($id))
                throwError(INVALID_PARAM,'Invalid param Id.');

            $objectiveService = new ObjectivesServices;
            $ok = $objectiveService->delete((int)$id);

            if(is_string($ok))
                throwError(DELETED_RECORDS_NOT_COMPLETE,'Objective could not be deleted.');

            returnResponse(RECORDS_DELETE_SUCCESSFULLY,'Objective successfully deleted.');
        }

        /**
         * Registra un objetivo estratégico. Método http => POST.
         * 
         * URL : /Objectives/update
         * 
         * @param string $desc Objetivo estratégico
         * @param int $idCompany Id de la empresa
         * @param int $idPerspective Id de la perspectiva
         * @param int $id Id del objetivo estratégico
         * @return object
         */

        public function update()
        {
            if($_SERVER['REQUEST_METHOD']!='POST')
                throwError(REQUEST_METHOD_NOT_VALID,'Method http not valid.');

            $desc = $_POST['desc'] ?? null;
            $idCompany = $_POST['idCompany'] ?? null;
            $idPerspective = $_POST['idPerspective'] ?? null;
            $id = $_POST['id'] ?? null;

            $desc = validateAlfaNumeric('Objetivo',$desc,'Alfanumeric');

            $objectiveService = new ObjectivesServices;

            $exists = $objectiveService->getObjectiveByDesc($desc,$idCompany,$id);
            if($exists)
                returnResponse(GET_RECORDS_NOT_COMPLETE,'Objective already exists.');

             $ok = $objectiveService->update($desc,$idPerspective,$id);

            if(is_string($ok))
                throwError(UPDATED_RECORDS_NOT_COMPLETE,$ok);
            else if($ok)
                returnResponse(RECORDS_UPDATE_SUCCESSFULLY,'Successfully updated objective');
            else
                returnResponse(UPDATED_RECORDS_NOT_COMPLETE,'Objective could not be updated');
        }
    }