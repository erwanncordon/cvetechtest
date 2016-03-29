<?php
class testBoostrap extends PHPUnit_Framework_TestCase
{
    public function testBootstrapHasLoadedSuccessfully()
    {
        if (!class_exists('Composer\Autoload\ClassLoader')) {
            throw new Exception('Bootstrap has not been loaded composer Autoloader');
        }

        if (!class_exists('\Cve\Controllers\CoreController')) {
            throw new Exception('Bootstrap has not been loaded composer Autoloader');
        }
    }
}