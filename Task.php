<?php
/**
 * Created by PhpStorm.
 * User: banhtieu
 * Date: 8/12/2015
 * Time: 12:12 AM
 */

$package_name = $_GET["p"];

$html = file_get_contents("https://apps.evozi.com/apk-downloader/?id=$package_name");
$pattern = "/\\{packagename:[^\\}]+\\}/";

preg_match($pattern, $html, $matches);

$result = $matches[0];
var_dump($result);

$pattern = "/(\\b[^:]+):\\s+([^,]+),/";
preg_match_all($pattern, $result, $matches);


$t = $matches[2][1];
$token_name = $matches[1][2];
$variable_name = $matches[2][2];

var_dump($t);
var_dump($token_name);
var_dump($variable_name);



$pattern = "/var\\s+$variable_name\\s*=\\s*'([^']*)'/";
preg_match($pattern, $html, $matches);

$token = $matches[1];

var_dump($token);
$data = array(
    "packagename" => $package_name,
    "t" => $t,
    $token_name => $token,
    "fetch" => false
);

$headers = array(
    'Content-type: application/x-www-form-urlencoded; charset=UTF-8',
    'origin: https://apps.evozi.com',
    "referer: https://apps.evozi.com/apk-downloader/?id=$package_name",
    'user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36'
);

$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => implode("\r\n", $headers),
        'content' => http_build_query($data)
    )
);

$context  = stream_context_create($opts);

$result = file_get_contents('https://api-apk.evozi.com/download', false, $context);
$url = $result["url"];

?>
<a href="<?php echo $url; ?>">Download</a>
