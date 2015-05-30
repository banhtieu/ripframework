<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/18/2015
 * Time: 6:37 PM
 */

namespace Core\Service;


/**
 * Class ServiceOperator
 * Contains meta-data of a service operator
 * @package Core\Service
 */
class ServiceOperation {

    /**
     * @var string the class name
     */
    public $className;

    /**
     * @var string the method name
     */
    public $methodName;

    /**
     * @var string the route mapping
     */
    public $route;

    /**
     * @var string HTTP Method
     */
    public $httpMethod;

    /**
     * @var ServiceParameter[]
     */
    public $parameters = array();


    /**
     * @var mixed parameters in path
     */
    public $pathParameters = array();


    /**
     *
     * initialize the class
     * @param $basePath
     * @param $className
     * @param $method \ReflectionMethod
     */
    public function __construct($basePath, $className, $method) {
        $this->className = $className;
        $this->methodName = $method->getName();
        $this->route = $basePath;

        $this->parse($method);
    }

    /**
     * Parse another content from $document
     * @param $method \ReflectionMethod
     */
    private function parse($method){
        $document = $method->getDocComment();

        preg_match("/@(?P<method>(get|post|delete|head|put))\\((?P<path>[^)]+)\\)/",
            $document, $match);

        if (sizeof($match) > 0) {

            $this->route .= $match["path"];
            $this->httpMethod = $match["method"];

            preg_match_all("/@param \\$(?P<name>[^\\s]+)\\s+[^#]+#(?P<type>[^\\s]+).*\\n/", $document, $matches);

            $numberOfParams = sizeof($matches[0]);
            $params = $method->getParameters();


            for ($i = 0; $i < $numberOfParams; $i++) {
                $parameterName = $matches["name"][$i];
                $parameterType = $matches["type"][$i];

                $param = $params[$i];
                $defaultValue = null;

                if ($param->isDefaultValueAvailable()) {
                    $defaultValue = $param->getDefaultValue();
                }

                $parameterInfo = new ServiceParameter($parameterName, $parameterType, $defaultValue);
                $this->parameters[] = $parameterInfo;
            }
        }
    }


    /**
     * Calculate a score base
     * @param $path
     * @return int score of this $path
     */
    public function match($path) {
        $components = preg_split("@/@", $this->route, NULL, PREG_SPLIT_NO_EMPTY);
        $pathComponents = preg_split("@/@", $path, NULL, PREG_SPLIT_NO_EMPTY);
        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);

        $count = sizeof($components);
        $score = 0;

        if ($count == sizeof($pathComponents) && $requestMethod == $this->httpMethod) {
            $score = 1;

            for ($i = 0; ($i < $count) && ($score > 0); $i++) {
                $score *= 2;
                $component = $components[$i];
                $pathComponent = $pathComponents[$i];

                // variable
                if ($component[0] == ':') {
                    $this->pathParameters[substr($component, 1)] = $pathComponent;
                } else {
                    if ($component == $pathComponent) {
                        $score = $score + 1;
                    } else {
                        $score = 0;
                    }
                }
            }
        }

        return $score;
    }


    /**
     * execute the request
     */
    public function execute() {
        $parameters = array();

        foreach ($this->parameters as $parameterInfo) {
            $parameter = null;

            if ($parameterInfo->type == "query") {
                if (isset($_REQUEST[$parameterInfo->name])) {
                    $parameter = $_REQUEST[$parameterInfo->name];
                } else if ($parameterInfo->defaultValue) {
                    $parameter = $parameterInfo->defaultValue;
                }
            } else if ($parameterInfo->type == "path") {
                $parameter = $this->pathParameters[$parameterInfo->name];
            } else if ($parameterInfo->type == "body") {
                $parameter = json_decode(file_get_contents("php://input"));
            } else if ($parameterInfo->type == "file") {
                if (isset($_FILES[$parameterInfo->name])) {
                    $parameter = new UploadedFile($parameterInfo->name);
                }
            }

            $parameters[] = $parameter;
        }

        $service = new $this->className();
        $data = call_user_func_array(array($service, $this->methodName), $parameters);

        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        echo json_encode($data);
    }
}