<?php
/**
 * Created by PhpStorm.
 * User: banhtieu
 * Date: 8/12/2015
 * Time: 3:02 AM
 */

namespace Application\Service;


/**
 * The service that manage download page
 * Class DownloadService
 * @package Application\Service
 */
class DownloadService
{

    const ARRAY_PATTERN = "/\\{packagename:[^\\}]+\\}/";
    const ARRAY_ELEMENT_PATTERN = "/(\\b[^:]+):\\s+([^,]+),/";
    const VARIABLE_DEFINE_PATTERN = "/var\\s+%s\\s*=\\s*'([^']*)'/";


    /**
     *
     * @get(/download/:packageName)
     * Download Package Name
     * @param $packageName string name of the package #path
     */
    public function download($packageName) {

        $html = $this->getHTML("https://apps.evozi.com/apk-downloader/?id=$packageName");
        $definition = $this->getPostDataDefinition($html);
        list($t, $tokenName, $variableName) = $this->extractDefinition($definition);
        $token = $this->getToken($html, $variableName);

        $data = $this->getData($packageName, $t, $tokenName, $token);


        $url = $data->url;

        return $url;
    }
    
    /**
     * get HTML Source of an $url
     * @param $url
     *
     * @return html source
     */
    private function getHTML($url) {
        return file_get_contents($url);
    }

    /**
     * get post data definition like following pattern {packagename:...}
     * @param $html
     * @return text that matches pattern
     */
    private function getPostDataDefinition($html) {
        preg_match(self::ARRAY_PATTERN, $html, $matches);
        return $matches[0];
    }


    /**
     * extract data from the definition {packagename:....}
     * @param $definition
     *
     * @return array $t, $tokenName, $variable contains the token value
     */
    private function extractDefinition($definition){
        preg_match_all(self::ARRAY_ELEMENT_PATTERN, $definition, $matches);


        $t = $matches[2][1];
        $tokenName = $matches[1][2];
        $variableName = $matches[2][2];

        return array($t, $tokenName, $variableName);
    }


    /**
     * Get token from html source
     * @param $html
     * @param $variableName
     * @return string
     */
    private function getToken($html, $variableName) {
        $pattern = sprintf(self::ARRAY_ELEMENT_PATTERN, $variableName);
        preg_match($pattern, $html, $matches);

        return $matches[1];
    }


    /**
     *
     * Get data for the input time, token
     *
     * @param $packageName
     * @param $t
     * @param $tokenName
     * @param $token
     * @return mixed
     */
    private function getData($packageName, $t, $tokenName, $token) {
        $data = array(
            "packagename" => $packageName,
            "t" => $t,
            $tokenName => $token,
            "fetch" => false
        );

        $headers = array(
            'Content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'origin: https://apps.evozi.com',
            "referer: https://apps.evozi.com/apk-downloader/?id=$packageName",
            'user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36'
        );

        $options = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => implode("\r\n", $headers),
                'content' => http_build_query($data)
            )
        );

        $context  = stream_context_create($options);

        $jsonContent = file_get_contents('https://api-apk.evozi.com/download', false, $context);
        $result = json_decode($jsonContent);

        return $result;
    }


}