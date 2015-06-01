<?php
/**
 * Created by PhpStorm.
 * User: banhtieu
 * Date: 1/25/15
 * Time: 12:22 AM
 */

namespace Core;
use Core\Database\Column;
use Core\Database\NameDecorator;
use Core\Database\DBColumn;

/**
 * Class Migration
 * Database Migration
 * @package Core
 */
class Migration {

    /**
     * @var Database The database
     */
    private $database;

    /**
     * Migrate current database
     * @param $dropTable bool drop the table
     */
    public function execute($dropTable = false) {
        $this->database = Database::getInstance();

        $modelDir = "Application/Model";
        $file = opendir($modelDir);

        // there is fileName
        while (($fileName = readdir($file)) !== false) {
            if (substr($fileName, -4) == ".php") {
                $this->migrateTable($modelDir . "/" . $fileName, $dropTable);
            }
        }
    }


    /**
     * Get Class Properties
     * @param $item
     * @return array
     */
    public static function getProperties($item) {
        // create a reflector
        $reflector = new \ReflectionClass($item);
        $columns = array();

        // iterate all $property
        foreach ($reflector->getProperties() as $property) {

            $document = $property->getDocComment();

            $matches = null;

            // find annotation
            if (preg_match("/@column\\((?P<type>[^)]+)\\)/", $document, $matches)) {
                $type = $matches["type"];

                $column = new Column();

                $column->setType($type);
                $column->name = $property->name;

                $columns[] = $column;
            }
        }

        return $columns;
    }

    /**
     *
     * Migrate a table
     * @param string $fileName
     * @param $dropTable bool drop the table
     */
    public function migrateTable($fileName, $dropTable) {

        include_once ($fileName);
        $className = "Application\\Model\\" . basename($fileName, ".php");

        // create an instance of the class
        $item = new $className();

        $columns = $this->getProperties($item);

        $reflector = new \ReflectionClass($item);
        // get name of the table
        $tableName = NameDecorator::getTableName($reflector->getShortName());
        $dbColumns = false;

        if ($dropTable) {
            $this->database->executeQuery("DROP TABLE $tableName");
        } else {
            $query = "SHOW COLUMNS IN `$tableName`";
            $dbColumns = $this->database->executeQuery($query, "Core\\Database\\DBColumn");
        }

        // if there is no such table, create it
        if ($dbColumns === false || sizeof($dbColumns) == 0) {
            $this->createTable($tableName, $columns);
        } else { // do a migration
            $this->updateTable($tableName, $columns, $dbColumns);
        }

    }

    /**
     *
     * Create the table in database
     * @param $tableName
     * @param $columns
     */
    protected function createTable($tableName, $columns) {

        $columnQueries = array();

        foreach ($columns as $column) {
            /** @var Column $column */
            $columnQueries[] = $column->getSQL();
        }

        $createTableQuery = "CREATE TABLE $tableName (
                ". join(",", $columnQueries) ."
            )";

        // execute query to create table
        $this->database->executeUpdate($createTableQuery);

    }

    /**
     * Update the database table following entity class
     * @param string $tableName
     * @param array $columns
     * @param array $dbColumns
     */
    protected function updateTable($tableName, array $columns, array $dbColumns) {

        $addedColumns = array();
        $updatedColumns = array();
        $droppedColumns = null;

        // iterate all columns to see changed
        foreach ($columns as $column) {
            /** @var Column $column */
            /** @var DBColumn $matchedColumn */
            $matchedColumn = null;
            $index = 0;

            foreach ($dbColumns as $dbColumn) {
                /** @var DBColumn $dbColumn */
                if ($dbColumn->Field == $column->name) {
                    $matchedColumn = $dbColumn;
                    array_splice($dbColumns, $index, 1);
                    break;
                }

                $index++;
            }

            // if there is a matched column
            if ($matchedColumn) {
                // compare type
                if ($matchedColumn->Type != $column->dbType) {
                    $updatedColumns[] = $column;
                }
            } else {
                $addedColumns[] = $column;
            }
        }

        $droppedColumns = $dbColumns;

        // build the query

        $alterStatements = array();

        // build added column statements
        foreach ($addedColumns as $column) {
            /** @var Column $column */
            $alterStatements[] = "ADD " . $column->getSQL();
        }

        // build updated column statements
        foreach ($updatedColumns as $column) {
            /** @var Column column */
            $alterStatements[] = "UPDATE " . $column->getSQL();
        }

        // build drop statements
        foreach ($droppedColumns as $column) {
            /** @var DBColumn column */
            $alterStatements[] = "DROP " . $column->Field . "";
        }

        // if there are changes
        if (sizeof($alterStatements) > 0) {
            $query = "ALTER TABLE $tableName\n";
            $query .= join(",\n", $alterStatements);
            $this->database->executeUpdate($query);
        }
    }

} 