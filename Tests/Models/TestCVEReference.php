<?php

class TestCVEReference extends PHPUnit_Framework_TestCase
{
    public function testGetData() {
        $cveReference = new \Cve\Models\CVEReference();
        $cveReference->reference = 'foo';
        $this->assertEquals('foo', $cveReference->getData());
    }
}