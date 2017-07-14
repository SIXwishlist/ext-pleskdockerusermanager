<?php

/**
 * Abstrakte Klasse für die Datenrequests auf einem Container
 */
abstract class Modules_Pleskdockerusermanager_Dockerapi_Request_AbstractContainer extends Modules_Pleskdockerusermanager_Dockerapi_Request_Requesthandler
{
    protected $containerTargetId;

    /**
     * Erzeugt eine neue Instanz
     *
     * @param integer $containerId ID des Containers
     */
    function __construct($containerId)
    {
        $this->containerTargetId = $containerId;
    }
}
