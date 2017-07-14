<?php

/**
 * Abstrakte Klasse für die Datenrequests
 */
abstract class Modules_Pleskdockerusermanager_Dockerapi_Request_Requesthandler
{
    /**
     * Erweiterung für das Shellkommando
     *
     * @return string Kommandoerweiterung
     */
    abstract public function getShellCommandExtension();
}
