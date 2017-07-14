<?php

/**
 * Daten der Docker
 */
class Modules_Pleskdockerusermanager_Dockerapi_ApiClient
{
    public function requestData(Modules_Pleskdockerusermanager_Dockerapi_Request_Requesthandler $requestHandle)
    {
        $result = pm_ApiCli::callSbin('dockershell', ['shellrequest', $requestHandle->getShellCommandExtension()]);
        if ($result['code'] !== 0) {
        } else {
            print_r(json_decode($result['stdout'], true));
        }
    }
}
