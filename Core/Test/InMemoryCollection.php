<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/31/2015
 * Time: 12:29 AM
 */

namespace Core\Test;


use Core\Database\Collection;
use Core\Database\Model;

/**
 * Class InMemoryCollection
 * @package Core\Test
 */
class InMemoryCollection extends Collection {


    /**
     * @var Model[] records
     */
    public $records;

    /**
     * @var int id generator
     */
    private $currentId = 0;


    /**
     * Create a new constructions
     * @param $name
     */
    public function __construct($name) {
    }

    /**
     * Save an entity
     * @param mixed $entity
     */
    public function save(&$entity) {

        $keyValue = $entity->id;

        if ($keyValue != null) {

            foreach ($this->records as &$record) {
                if ($record->id == $keyValue) {
                    $record = $entity;
                }
            }
        } else {
            $this->records[] = $entity;
            $entity->id = ++$this->currentId;
        }
    }


    /**
     * @return Query the query for this collection
     */
    public function buildQuery() {
        return new InMemoryQuery($this);
    }

    /**
     * Find all parameters
     * @return array - result
     */
    public function findAll() {
        return $this->buildQuery()->findAll();
    }


    /**
     * @param $item
     *
     * @return int number of affected rows
     */
    public function delete($item) {
        $offset = 0;

        if (is_object($item)) {
            $item = $item->id;
        }

        foreach ($this->records as &$record) {
            if ($record->id == $item) {
                break;
            }
            $offset++;
        }

        if ($offset < sizeof($this->records)) {
            array_splice($this->records, $offset, 1);
        }

    }


    /**
     * @return string the table name
     */
    public function getTableName() {
        return NameDecorator::getTableName($this->name);
    }

    /**
     * Get instance of class name
     * @return mixed
     */
    public function getEntityClass() {
        $className = "Application\\Model\\" . $this->name;
        return new $className();
    }
}