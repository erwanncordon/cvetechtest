<?php

namespace Cve\Helpers;

use Cve\Exceptions\MissingConfigException;


/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 24/03/2016
 * Time: 14:48
 */
class Config
{

    protected static $config = array();

    /**
     * Sets the config, if it is already set an error is thrown
     * @param array $config
     * @throws \Exception
     */
    static function setConfig($config) {
        if (self::$config) {
            throw new \Exception('config Already set');
        };
        self::$config = $config;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws MissingConfigException
     */
    static function getConfig($name) {
        if (empty(self::$config[$name])) {
            throw new MissingConfigException('Config: ' . $name . ' could not be found');
        }
        return self::$config[$name];
    }
}