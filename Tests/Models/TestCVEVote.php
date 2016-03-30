<?php

class TestCVEVote extends PHPUnit_Framework_TestCase
{
    public function testGetData() {
        $cveVote = new \Cve\Models\CVEVote();
        $cveVote->vote = 'foo';
        $this->assertEquals('foo', $cveVote->getData());
    }
}