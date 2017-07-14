<?php

/**
 * Stellt alle Operationen für die Datenbank bereit
 */
class Modules_Pleskdockerusermanager_Database
{
    private static $__dbConnection = null;

    /**
     * Aktuallisiert die Containerdaten
     *
     * @param array $containerDataset Daten für einen Container
     * @return True wenn aktuallisiert
     */
    public static function refreshContainerData($containerDataset)
    {
        $conn = self::__getDatabaseConnection();

        // Basiseintrag
        $sql = "SELECT id FROM pleskdockerusermanager_containers WHERE dockeruid='" . $containerDataset['id'] . "' LIMIT 0,1";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $sql = "INSERT INTO pleskdockerusermanager_containers (dockername, dockeruid, dockertype, dockerlogfile, lastupdate) VALUES (" .
                    "'" . $containerDataset['realname'] . "'," .
                    "'" . $containerDataset['id'] . "'," .
                    "'" . $containerDataset['containername'] . "'," .
                    "'" . $containerDataset['hostlogfile'] . "'," .
                    "'" . time() . "')";
            $result = $conn->query($sql);
        
            $sql = "SELECT id FROM pleskdockerusermanager_containers WHERE dockeruid='" . $containerDataset['id'] . "' LIMIT 0,1";
            $result = $conn->query($sql);
            if ($result->num_rows == 0) {
                return false;
            }
        }
        $row = $result->fetch_assoc();
        $dockerDatabaseId = $row['id'];
        $sql = "UPDATE pleskdockerusermanager_containers SET dockername='" . $containerDataset['realname'] . "', " .
            "dockertype='" . $containerDataset['containername'] . "', " .
            "dockerlogfile='" . $containerDataset['hostlogfile'] . "', lastupdate=" . time() . " WHERE id=" . $dockerDatabaseId;
        $conn->query($sql);
        

        // Erzeuge Mountmap
        $mountMap = array();
        foreach ($containerDataset['mountmapping'] as $val) {
            $mountMap[$val['Source']] = $val['Destination'];
        }

        $sql = "SELECT * FROM pleskdockerusermanager_mountmap WHERE docker_id=" . $dockerDatabaseId;
        $sqlUpdate = "";
        $sqlDelete = "";
        $result = $conn->query($sql);
        $idsToDelete = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (!array_key_exists($row['source'], $mountMap)) {
                    $sqlDelete .= "DELETE FROM pleskdockerusermanager_mountmap WHERE id=" . $row['id'] ."; ";
                } else {
                    $sqlUpdate .= "UPDATE pleskdockerusermanager_mountmap SET destination='" . $mountMap[$row['source']] . "' WHERE id=" . $row['id'] ."; ";
                    unset($mountMap[$row['source']]);
                }
            }

            if (strlen($sqlDelete) > 0) {
                 $conn->query($sqlDelete);
            }
            if (strlen($sqlUpdate) > 0) {
                 $conn->query($sqlUpdate);
            }
        }
        
        foreach ($mountMap as $key => $val) {
            $sqlInsert = "INSERT INTO pleskdockerusermanager_mountmap (docker_id, source, destination) VALUES ('" . $dockerDatabaseId . "','" . $key . "', '" . $val . "'); ";
            $conn->query($sqlInsert);
        }
        return true;
    }




    /**
     * Alle Container aus der Datenbank ziehen
     *
     * @return array Modules_Pleskdockerusermanager_List_Dockercontainers Objekte
     */
    public static function getAllContainers()
    {
        // Daten bekommen
        $containerDataArray = array();
        $conn = self::__getDatabaseConnection();
        $sql = "SELECT * FROM pleskdockerusermanager_containers ORDER BY dockername DESC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $newContainerData = new Modules_Pleskdockerusermanager_List_Dockercontainers();
                $newContainerData->dbId = $row['id'];
                $newContainerData->title = $row['dockername'];
                $newContainerData->ipV4 = "n/a";
                $newContainerData->ipV6 = "n/a";
                $newContainerData->dockerType = $row['dockertype'];
                $containerDataArray[] = $newContainerData;
            }
        }
        return $containerDataArray;
    }































    /**
     * Erzeugt eine neue Verbindung zur Datenbank oder gibt eine vorhandene Zurück
     *
     * @return mysqli Handle zur Datenbankverbindung
     */
    private static function __getDatabaseConnection()
    {
        if (self::$__dbConnection != null) {
            return self::$__dbConnection;
        }
        $serviceParameters = Modules_Pleskdockerusermanager_Configfile::getServiceConfigurationData();

        self::$__dbConnection = new mysqli(
            $serviceParameters['database']['server'],
            $serviceParameters['database']['username'],
            $serviceParameters['database']['password'],
            $serviceParameters['database']['database']);
        if (self::$__dbConnection->connect_error) {
            throw new pm_Exception ("Connection to database failed: " . self::$__dbConnection->connect_error);
        }
        return self::$__dbConnection;
    }
}


/*
return [
            'id' => 'null',
            'realname' => "",
            'containername' => "",
            'cpu_usage_percent' => '0%',
            'mem_usage' => '0MiB',
            'mem_limit' => '0MiB',
            'mem_usage_percent' => '0%',
            'net_in' => '0MiB',
            'net_out' => '0MiB',
            'pids' => '0',
            'portmapping' => array(),
            'mountmapping' => array(),
            'env_vars' => array(),
            'hostlogfile' => ''
        ];*/
