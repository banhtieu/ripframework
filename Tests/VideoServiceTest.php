<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/30/2015
 * Time: 11:50 PM
 */

namespace Tests;

use Application\Service\VideoService;


/**
 * Class VideoServiceTest
 * @package Tests
 */
class VideoServiceTest extends \PHPUnit_Framework_TestCase {

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
