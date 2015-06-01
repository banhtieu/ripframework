<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/31/2015
 * Time: 4:31 AM
 */

namespace Core\Helper;


class AppHelper {


    /**
     * develop environment
     */
    const DEVELOP_ENVIRONMENT = 'dev';

    /**
     * Test environment
     */
    const TEST_ENVIRONMENT = 'test';

    /**
     * Production environment
     */
    const PRODUCTION_ENVIRONMENT = 'production';

    /**
     * @return environment
     */
    public static function getEnvironment() {
        $environment = self::PRODUCTION_ENVIRONMENT;

        if (isset($_SERVER["SERVER_NAME"])
            && ($_SERVER["SERVER_NAME"] == 'localhost')) {
            $environment = self::DEVELOP_ENVIRONMENT;
        } else if (defined('TESTING')){
            $environment = self::TEST_ENVIRONMENT;
        }

        return $environment;
    }
}