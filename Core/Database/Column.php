<?php
/**
 * Created by PhpStorm.
 * User: banhtieu
 * Date: 1/25/15
 * Time: 5:40 AM
 */

namespace Core\Database;

/**
 * Class ColumnInfo
 * @package Core\Database
 */
class Column {


    /**
     * name of the column
     * @var string
     */
    public $name;

    /**
     * type of the column
     * @var string
     */
    public $type;

    /**
     * if the column is a key
     * @var bool
     */
    public $isKey;

    /**
     * Database type
     * @var int
     */
    public $dbType;

    /**
     * @param string $type
     */
    public function setType($type)
    {

        $this->type = $type;

        // set database type
        if ($type == "string") {
            $this->dbType = "varchar(255)";
        } else if ($type == "identity") {
            $this->dbType = "int(11)";
            $this->isKey = true;
        } else if ($type == "int") {
            $this->dbType = "int(11)";
        } else {
            $this->dbType = $type;
        }
    }

    /**
     * Get SQL
     */
    public function getSQL()
    {
        $sql = $this->name . " " . $this->dbType;

        if ($this->isKey) {
            $sql .= " PRIMARY KEY NOT NULL AUTO_INCREMENT";
        }

        return $sql;
    }



} 