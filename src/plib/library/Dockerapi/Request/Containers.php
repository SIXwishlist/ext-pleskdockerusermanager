<?php

/**
 * Abstrakte Klasse für die Datenrequests
 */
class Modules_Pleskdockerusermanager_Dockerapi_Request_Containers extends Modules_Pleskdockerusermanager_Dockerapi_Request_Requesthandler
{
    private $enableGetSize;
    private $enableGetAll;

    /**
     * Erezugt eine neue Instanz
     *
     * @param boolean $enableGetAll Anzeige aller Container auch der nicht laufenden
     * @param boolean $enableGetSize Anzeige der verwendeten Größe
     */
    public function __construct($enableGetAll = false, $enableGetSize = false)
    {
        $this->enableGetSize = $enableGetSize;
        $this->enableGetAll = $enableGetAll;
    }

     /**
     * Erweiterung für das Shellkommando
     *
     * @return string Kommandoerweiterung
     */
    public function getShellCommandExtension()
    {
        $cmd = "http:/v1.24/containers/json?";
        if ($this->enableGetAll) {
            $cmd .= "all=1&";
        }
        if ($this->enableGetSize) {
            $cmd .= "size=1&";
        }
        return substr($cmd, 0, -1);
    }
}
