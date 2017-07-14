<?php

/**
 * Form für die Einstellungen
 */
class Modules_Pleskdockerusermanager_Form_Config extends pm_Form_Simple
{
    /**
     * Initialisiere die FORM
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $configurationDataSet = Modules_Pleskdockerusermanager_Configfile::getServiceConfigurationData();

        $this->addElement('select', 'db_server', [
            'label' => 'Mysql Server',
            'multiOptions' => array('localhost' => 'localhost',),
            'value' => $configurationDataSet['database']['server'],
            'required' => true,
        ]);
        $this->addElement('text', 'db_database', [
            'label' => 'Mysql Database',
            'value' => $configurationDataSet['database']['database'],
            'required' => true,
            'validators' => [
                ['NotEmpty', true],
            ],
        ]);
        $this->addElement('text', 'db_username', [
            'label' => 'Mysql Benutzer',
            'value' => $configurationDataSet['database']['username'],
            'required' => true,
            'validators' => [
                ['NotEmpty', true],
            ],
        ]);
        $this->addElement('password', 'db_password', [
            'label' => 'Mysql Passwort',
            'value' => '',
            'required' => true,
            'validators' => [
                ['NotEmpty', true],
            ],
        ]);

        $this->addControlButtons(array(
            'cancelLink' => pm_Context::getBaseUrl(),
        ));
    }

    /**
     * Verarbeite die Daten der Form
     *
     * @return void
     */
    public function process()
    {
        $configurationDataSet = [
              'database' => [
                  'server' => $this->getValue('db_server'),
                  'database' => $this->getValue('db_database'),
                  'username' => $this->getValue('db_username'),
                  'password' => $this->getValue('db_password'),
                  'prefix' => ''
              ]
          ];

          Modules_Pleskdockerusermanager_Configfile::setServiceConfigurationData($configurationDataSet);
    }
}
