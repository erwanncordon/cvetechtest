<?php

namespace Cve\Models;

class CVEVote implements DataInterface
{
    /**
     * @var string $vote
     */
    public $vote;

    /**
     * @return string
     */
    public function getData() {
        return $this->vote;
    }
}