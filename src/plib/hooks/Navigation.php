<?php

class Modules_Pleskdockerusermanager_Navigation extends pm_Hook_Navigation
{
    public function getNavigation()
    {
        return [
            [
                'controller' => 'index',
                'action' => 'index',
                'tabbed' => true,
                'pages' => [
                    [
                        'controller' => 'index',
                        'action' => 'overview',
                    ],
                    [
                        'controller' => 'sshstat',
                        'action' => 'index',
                    ],
                    [
                        'controller' => 'sustat',
                        'action' => 'index',
                    ],
                    [
                        'controller' => 'configuration',
                        'action' => 'index',
                    ],
                ],
            ],
        ];
    }
}
