<?php
/**
 * Created by PhpStorm.
 * User: banhtieu
 * Date: 1/25/15
 * Time: 8:49 AM
 */

namespace Core\Database;


class DBColumn {

    /**
     * @var string Field name
     */
    public $Field;

    /**
     * @var string Type of column
     */
    public $Type;

    /**
     * @var boolean is Null
     */
    public $Null;

    /**
     * @var string Key Type
     */
    public $Key;

    /**
     * @var string extra comments
     */
    public $Extra;


}