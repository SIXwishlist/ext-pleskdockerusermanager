<?php

/**
 * Benutzerübersicht der Docker Containers
 */
class UseroverviewController extends pm_Controller_Action
{
    /**
     * Einstiegsfunktion
     */
    public function indexAction()
    {
        if (! Modules_Pleskdockerusermanager_Config::isConfigured()) {
             throw new pm_Exception('Permission denied');
        }
        $this->_forward('overview', 'Index');
    }

    /**
     * Einstiegsfunktion
     */
    public function overviewAction()
    {
        $this->view->pageTitle = $this->lmsg('controllers.useroverview.index.title') . " > " . $this->lmsg('controllers.useroverview.overview.title');
    }
}
