<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 18/05/2015
 * Time: 1:51 CH
 */

namespace Core\Database;


/**
 * Class QueryCondition
 * @package Core\Database
 */
class QueryCondition {

    /**
     * @var string field to search
     */
    public $field;

    /**
     * @var string value to search
     */
    public $value;

    /**
     * @var string criteria
     */
    public $criteria;


    /**
     *
     * Construct a query Condition
     * @param $field
     * @param $value
     * @param string $criteria
     */
    public function __construct($field, $value, $criteria = CRITERIA_EQUAL) {
        $this->field = $field;
        $this->value = $value;
        $this->criteria = $criteria;
    }
}
