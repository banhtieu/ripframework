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
use Core\Database\Repository;
use Core\Test\InMemoryCollection;


/**
 * Class VideoServiceTest
 * @package Tests
 */
class VideoServiceTest extends \PHPUnit_Framework_TestCase {

    /**
     * Set up the test
     */
    public function setUp() {
        $videoCollection = new InMemoryCollection("Video");

        $video = new Video();

        $video->name = "Sample Video";
        $video->link = "a link";
        $video->description = "This is good";

        $videoCollection->save($video);

        Repository::put("Video", $videoCollection);
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

}
