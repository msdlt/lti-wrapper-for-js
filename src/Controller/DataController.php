<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\InternalErrorException;

/**
 * Data Controller
 *
 * @property \App\Model\Table\DataTable $Data
 */
class DataController extends AppController
{
    /**
     * download method
     * sends data for user if GET, saves if POST or PUT
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Network\Exception\ForbiddenException If user is not allowed to view results
     */
    public function download()
    {
        $this->viewBuilder()->layout('excel');
        
        $tool = $this->SessionData->getLtiTool();
        
        //If the user is not staff or admin, throw forbidden exception 
        if(!$this->Data->LtiUser->isLTIStaffOrAdmin($tool)) {
            throw new ForbiddenException(__('Not allowed to view results.'));
        }
        
        $results = $this->Data->getResults($tool);
        $cases = $this->Data->getCases();
        
        $this->set(compact('cases', 'results'));
        $this->set('_serialize', ['cases', 'results']);
    }
    
    /**
     * getResults method
     * sends data for user if GET, saves if POST or PUT
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Network\Exception\ForbiddenException If user is not allowed to view results
     */
    public function getResults()
    {
        $this->viewBuilder()->layout('ajax');
        
        $tool = $this->SessionData->getLtiTool();
        
        //If the user is not staff or admin, throw forbidden exception 
        if(!$this->Data->LtiUser->isLTIStaffOrAdmin($tool)) {
            throw new ForbiddenException(__('Not allowed to view results.'));
        }
        
        $results = $this->Data->getResults($tool);
        //pr($results);
        
        $this->set(compact('results'));
        $this->set('_serialize', ['results']);
    }
    
    /**
     * index method
     * sends data for user if GET, saves if POST or PUT
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     * @throws \Cake\Network\Exception\InternalErrorException If there is an error with save.
     */
    public function index()
    {
        $this->viewBuilder()->layout('ajax');
        
        $tool = $this->SessionData->getLtiTool();
        //pr($tool);
        
        //If POST, PUT or PATCH, save the data
        if($this->request->is(['post', 'put'])) {
            $dataToSave = [];
            
            $dataEntity = $this->Data->getLatestUserData($tool);
            
            //pr($this->request->data); exit;
            $data = json_encode($this->request->data);
            
            //If there is existing data, backup it up and then patch with new data
            if(!empty($dataEntity)) {
                $existingDataArray = $dataEntity->toArray();
                $existingId = $existingDataArray['id'];   //Get the ID of the existing record
                unset($existingDataArray['id']);  //Unset the ID for the old data
                $existingDataArray['revision_parent'] = $existingId;  //Set the revision parent to the existing ID
                $dataToSave[] = $this->Data->newEntity($existingDataArray);   //Hydrate the old data
                
                $dataEntity->data = $data;
                $dataToSave[] = $dataEntity; 
            }
            //Otherwise, just save new data
            else {
                $newDataArray = $this->Data->getBasicUserConditions($tool);  //Get the LTI values
                $newDataArray['revision_parent'] = 0;    //No revision parent
                $newDataArray['data'] = $data;   //Add the data
                $dataToSave[] = $this->Data->newEntity($newDataArray);   //Hydrate the data
            }
            
            //pr($dataToSave); exit;
            
            if ($this->Data->saveMany($dataToSave)) {
                $message = 'success: ' . time();
                $this->set('message', $message);
                $this->set('_serialize', ['message']);
            } else {
                throw new InternalErrorException(__('Error saving data'));
            }
        }
        //If anything other than post, return the data
        else {
            $dataEntity = $this->Data->getLatestUserData($tool);

            if(!empty($dataEntity)) {
                $data = json_decode($dataEntity->data);
            }
            else {
                $data = (object)[];
            }
            //pr($data);

            $this->set('data', $data);
            $this->set('_serialize', ['data']);
        }
    }
    
    /**
     * view method
     * allows admins to view the results
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view()
    {   
        $tool = $this->SessionData->getLtiTool();
        
        //If the user is not staff or admin, redirect to cases index
        if(!$this->Data->LtiUser->isLTIStaffOrAdmin($tool)) {
            $this->redirect(['controller' => 'LtiConsumer', 'action' => 'index']);
        }
        
        $cases = $this->Data->getCases();
        
        $this->set('cases', $cases);
    }
}
