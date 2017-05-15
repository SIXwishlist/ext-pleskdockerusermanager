<?php

/**
 * Installationswizard für die Installation/Einrichtung des Moduls
 */
class InstallationController extends pm_Controller_Action
{
    /**
     * Initialisierung des Controllsers
     */
    public function init()
    {
        parent::init();
        if (!pm_Session::getClient()->isAdmin()) {
            throw new pm_Exception('Permission denied');
        }
    }

    /**
     * Einstiegsfunktion
     */
    public function indexAction()
    {
        $this->view->pageTitle = $this->lmsg('controllers.index.index.title') . " > " . $this->lmsg('controllers.installation.index.title');
        $form = new Modules_Pleskdockerusermanager_Form_Config();

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process();

            $this->_status->addMessage('info', 'Data was successfully saved.');
            $this->_helper->json(['redirect' => pm_Context::getBaseUrl()]);
        }
        $this->view->form = $form;
    }
}
