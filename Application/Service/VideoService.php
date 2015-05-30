<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 18/05/2015
 * Time: 1:38 CH
 */
namespace Application\Service;

use Application\Model\Video;
use Core\Database\Collection;
use Core\Database\Repository;

/**
 * Class TourService
 * @package Application\Service
 * @base(/videos)
 */
class VideoService extends CRUDService {



    /**
     * The default constructor
     */
    public function __construct() {
        $this->collection = Repository::get("Video");
    }
}