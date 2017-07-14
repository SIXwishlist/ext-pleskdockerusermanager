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


        $requestModelA = new Modules_Pleskdockerusermanager_Dockerapi_Request_Containers(true);
        $requestModelB = new Modules_Pleskdockerusermanager_Dockerapi_Request_ContainerInspect('53cb7e31a6935a73c98c80c15e44e15dbd4c21529e9d27147d45831ecffb4d1e');
        //$requestModelC = new Modules_Pleskdockerusermanager_Dockerapi_Request_ContainerTop('53cb7e31a6935a73c98c80c15e44e15dbd4c21529e9d27147d45831ecffb4d1e');
        //$requestModelD = new Modules_Pleskdockerusermanager_Dockerapi_Request_ContainerStats('53cb7e31a6935a73c98c80c15e44e15dbd4c21529e9d27147d45831ecffb4d1e');

        $cli = new Modules_Pleskdockerusermanager_Dockerapi_ApiClient();

        echo "<pre>";
        $cli->requestData($requestModelA);

        $cli->requestData($requestModelB);

        //$cli->requestData($requestModelC);

        //$cli->requestData($requestModelD);
        echo "</pre>";

        /*$docker = new Modules_Pleskdockerusermanager_Dockerapi_ApiClient('127.0.0.1', 32776);

        echo "<pre>";
        $response = $docker->get('/containers/json');
        if ($response->getStatus() === 200) {
            $responseHandler = new Modules_Pleskdockerusermanager_Dockerapi_ResponseHandlers_Json($response);
            $containers = $responseHandler->getData();
            var_dump($containers);
        } else {
            var_dump($response);
        }
        echo "</pre>";*/


        
        

        // Modules_Pleskdockerusermanager_Dockershell::getAllContainers();
    }

    /**
     * Funktion zum prüfen ob das Modul installiert ist, ansonsten muss erst die Konfiguration erfolgen!
     */
    private function _checkIsInstalled()
    {
        if (! Modules_Pleskdockerusermanager_Configfile::isConfigured()) {
            $this->_forward('index', 'Installation');
            return false;
        }
        return true;
    }
}
