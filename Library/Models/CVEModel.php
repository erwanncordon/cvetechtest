<?php
namespace Cve\Models;

/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 25/03/2016
 * Time: 13:11
 */
class CVEModel extends CoreModel
{
    public function saveCVERecord($record) {
        try {
            $this->dbDriver->beginTransaction();

            $this->insertCVERecord($record);
            //clear all the data for this record;

            $this->insertCVEComments($record['name'], $record['comments']);
            $this->insertCVEVotes($record['name'], $record['votes']);
            //clear all the data for this record;
            $this->insertCVEReferences($record['name'], $record['references']);
            $this->dbDriver->commit();
        } catch (\Exception $e) {
            $this->logger->err($e->getMessage());
            $this->dbDriver->rollBackTransaction();
        }
    }

    protected function insertCVERecord($records) {
        $this->dbDriver->insert(
            'cve_records',
            array(
                'name' => array($records['name'], 'string'),
                'status' => array($records['status'], 'string'),
                'description' => array($records['description'], 'string'),
                'phase' => array($records['phase'], 'string')
            )
        );
    }

    protected function insertCVEComments($cve_record_id, $comments) {
        foreach ($comments as $comment) {
            if (!empty($comment['comment'])) {
                $this->dbDriver->insert(
                    'cve_comments',
                    array(
                        'cve_record_id' => array($cve_record_id, 'string'),
                        'author' => array($comment['author'], 'string'),
                        'user_comment' => array($comment['comment'], 'string')
                    )
                );
            }
        }
    }

    protected function insertCVEVotes($cve_record_id, $votes) {
        foreach ($votes as $vote) {
            if (!empty($vote)) {
                $this->dbDriver->insert(
                    'cve_votes',
                    array(
                        'cve_record_id' => array($cve_record_id, 'string'),
                        'vote' => array($vote, 'string')
                    )
                );
            }
        }
    }

    protected function insertCVEReferences($cve_record_id, $references) {
        foreach ($references as $reference) {
            if (!empty($reference)) {
                $this->dbDriver->insert(
                    'cve_references',
                    array(
                        'cve_record_id' => array($cve_record_id, 'string'),
                        'reference' => array($reference, 'string')
                    )
                );
            }
        }
    }


    public function clearCVEData() {
        $this->dbDriver->truncate("cve_records");
        $this->dbDriver->truncate("cve_comments");
        $this->dbDriver->truncate("cve_references");
        $this->dbDriver->truncate("cve_votes");
    }

    public function getRecord($cveNumber) {
        $where = [];
        if ($cveNumber) {
            $where[] =
                array('AND', '=', 'name', $cveNumber, 'string');
        }
        try {
            $records = $this->dbDriver->fetch(
                'cve_records',
                $where
            );
            if (!empty($records)) {
                $record = array_pop($records);
                $cveRecord = new CVERecord($this->logger, $this->dbDriver, $record['name'], $record['description'], $record['status'], $record['phase']);
                $cveRecord->decorate();
                return $cveRecord;
            }
            return null;
        } catch (\Exception $e) {
            $this->logger->err($e->getMessage());
        }
    }

    public function getRecords($limit = null, $offset = 0, $year = null) {
        $where = [];
        if ($year) {
            $where[] =
                array('AND', 'LIKE', 'name', $year, 'string');
        }
        try {
            $data = $this->dbDriver->fetchAll(
                'cve_records',
                $where,
                $limit,
                $offset
            );
            if (!empty($data)) {
                $cveRecords = array();
                foreach ($data as $record) {
                    $cveRecord = new CVERecord($this->logger, $this->dbDriver, $record['name'], $record['description'], $record['status'], $record['phase']);
                    $cveRecord->decorate();
                    $cveRecords[] = $cveRecord;
                }
                return $cveRecords;
            }
            return null;
        } catch (\Exception $e) {
            $this->logger->err($e->getMessage());
        }
        return null;
    }

    public function getComments($cve_record_id) {
        $where = array(array('AND', '=', 'cve_record_id', $cve_record_id, 'string'));
        try {
            return $this->dbDriver->fetchAll(
                'cve_comments',
                $where,
                null,
                0,
                '\Cve\Models\CVEComment',
                'author, user_comment'
            );
        } catch (\Exception $e) {
            $this->logger->err($e->getMessage());
        }
        return null;
    }


    public function getReferences($cve_record_id) {
        $where = array(array('AND', '=', 'cve_record_id', $cve_record_id, 'string'));
        try {
            return $this->dbDriver->fetchAll(
                'cve_references',
                $where,
                null,
                0,
                '\Cve\Models\CVEReference',
                'reference'
            );
        } catch (\Exception $e) {
            $this->logger->err($e->getMessage());
        }
        return null;
    }

    public function getVotes($cve_record_id) {
        $where = array(array('AND', '=', 'cve_record_id', $cve_record_id, 'string'));
        try {
            return $this->dbDriver->fetchAll(
                'cve_votes',
                $where,
                null,
                0,
                '\Cve\Models\CVEVote',
                'vote'
            );
        } catch (\Exception $e) {
            $this->logger->err($e->getMessage());
        }
        return null;
    }
}