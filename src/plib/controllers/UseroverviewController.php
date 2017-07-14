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
        if (! Modules_Pleskdockerusermanager_Configfile::isConfigured()) {
             throw new pm_Exception('Permission denied');
        }
        $this->_forward('overview', 'Useroverview');
    }

    /**
     * Einstiegsfunktion
     */
    public function overviewAction()
    {





        $this->view->containers = Modules_Pleskdockerusermanager_Dockerdata::getContainers();
        $this->view->pageTitle = $this->lmsg('controllers.useroverview.index.title') . " > " . $this->lmsg('controllers.useroverview.overview.title');
    }
}
