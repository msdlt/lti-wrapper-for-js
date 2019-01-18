<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Event\Event;

/**
 * LtiConsumer Controller
 *
 * @property \App\Model\Table\LtiConsumerTable $LtiConsumer
 */
class LtiConsumerController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('launch');
	}
    
    /**
     * Launch method
     *
     * Verifies LTI Launch, processes user information and then redirects appropriately
     * Need to add redirection logic (line 65)
     *
     * @return \Cake\Network\Response|void Redirects to Choice view (if Choice is associated) or Choice link action
     * @throws \Cake\Network\Exception\ForbiddenException When not an LTI request, or LTI request fails.
     * @throws \Cake\Network\Exception\InternalErrorException When user cannot be saved.
     * @throws \Cake\Network\Exception\ForbiddenException When choice is not found and user does not have permission to configure it.
     */
    public function launch() {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;
        if(isset($_REQUEST['lti_message_type']) && isset($_REQUEST['oauth_consumer_key'])) {	//Is this an LTI request
            //lis_result_sourcedid is not sent by WebLearn, but is required for the LTI_Tool_Provider to save the user
            //Use the lis_person_sourcedid as the lis_result_sourcedid
            if(!isset($_POST['lis_result_sourcedid']) && isset($_POST['lis_person_sourcedid'])) {
                $_POST['lis_result_sourcedid'] = $_POST['lis_person_sourcedid'];
            }
			require_once(ROOT . DS . 'vendor' . DS  . 'lti-tool-provider' . DS . 'LTI_Tool_Provider.php');	//Load the LTI class
            
            //Connect to the database using the LTI data connector (not the Cake way!)
            //TODO: Could we do this in a more Cakey way?
            $dbconfig = ConnectionManager::get('default')->config();    //Get the database config using Cake ConnectionManager
            $db_connection_object = mysqli_connect($dbconfig['host'], $dbconfig['username'], $dbconfig['password'], $dbconfig['database']);
						
            //mysqli_select_db($link, $dbconfig['database']);
            $db_connector = \LTI_Data_Connector::getDataConnector('', $db_connection_object, 'mysqli');  
						    
            //Verify the launch
            //This calls the onLaunch method in the LTIToolProviderComponent, which just sets ltilaunch = true
            //Normally would do the LTI launch processing there, but that has to be a static function
            //Therefore, we will do all the processing from here, so we check for ltilaunch below then do the processing
            $tool = new \LTI_Tool_Provider('App\Controller\Component\LTIToolProviderComponent::onLaunch', $db_connector);
            $tool->execute();

            //Check that we have gone to the LTIToolProviderComponent::onLaunch method
            if($tool->ltilaunch) {
                //Add Sakai display ID to user data so it can be used as username
                if(isset($_POST['ext_sakai_provider_displayid']) && !isset($tool->user->displayid)) {
                    $tool->user->displayid = $_POST['ext_sakai_provider_displayid'];
                }
                
                //Add the tool to the session
                $session = $this->request->session();
                $session->write('tool', $tool);
				
				//Register the user
                if($user = $this->LtiConsumer->LtiContext->LtiUser->LtiUserUsers->Users->register($tool)) {
                    //Log the user in
                    $this->Auth->setUser($user->toArray());
                    
                    //If user is Staff or Admin, take them to the reporting interface
                    //if($this->LtiConsumer->LtiContext->LtiUser->isLTIStaffOrAdmin($tool)) {
                    //    $this->redirect(['controller' => 'data', 'action' => 'view']);
                    //}
                    //Otherwise, send them to the cases index
                    //else {
                        $this->redirect(['action' => 'index']);
                    //}
                }
                else {
                    throw new InternalErrorException(__('User details could not be saved.'));
                }
            }
            else {
                throw new ForbiddenException(__('Failed LTI Launch.'));
            }
        }
        else {
            throw new ForbiddenException(__('LTI Launch Required.'));
        }
    }
    
    public function demo() {
        $this->viewBuilder()->layout('jquery');
        //Demo action that just shows a page with "Demo page" text
    }
    
    /* Show neurosim index page */
    public function index() {
        $this->viewBuilder()->layout('cerebellar-activities');
        
        $tool = $this->SessionData->getLtiTool();
        $contextId = $tool->context->lti_context_id;
        $resourceLinkId = $tool->resource_link->getId();
        
        $loginUrl = "https://weblearn.ox.ac.uk/access/basiclti/site/" . $contextId . "/" . $resourceLinkId;
        $this->set("loginUrl", $loginUrl);
    }
}
