<?php

/**
 * Stellt alle Operationen bereit um auf die Shell
 * zuzugreifen und Operationen rund um die Docker Container auszuführen
 */
class Modules_Pleskdockerusermanager_Dockershell
{
    /**
     * Listet alle Container auf, welche auf dem Rechner Aktiv sind
     *
     * @return array
     */
    public static function getAllContainers()
    {
        return array();
    }


    /**
     * Beendet einen laufenden Container
     *
     * @param string $containerName Name des Containers
     * @return bool True wenn beendet
     */
    public static function stopContainer($containerName)
    {
        return false;
    }

    /**
     * Startet einen Container
     *
     * @param string $containerName Name des Containers
     * @return bool True wenn gestartet
     */
    public static function startContainer($containerName)
    {
        return false;
    }

    /**
     * Startet einen Container neu
     *
     * @param string $containerName Name des Containers
     * @return bool True wenn neugestartet
     */
    public static function restartContainer($containerName)
    {
        return false;
    }

    /**
     * Gibt Dateien eines Containers frei
     * Benötigt, da die Container meist nur ROOT Berechtigungen haben!
     *
     * @param string $containerName Name des Containers
     * @return bool True wenn Berechtigungen gesetzt sind
     */
    public static function getAccessContainerFiles($containerName)
    {
        return false;
    }
}
