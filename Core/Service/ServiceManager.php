<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/18/2015
 * Time: 9:20 PM
 */

namespace Core\Service;


/**
 * Class ServiceScanner
 * Manages all services & route mapping
 *
 * @package Core\Service
 */
class ServiceManager {

    /**
     * Routes configuration
     * @var ServiceOperation[]
     */
    private $operations = array();

    /**
     * scan the Service folder
     */
    public function scan() {

        $folder = "Application/Service";
        $file = opendir($folder);

        // there is fileName
        while (($fileName = readdir($file)) !== false) {
            if (substr($fileName, -4) == ".php") {
                $this->processService($folder . "/" . $fileName);
            }
        }
    }


    /**
     * @param $file
     */
    public function processService($file) {
        $className = "Application\\Service\\" . basename($file, ".php");
        $service = new $className();
        $reflector = new \ReflectionClass($service);
        $methods = $reflector->getMethods();
        $classDocument = $reflector->getDocComment();

        $basePath = $this->getPath($classDocument);

        foreach ($methods as $method) {
            $operation = new ServiceOperation($basePath, $className, $method);

            if ($operation->httpMethod != null) {
                $this->operations[] = $operation;
            }
        }
    }


    /**
     * Get matches
     * @param $document
     * @return null
     */
    public function getPath($document) {
        $result = null;
        preg_match("/@base\\((?P<path>[^)]+)\\)/", $document, $matches);

        if (sizeof($matches) > 0) {
            $result = $matches["path"];
        }

        return $result;
    }


    /**
     * Process a request
     */
    public function processRequest() {
        $maxScore = 0;
        $targetOperation = null;

        if (isset($_SERVER["PATH_INFO"])){
            $path = $_SERVER["PATH_INFO"];
        } else {
            $path = "/";
        }

        foreach ($this->operations as $operation) {
            $score = $operation->match($path);

            if ($score > $maxScore) {
                $targetOperation = $operation;
                $maxScore = $score;
            }
        }

        if ($targetOperation != null) {
            $targetOperation->execute();
        } else {
            throw new \Exception("Page not found!!");
        }
    }
}