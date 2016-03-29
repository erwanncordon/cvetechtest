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

    static function setConfig($config) {
        if (self::$config) {
            throw new \Exception('config Already set');
        };
        self::$config = $config;
    }

    static function getConfig($name) {
        if (empty(self::$config[$name])) {
            throw new MissingConfigException('Config: ' . $name . ' could not be found');
        }
        return self::$config[$name];
    }
}