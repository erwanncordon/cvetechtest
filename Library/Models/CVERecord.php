<?php
/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 27/03/2016
 * Time: 17:33
 */

namespace Cve\Models;


class CVERecord extends CVEModel implements DataInterface

{
    public $name;
    public $description;
    public $status;
    public $phase;

    /**
     * @var [Comment]
     */
    public $comments = [];

    /**
     * @var [CVEReference]
     */
    public $references = [];

    /**
     * @var [CVEVote]
     */
    public $votes = [];

    public function __construct($logger, $dbDriver, $name, $description, $status, $phase) {
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->phase = $phase;
        parent::__construct($logger, $dbDriver);
    }

    public function decorate() {
        $this->comments = $this->getComments($this->name);
        $this->references = $this->getReferences($this->name);
        $this->votes = $this->getVotes($this->name);
    }

    public function getData() {
        $record = array(
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'phase' => $this->phase,
            'comments' => array(),
            'votes' => array(),
            'references' => array()
        );
        foreach ($this->comments as $comment) {
            $record['comments'][] = $comment->getData();
        }
        foreach ($this->references as $reference) {
            $record['references'][] = $reference->getData();
        }
        foreach ($this->votes as $vote) {
            $record['votes'][] = $vote->getData();
        }
        return $record;
    }
}