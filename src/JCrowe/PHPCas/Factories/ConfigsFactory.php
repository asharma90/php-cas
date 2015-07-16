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
        $configs = require(__DIR__ . '/../config.php');
        $configsEntity = $this->make();

        $configsEntity->setConfigs($configs);

        return $configsEntity;

    }

}