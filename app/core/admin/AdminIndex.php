<?php

namespace Bc\App\Core\Admin;

use Bc\App\Core\RouteExtenders\AdminRouteExtender;

class AdminIndex extends AdminRouteExtender {
    
    protected function init()
    {
        $this->gate();
    }
    
    protected function gate()
    {
        $this->sessionStart();
        
        if (!$this->sessionAuthIsEmpty) {
            $this->checkAuth();
        }
        
        if ($this->sessionAuthIsEmpty) {
            $this->renderLogin();
        } 
        
        $this->renderLogin();
    }
    
    protected function checkAuth()
    {
        // Decide use the user and pass
        
        // 
        var_export($this->getSession());
        exit();
    }
    
    protected function renderLogin()
    {
        $this->render(
            $this->getThemePart('/src/main/login.php'),
            null, 
            [
                '[:nav]' => '',
                '[:body]' => $this->body,
                
            ]
        );
        exit();
    }
}

