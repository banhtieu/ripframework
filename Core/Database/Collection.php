<?php
/**
 * Created by PhpStorm.
 * User: banhtieu
 * Date: 2/1/15
 * Time: 11:03 AM
 */

namespace Core\Database;
use Core\Database;
use Core\Migration;


/**
 * Class Collection
 * @package Core\Database
 */
class Collection {

    /**
     * @var string name of the collection
     */
    protected $name;


    /**
     * Construct a collection with a name
     * @param $name
     */
    public function __construct($name) {
        $this->name = $name;
    }


    /**
     * Save an entity
     * @param Model $entity
     */
    public function save(&$entity) {
        /* @var Column $keyColumn */
        $keyColumn = null;
        $tableName = $this->getTableName();
        $columns = Migration::getProperties($this->getEntityClass());
        $database = Database::getInstance();

        $keys = array();
        $values = array();

        foreach ($columns as $column) {
            if (!$column->isKey) {
                $keys[] = $column->name;
                $value = null;

                if (isset($entity->{$column->name})) {
                    $value = $entity->{$column->name};
                }

                $values[] = $value;
            } else {
                $keyColumn = $column;
            }
        }

        if ($keyColumn) {
            $keyValue = null;

            if (isset($entity->{$keyColumn->name})) {
                $keyValue = $entity->{$keyColumn->name};
            }

            $query = null;

            // if there is an id
            if ($keyValue != null) {

                $setCommands = array();

                foreach ($columns as $column) {
                    /* @var Column $column */
                    if (!$column->isKey) {
                        $setCommands[] = $column->name . " = ?";
                    }
                }

                $query = "UPDATE $tableName";
                $query .= " SET " . join(", ", $setCommands);
                $query .= " WHERE $keyColumn->name = ?";

                $values[] = $keyValue;

                $database->executeUpdate($query, $values);
            } else {


                $query = "INSERT INTO $tableName";
                $query.= "(" . join(", ", $keys) . ")";

                $questionMarks = array_fill(0, sizeof($columns) - 1, "?");

                $query .= "VALUES (" . join(", ", $questionMarks) . ")";

                $database->executeUpdate($query, $values);
                $entity->{$keyColumn->name} = $database->getInsertId();
            }
        }
    }


    /**
     * @return Query the query for this collection
     */
    public function buildQuery() {
        return new Query($this->name);
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
        $itemId = $item;

        if (is_object($item)) {
            $itemId = $item->id;
        }

        $query = "DELETE FROM " . $this->getTableName() . " WHERE id = ?";
        return Database::getInstance()->executeUpdate($query, array($itemId));

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