<?php

/**
 * Stellt alle Operationen bereit um Daten der Docker in der Datenbank
 * zu lesen oder schreiben
 */
class Modules_Pleskdockerusermanager_Dockerdata
{








    public static function getContainers()
    {
        $containerData = Modules_Pleskdockerusermanager_Database::getAllContainers();
        return $containerData;
    }
}
