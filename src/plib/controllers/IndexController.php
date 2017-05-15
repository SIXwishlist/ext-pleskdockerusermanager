<?php

/**
 * Einstiegsfunktion
 */
class IndexController extends pm_Controller_Action
{
    /**
     * Einstiegsfunktion
     */
    public function indexAction()
    {
        if (! $this->_checkIsInstalled()) {
            return;
        }
        $this->_forward('overview', 'Index');
    }

    /**
     * Einstiegsfunktion
     */
    public function overviewAction()
    {
        $this->view->pageTitle = $this->lmsg('controllers.index.index.title') . " > " . $this->lmsg('controllers.index.overview.title');
    }

    /**
     * Funktion zum prüfen ob das Modul installiert ist, ansonsten muss erst die Konfiguration erfolgen!
     */
    private function _checkIsInstalled()
    {
        if (! Modules_Pleskdockerusermanager_Config::isConfigured()) {
            $this->_forward('index', 'Installation');
            return false;
        }
        return true;
    }
}
