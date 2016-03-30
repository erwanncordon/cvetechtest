<?php

namespace Cve\Models;

class CVEVote implements DataInterface
{
    public $vote;

    public function getData() {
        return $this->vote;
    }
}