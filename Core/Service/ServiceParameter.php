<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/18/2015
 * Time: 10:29 PM
 */

namespace Core\Service;

/**
 * Class ServiceParameter
 * @package Core\Service
 */
class ServiceParameter {

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $defaultValue;

    /**
     * Generate a parameter and type
     *
     * @param $name
     * @param $type
     * @param $defaultValue
     */
    public function __construct($name, $type, $defaultValue) {
        $this->name = $name;
        $this->type = $type;
        $this->defaultValue = $defaultValue;
    }
}