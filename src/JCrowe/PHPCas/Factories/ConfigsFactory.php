<?php

namespace JCrowe\PHPCas\Factories;


use JCrowe\PHPCas\Server\CasServerConfigs;

class ConfigsFactory {

    public function make()
    {
        return new CasServerConfigs();
    }


    public function getFromConfigFile()
    {
        $configFile = __DIR__ .'/../../../../../../config/php-cas.php';
        $defaultConfigs = require(__DIR__ . '/../default_config.php');

        $userProvidedConfigs = is_file($configFile) ? require($configFile) : [];

        $configsEntity = $this->make();

        $configsEntity->setConfigs(array_merge($defaultConfigs, $userProvidedConfigs));

        return $configsEntity;

    }

}