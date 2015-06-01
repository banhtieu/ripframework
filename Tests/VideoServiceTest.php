<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/30/2015
 * Time: 11:50 PM
 */

namespace Tests;

use Application\Model\Video;
use Application\Service\VideoService;


/**
 * @package Tests
 */
class VideoServiceTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test save
     */
    public function testSave() {
        $service = new VideoService();
        $inputVideo = new Video();
        $inputVideo->name = 'Sample Video';
        $inputVideo->description = 'A video';
        $inputVideo->link = 'a link';

        $video = $service->save($inputVideo);

        assert($video != null, 'There is returned value');
        assert(isset($video->id), 'There is id returned');
        assert($video->id > 0, 'Returned id is greater than zero');
    }

    /**
     * Test get all
     */
    public function testGetAll() {
        $service = new VideoService();
        $items = $service->getAll();
        assert(is_array($items), "Result of get all is array");
        assert(sizeof($items) > 0, "Number of results is greater than zero");
    }


    /**
     * Get an item
     */
    public function testGetItem() {
        $service = new VideoService();
        $item = $service->getItem(1);
        assert(is_object($item));
        assert(is_a($item, 'Application\\Model\\Video'));
    }

}
