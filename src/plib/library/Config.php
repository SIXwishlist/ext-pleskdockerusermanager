<?php

/**
 * Lesen / Schreiben der Konfigurationsdaten
 */
class Modules_Pleskdockerusermanager_Config
{
    private static $_configFile = "/usr/local/psa/var/modules/pleskdockerusermanager/config.conf";

    /**
     * Gibt die Konfigurationsdaten für den Service zurück
     * @return array Array mit den Konfigurationsdaten welche gesetzt sind
     */
    public static function getServiceConfigurationData()
    {
        if (!file_exists($_configFile)) {
            return [
              'database' => [
                  'server' => 'localhost',
                  'database' => 'server',
                  'username' => 'mysql_user',
                  'password' => '',
                  'prefix' => ''
              ]
            ];
        }
        return json_decode(file_get_contents(self::$_configFile));
    }

    /**
    * Setzt die Daten für die Konfiguration
    * @param array $configData Array mit den Konfigurationsdaten welche gesetzt werden sollen
    */
    public static function setServiceConfigurationData($configData)
    {
        file_put_contents(self::$_configFile, json_encode($serviceConfigData));
    }

    /**
     * Prüft ob die Konfiguration gesetzt ist
     * @return bool True wenn Datei existiert, anderenfalls false!
     */
    public static function isConfigured()
    {
        return file_exists(self::$_configFile);
    }
}
