<?php

/**
 * Abstrakte Klasse für die Laufzeitdaten eines Containers
 */
class Modules_Pleskdockerusermanager_Dockerapi_Request_ContainerTop extends Modules_Pleskdockerusermanager_Dockerapi_Request_AbstractContainer
{
     /**
     * Erweiterung für das Shellkommando
     *
     * @return string Kommandoerweiterung
     */
    public function getShellCommandExtension()
    {
        return "http:/v1.24/containers/" . $this->containerTargetId . "/top";
    }
}
