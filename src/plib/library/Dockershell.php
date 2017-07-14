<?php

/**
 * Stellt alle Operationen bereit um auf die Shell
 * zuzugreifen und Operationen rund um die Docker Container auszuführen
 */
class Modules_Pleskdockerusermanager_Dockershell
{
    private static $_statFile = "/usr/local/psa/var/modules/pleskdockerusermanager/dockerstat.log";
    private static $_psFile = "/usr/local/psa/var/modules/pleskdockerusermanager/dockerps.log";
    private static $_inspectFile = "/usr/local/psa/var/modules/pleskdockerusermanager/containerdataset_##id##.json";
    private static $_lastContainerData = null;








    public static function refreshContainerListing()
    {
    }











































    /**
     * Aktuallisiert alle statistischen Daten und gibt diese zurück
     *
     * @return array
     */
    public static function getAllContainers($inspect = true)
    {
        if (self::$_lastContainerData != null) {
            return self::$_lastContainerData;
        }
        $knownContainers = array();
        $knownContainers = self::__getDataFromStatAndPs(self::$_statFile, self::$_psFile, $knownContainers);
        if ($inspect) {
            $knownContainers = self::__inspectAllContainers($knownContainers, self::$_inspectFile);
        }

        foreach ($knownContainers as $val) {
            Modules_Pleskdockerusermanager_Database::refreshContainerData($val);
        }

        self::$_lastContainerData = $knownContainers;
        return $knownContainers;
    }

    /**
     * Gibt Ordner und Dateien frei welche vom Dockercontainer verwendet werden
     *
     * @param string $containerId ID des Containers
     * @return bool True wenn erfolgreich
     */
    public static function enableFilesForEdit($containerId)
    {
        $containers = self::getAllContainers(true);
        if (!array_key_exists($containers[$containerId])) {
            return false;
        }

        foreach ($containers[$containerId]['mountmapping'] as $val) {
            $result = pm_ApiCli::callSbin('dockershell', ['chmod', $val]);
            if ($result['code'] !== 0) {
                throw new pm_Exception ('Error occurred when try to enable directorys.');
            }
        }
        return true;
    }

    /**
     * Gibt einen neuen Eintrag mit allen Parametern zurück
     *
     * @return array mit Basisdaten
     */
    private static function __getContainerEntryArrayNew()
    {
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
        ];
    }

    /**
     * Liest detailierte Daten aus, zu den Containern
     *
     * @param array $inputContainerArray Daten der Container
     * @param string $targetTargetTemplate Template für temporäre Daten
     * @return array Erweiterte Containerdaten
     */
    private static function __inspectAllContainers($inputContainerArray, $targetTargetTemplate)
    {
        foreach ($inputContainerArray as $key => $val) {
            $targetFile = str_replace("##id##", $val['id'], $targetTargetTemplate);
            $result = pm_ApiCli::callSbin('dockershell', ['inspect', $val['id'], $targetFile]);
            if (($result['code'] !== 0) || !file_exists($targetFile)) {
                continue;
            }

            $content = json_decode(file_get_contents($targetFile), true);
            if (!isset($content[0])) {
                continue;
            }
            if (isset($content[0]['LogPath'])) {
                $inputContainerArray[$key]['hostlogfile'] = $content[0]['LogPath'];
            }
            if (isset($content[0]['Mounts'])) {
                $inputContainerArray[$key]['mountmapping'] = $content[0]['Mounts'];
            }
            if (isset($content[0]['Config']['Env'])) {
                $inputContainerArray[$key]['env_vars'] = $content[0]['Config']['Env'];
            }
        }
        return $inputContainerArray;
    }

    /**
     * Ruft das Sheelscript auf und analysiert die Daten aus PS und STAT
     *
     * @param string $fileStat Zieldatei für die STAT Daten
     * @param string $filePs Zieldatei für die PS Daten
     * @param array $inputContainerArray Array mit den vorhandenen Containerdaten
     * @return array Neue Containerdaten
     */
    private static function __getDataFromStatAndPs($fileStat, $filePs, $inputContainerArray)
    {
        $result = pm_ApiCli::callSbin('dockershell', ['stats', $fileStat, $filePs]);
        if ($result['code'] !== 0) {
            throw new pm_Exception ('Error occurred when try to refresh data.');
        }

        $inputContainerArray = self::__parseStatFile($fileStat, $inputContainerArray);
        $inputContainerArray = self::__parsePsFile($filePs, $inputContainerArray);
        return $inputContainerArray;
    }

    /**
     * Parsen der Daten aus der Stat Datei
     *
     * @param string $file Dateiname für den Input
     * @param array $inputContainerArray Array mit vorhandenen Daten
     * @return array neuer Datensatz
     */
    private static function __parseStatFile($file, $inputContainerArray)
    {
        $pregPatternStat = '/([a-z0-9]*)([ \t]*)([0-9.%]*)([ \t]*)([0-9.A-Za-z]+)([ \t]+)\/([ \t]+)([0-9.A-Za-z]+)([ \t]+)([0-9.%]+)([ \t]+)([0-9.A-Za-z]+)([ \t]+)\/([ \t]+)([0-9.A-Za-z]+)([ \t]+)([0-9.A-Za-z]+)([ \t]+)\/([ \t]+)([0-9.A-Za-z]+)([ \t]+)([0-9]+)/';
        $handle = fopen($file, "r");
        if ($handle) {
            $ignoreFirstLine = true;
            while (($line = fgets($handle)) !== false) {
                if ($ignoreFirstLine) {
                    $ignoreFirstLine = false;
                    continue;
                }
                $matches = array();
                if (preg_match_all($pregPatternStat, $line, $matches) == 1) {
                    $statEntry = self::__getContainerEntryArrayNew();
                    if (array_key_exists(trim($matches[1][0]), $inputContainerArray)) {
                        $statEntry = $inputContainerArray[$matches[1][0]];
                    }

                    $statEntry['id'] = trim($matches[1][0]);
                    $statEntry['cpu_usage_percent'] = trim($matches[3][0]);
                    $statEntry['mem_usage'] = trim($matches[5][0]);
                    $statEntry['mem_limit'] = trim($matches[8][0]);
                    $statEntry['mem_usage_percent'] = trim($matches[10][0]);
                    $statEntry['net_in'] = trim($matches[12][0]);
                    $statEntry['net_out'] = trim($matches[15][0]);
                    $statEntry['pids'] = trim($matches[22][0]);
                    $inputContainerArray[$statEntry['id']] = $statEntry;
                }
            }
            fclose($handle);
        }
        return $inputContainerArray;
    }

    /**
     * Parsen der Daten aus der PS Datei
     *
     * @param string $file Dateiname für den Input
     * @param array $inputContainerArray Array mit vorhandenen Daten
     * @return array neuer Datensatz
     */
    private static function __parsePsFile($file, $inputContainerArray)
    {
        $pregPatternPs = '/([a-z0-9]*)([ \t]*)([0-9A-Za-z\/]*)([ \t]*)\\"(.*)\\"([ \t]*)([0-9]*) ([a-zA-Z]*) ([a-zA-Z]*)([ \t]*)([a-zA-Z]*) ([0-9]*) ([a-zA-Z]*)([ \t]*)([0-9.:A-Za-z->\/,]*)([ \t]*)([a-zA-Z-0-9]*)/';
        $handle = fopen($file, "r");
        if ($handle) {
            $ignoreFirstLine = true;
            while (($line = fgets($handle)) !== false) {
                if ($ignoreFirstLine) {
                    $ignoreFirstLine = false;
                    continue;
                }
                $line = preg_replace("/, /", ",", $line);
                $matches = array();
                if (preg_match_all($pregPatternPs, $line, $matches) == 1) {
                    $statEntry = self::__getContainerEntryArrayNew();
                    if (array_key_exists(trim($matches[1][0]), $inputContainerArray)) {
                        $statEntry = $inputContainerArray[$matches[1][0]];
                    }

                    $statEntry['id'] = trim($matches[1][0]);
                    $statEntry['containername'] = trim($matches[3][0]);
                    $statEntry['realname'] = trim($matches[17][0]);
                    $statEntry['portmapping'] = explode(",", trim($matches[15][0]));
                    $inputContainerArray[$statEntry['id']] = $statEntry;
                }
            }
            fclose($handle);
        }
        return $inputContainerArray;
    }

    /**
     * Startet einen Container neu
     *
     * @param string $containerName Name des Containers
     * @return bool True wenn neugestartet
     */
    public static function restartContainer($containerName)
    {
        $result = pm_ApiCli::callSbin('dockershell', ['restart', $containerName]);
        if ($result['code'] !== 0) {
            throw new pm_Exception ('Error occurred when try to restart container.');
        }
        return true;
    }

    /**
     * Beendet einen laufenden Container
     *
     * @param string $containerName Name des Containers
     * @return bool True wenn beendet
     */
    public static function stopContainer($containerName)
    {
        $result = pm_ApiCli::callSbin('dockershell', ['restart', $containerName]);
        if ($result['code'] !== 0) {
            throw new pm_Exception ('Error occurred when try to stop container.');
        }
        return true;
    }

    /**
     * Startet einen Container
     *
     * @param string $containerName Name des Containers
     * @return bool True wenn gestartet
     */
    public static function startContainer($containerName)
    {
        $result = pm_ApiCli::callSbin('dockershell', ['start', $containerName]);
        if ($result['code'] !== 0) {
            throw new pm_Exception ('Error occurred when try to start container.');
        }
        return true;
    }
}
