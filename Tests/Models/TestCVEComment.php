<?php

class TestCVEComment extends PHPUnit_Framework_TestCase
{
    public function testGetData() {
        $cveComment = new \Cve\Models\CVEComment();
        $cveComment->author = 'foo';
        $cveComment->user_comment = 'bar';
        $this->assertEquals(array('author' => 'foo', 'user_comment' => 'bar'), $cveComment->getData());
    }
}