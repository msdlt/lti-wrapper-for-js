<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\ForbiddenException;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
		$this->Auth->allow('forbidden');
	}
    
	/**
     * Forbidden method
     *
     * @return void
     * @throws \Cake\Network\Exception\ForbiddenException
     */
    public function forbidden()
    {
		//Return exception if this is an ajax request, so request fails
        if ($this->request->is('ajax')) {
            throw new ForbiddenException('Access Denied');
        }
        //Otherwise, just show friendly, human-readable forbidden page
    }
}
