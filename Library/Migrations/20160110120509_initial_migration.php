<?php

use Phinx\Migration\AbstractMigration;

class InitialMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up() {
        $this->createRecordsTable();

        $this->createCommentsTable();

        $this->createReferencesTable();

        $this->createVotesTable();

    }

    private function createRecordsTable() {
        $recordsTable = $this->table('cve_records', array('id' => false));
        $recordsTable->addColumn('name', 'string', array('limit' => 100))
            ->addColumn('status', 'string', array('limit' => 255))
            ->addColumn('description', 'text', array('null' => false))
            ->addColumn('phase', 'text', array('null' => false))
            ->addIndex(array('name'), array('unique' => true))
            ->create();
    }

    private function createCommentsTable() {
        $commentsTable = $this->table('cve_comments', array('id' => false));
        $commentsTable->addColumn('cve_record_id', 'string', array('limit' => 100))
            ->addColumn('author', 'string', array('null' => false, 'limit' => 255))
            ->addColumn('user_comment', 'text', array('null' => false))
            ->addIndex(array('cve_record_id'))
            ->create();
    }

    private function createReferencesTable() {
        $referenceTable = $this->table('cve_references', array('id' => false));
        $referenceTable->addColumn('cve_record_id', 'string', array('limit' => 100))
            ->addColumn('reference', 'text', array('null' => false))
            ->addIndex(array('cve_record_id'))
            ->create();

    }

    private function createVotesTable() {
        $votesTable = $this->table('cve_votes', array('id' => false));
        $votesTable->addColumn('cve_record_id', 'string', array('limit' => 100))
            ->addColumn('vote', 'text', array('null' => false))
            ->addIndex(array('cve_record_id'))
            ->create();
    }

    public function down() {
        $this->dropTable('cve_references');
        $this->dropTable('cve_votes');
        $this->dropTable('cve_comments');
        $this->dropTable('cve_records');
    }
}
