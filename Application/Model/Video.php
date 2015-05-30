<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/21/2015
 * Time: 7:18 AM
 */

namespace Application\Model;
use Core\Database\Model;


/**
 * Class Video
 * Video
 * @package Application\Model
 */
class Video extends Model{


    /**
     * @var int id - the unique id of this object
     * @column(identity)
     */
    public $id;


    /**
     * @var string
     * @column(text)
     */
    public $name;


    /**
     * @var string
     * @column(string)
     */
    public $link;


    /**
     * @var string
     * @column(text)
     */
    public $description;


    /**
     * @var int number of view
     * @column(text)
     */
    public $views;
}