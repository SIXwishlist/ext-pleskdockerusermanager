<?php

/**
 * Liste mit Dockercontainern
 */
class Modules_Sshmonitor_List_Dockercontainers extends pm_View_List_Simple
{
    /**
     * Erzeugt eine neue Liste der Container
     *
     * @param Zend_View $view
     * @param Zend_Controller_Request_Abstract $request
     */
    public function __construct(Zend_View $view, Zend_Controller_Request_Abstract $request)
    {
        parent::__construct($view, $request);
        
        
        
        
        /*$iconPath = pm_Context::getBaseUrl() . 'img/flags/16/';
        $data = array();
        foreach (Modules_Sshmonitor_Manager::getLastEntrys() as $obj) {
            $data[] = [
                    'location' => "<img src=\"" . $iconPath . strtolower($obj['country_iso_code']) . ".png\" alt=\"" . $obj['country_name '] . "\" />",
                    'timestamp' => date("d.M.y H:m:s", $obj["timestamp"]),
                    'host' => $obj["host"],
                    'sshd_thread' => $obj["sshd_thread"],
                    'sshtype' => $obj["sshtype"],
                    'port' => $obj["port"],
                    'username' => $obj["username"],
                    'ip' => $obj["ip"]
            ];
        }

        $this->setData($data);
        $this->setColumns(array(
            'location' => array(
                'title' => "Locatoion",
                'sortable' => false,
                'noEscape' => true,
            ),
            'timestamp' => array(
                'title' => "Zeit",
                'sortable' => false,
                'noEscape' => true,
            ),
            'ip' => array(
                'title' => "IP",
                'sortable' => false,
                'noEscape' => true,
            ),
            'username' => array(
                'title' => "Username",
                'sortable' => false,
                'noEscape' => true,
            )
        ));



        $this->setDataUrl(array('action' => 'list-data-latestconnection'));*/
    }
}
