<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 18/05/2015
 * Time: 1:42 CH
 */

namespace Core\Database;


use Core\Database;

define("CRITERIA_EQUAL", "=");
define("CRITERIA_LESS", "<");
define("CRITERIA_NOT_LESS", ">=");
define("CRITERIA_GREATER", ">");
define("CRITERIA_NOT_GREATER", "<=");


/**
 * Class Query
 * @package Core\Database
 */
class Query {

    /**
     * @var string
     */
    private $entityClass;


    /**
     * @var QueryCondition conditions
     */
    private $conditions = array();


    /**
     * @var array pagination option
     */
    private $paginateOptions;

    /**
     * @var string an SQL query string
     */
    private $query;

    /**
     * Create a query
     * @param $entityClass
     */
    public function __construct($entityClass) {
        $this->entityClass = $entityClass;
    }

    /**
     * Find by criteria
     * @param $field
     * @param $value
     * @param $criteria
     * @return Query
     */
    public function findBy($field, $value, $criteria = CRITERIA_EQUAL) {
        $this->conditions[] = new QueryCondition($field, $value, $criteria);
        return $this;
    }

    /**
     * find all
     */
    public function findAll() {
        $this->buildQuery();
        return Database::getInstance()->executeQuery($this->query, "Application\\Model\\" . $this->entityClass);
    }


    /**
     * count all
     */
    public function count() {
        $result = 0;
        $this->buildQuery(true);

        $resultSet = Database::getInstance()->executeQuery($this->query, "Application\\Model\\" . $this->entityClass);

        if (sizeof($resultSet) > 0) {
            $result = intval($resultSet[0]->numberOfRecords);
        }

        return $result;
    }

    /**
     *
     * Build a query
     *
     */
    public function buildQuery($count = false) {
        if ($this->query == null) {
            $database = Database::getInstance();

            $conditionString = array();

            foreach ($this->conditions as $condition) {
                $conditionString[] =
                    "`$condition->field` $condition->criteria "
                    . $database->escapeString($condition->value);
            }

            if (!$count) {
                $this->query = "SELECT * FROM " . NameDecorator::getTableName($this->entityClass) . "\n";
            } else {
                $this->query = "SELECT COUNT(*) as numberOfRecords FROM ". NameDecorator::getTableName($this->entityClass) . "\n";
            }

            if (sizeof($conditionString) > 0) {
                $this->query .= "WHERE " . join(", ", $conditionString);
            }

            if ($this->paginateOptions) {
                $this->query .= " LIMIT {$this->paginateOptions[0]}, {$this->paginateOptions[1]}";
            }
        }
    }


    /**
     * Find one
     * @return first element
     */
    public function first() {
        $result = null;

        $this->paginate(0, 1);
        $this->buildQuery();

        $resultSet = Database::getInstance()->executeQuery($this->query, "Application\\Model\\" . $this->entityClass);

        if (sizeof($resultSet) > 0) {
            $result = $resultSet[0];
        }

        return $result;
    }

    /**
     * Limit number of returning result
     * @param $page int
     * @param $itemPerPage int
     */
    public function paginate($page, $itemPerPage) {
        if ($page !== null && $itemPerPage !== null) {
            $this->paginateOptions = array($page * $itemPerPage, $itemPerPage);
        }
    }
}