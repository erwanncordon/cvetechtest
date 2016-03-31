<?php
/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 27/03/2016
 * Time: 17:33
 */

namespace Cve\Models;


use Cve\DB\DBInterface;
use Monolog\Logger;

class CVERecord extends CVEModel implements DataInterface
{

    /**
     * @var string name
     */
    public $name;
    /**
     * @var string description
     */
    public $description;
    /**
     * @var string status
     */
    public $status;
    /**
     * @var string phase
     */
    public $phase;

    /**
     * @var [CVEComment] $comments
     */
    public $comments = [];

    /**
     * @var [CVEReference] $references
     */
    public $references = [];

    /**
     * @var [CVEVote] $votes
     */
    public $votes = [];

    /**
     * CVERecord constructor.
     * @param \Monolog\Logger $logger
     * @param \Cve\DB\DBInterface $dbDriver
     * @param string $name
     * @param string $description
     * @param string $status
     * @param string $phase
     */
    public function __construct(Logger $logger, DBInterface $dbDriver, $name, $description, $status, $phase) {
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->phase = $phase;
        parent::__construct($logger, $dbDriver);
    }

    /**
     * Decorate the CVERecord object by fetching extra data
     */
    public function decorate() {
        $this->comments = $this->getComments($this->name);
        $this->references = $this->getReferences($this->name);
        $this->votes = $this->getVotes($this->name);
    }

    /**
     * @return array
     */
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