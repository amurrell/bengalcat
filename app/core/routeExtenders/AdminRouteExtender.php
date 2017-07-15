<?php

/*
 * An example of a RouteExtender that Extends RouteExtender with more specific
 * methods, properties to a particular part of the site that many routes my share.
 *
 */

namespace Bc\App\Core\RouteExtenders;

use Bc\App\Core\Util;

abstract class AdminRouteExtender extends DataRouteExtender {
    
    protected $settings;
    protected $sessionAuthIsEmpty;
    
    protected $body;
    protected $bodyWithSideBar;
    
    protected function doCustomInit() {
        parent::doCustomInit();
        
        $this->settings = $this->bc->getSetting('admin');
        
        $this->theme = $this->settings->theme;
        $this->body = Util::getTemplateContents(
            $this->bc,
            $this->getThemePart('/src/tokenHTML/body.php')
        );
        $this->bodyWithSideBar = Util::getTemplateContents(
            $this->bc,
            $this->getThemePart('/src/tokenHTML/body-sidebar.php')
        );
    }
    
    protected function setTemplatePaths() {
        
        if (!defined('CSS_DIR')) {
            define('CSS_DIR', $this->getThemePart('assets/build/css/'));
            define('JS_DIR', $this->getThemePart('assets/build/js/'));
            define('IMAGE_DIR', $this->getThemePart('assets/build/img/'));
        }
        
        parent::setTemplatePaths();
    }
    
    protected function sessionStart()
    {
        session_start();
        $this->sessionAuthIsEmpty = !isset($_SESSION['auth']);
    }
    
    protected function sessionDestroy()
    {
        if ($this->sessionAuthIsEmpty) {
            return;
        }
        
        session_destroy();
    }
    
    protected function sessionRemoveKey($key)
    {
        unset($_SESSION[$key]);
    }
    
    protected function sessionAddKeyValue($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    protected function getSession()
    {
        return $_SESSION;
    }
}