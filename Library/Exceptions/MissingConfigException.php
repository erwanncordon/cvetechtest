<?php
/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 24/03/2016
 * Time: 14:52
 */

namespace Cve\Exceptions;


class MissingConfigException extends \Exception
{
    public function __construct($message, $code = null, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}