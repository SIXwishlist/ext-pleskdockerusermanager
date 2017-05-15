<?php

/**
 * Erweitert das Adminpanel und Userpanel
 */
class Modules_Pleskdockerusermanager_CustomButtons extends pm_Hook_CustomButtons
{
    /**
     * Buttons
     */
    public function getButtons()
    {
        return [
            // Admin Menü
            [
                'place' => self::PLACE_ADMIN_NAVIGATION,
                'title' => pm_Locale::lmsg('modules.customButtons.adminNav.title'),
                'description' => pm_Locale::lmsg('modules.customButtons.adminNav.desc'),
                'icon' => pm_Context::getBaseUrl() . 'img/icons/app_ico.png',
                'link' => pm_Context::getActionUrl('index')
            ],

            // Hosting Menü (Benutzerseitig)
            [
                'place' => self::PLACE_DOMAIN_PROPERTIES,
                'title' => pm_Locale::lmsg('modules.customButtons.userNav.title'),
                'description' => pm_Locale::lmsg('modules.customButtons.userNav.desc'),
                'icon' => pm_Context::getBaseUrl() . 'img/icons/app_ico.png',
                'link' => pm_Context::getActionUrl('useroverview', 'index')
            ],
           
        ];
    }
}
