<?php
/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 24/03/2016
 * Time: 14:04
 */

namespace Cve\Controllers;

use Cve\DB\PdoDriver;
use Cve\Helpers\Config;
use Cve\Models\CVEModel;
use Cve\Models\genericData;

class CveFiles extends CoreController
{
    /**
     * @var \Cve\Models\CVEModel
     */
    protected $cveModel;

    public $fields = array(
        'name' => 0,
        'status' => 1,
        'description' => 2,
        'references' => 3,
        'phase' => 4,
        'votes' => 5,
        'comments' => 6
    );

    public function index() {
        //raised max execution time as it takes a while to parse and save all the data.
        ini_set('max_execution_time', 300);
        $this->checkRequestMethod('POST');
        $this->parseFromCsv();
        $this->outputData(new genericData(array('status' => 'Finished importing CSV')), true);
    }

    protected function parseFromCsv() {
        $fileLocation = Config::getConfig('cve_file_location');
        //the csv file contains a few lines which are descriptive about the file, rather than lines we care about.
        //because the headers are not on the first line, I am assuming that they could be on any line, and therefore I have hard coded them above.
        $i = 0;
        $fails = [];
        if (($handle = fopen($fileLocation, "r")) !== FALSE) {
            $this->logger->info('Parsing file: ' . $fileLocation);
            //truncate the tables;
            $this->cveModel->clearCVEData();
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                preg_match('/CVE-[0-9]+-[0-9]+/', $data[$this->fields['name']], $matches);
                //not a line we want to look at.
                if (!$matches) {
                    continue;
                }

                try {
                    $record = $this->prepareData($data);
                    $this->cveModel->saveCVERecord($record);
                    $i++;
                } catch (\Exception $e) {
                    $fails[] = $data[$this->fields['name']];
                }
            }
            if ($fails) {
                $this->logger->warn('Failed to save the records: ' . implode(',' ,$fails));
            }
            $this->logger->info('File finished parsing, ' . $i . ' records have been saved to the database');
        }
    }

    protected function prepareData($data) {
        $record = array(
            'name' => $data[$this->fields['name']],
            'status' => $data[$this->fields['status']],
            'description' => $data[$this->fields['description']],
            'references' => $data[$this->fields['references']],
            'phase' => $data[$this->fields['phase']],
            'votes' => $data[$this->fields['votes']],
            'comments' => $data[$this->fields['comments']]
        );
        //get the name of the commenter, and remove the > symbol
        preg_match_all('/([a-zA-Z1-9]*)>/', $record['comments'], $names);
        $commentsText = preg_split('/([a-zA-Z]*>)/', $record['comments']);
        //first element is null;
        array_shift($commentsText);
        $n = 0;
        $comments = array();
        foreach ($commentsText as $comment) {
            $name = trim($names[1][$n]);
            //format the comment, replace | with \n\n and remove any unwanted whitespaces (including any around new lines)
            $output = trim(trim(preg_replace('/[\s]?\|[\s]?/', '\n\n', preg_replace('!\s+!', ' ', $comment))), '\n\n');

            $comments[] = array(
                'author' => trim($name),
                'comment' => $output
            );
            $n++;
        }
        $record['comments'] = $comments;
        $votes = explode('|', $record['votes']);
        $record['votes'] = array_map('trim', $votes);


        $references = explode('|', $record['references']);
        $record['references'] = array_map('trim', $references);
        return $record;
    }

    /**
     * Allows for mocking for unit testing
     * @returns CVEModel
     */
    protected function setModels() {
        $this->cveModel = new CVEModel($this->logger, PdoDriver::getInstance());
    }
}