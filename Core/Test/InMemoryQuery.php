<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/31/2015
 * Time: 12:39 AM
 */

namespace Core\Test;


use Core\Database\Query;

class InMemoryQuery extends Query {

    /**
     * @var InMemoryCollection in memory collection
     */
    protected $collection;


    /**
     * @param $collection InMemoryCollection the target collection
     */
    public function __construct($collection) {
        $this->collection = $collection;
    }


    /**
     * find all
     */
    public function findAll() {
        return $this->collection->records;
    }


    /**
     * find all
     */
    public function first() {
        return $this->collection->records[0];
    }


    /**
     * find all
     */
    public function count() {
        return sizeof($this->collection->records);
    }
}