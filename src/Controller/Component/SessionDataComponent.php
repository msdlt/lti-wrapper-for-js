<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class SessionDataComponent extends Component
{
    public function getLtiTool() {
        $tool = $this->request->session()->read('tool');
        
        return $tool;
    }
}