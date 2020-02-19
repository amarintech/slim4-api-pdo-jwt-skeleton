<?php



$app->add(new Tuupola\Middleware\JwtAuthentication([
    "path" => ["/secure"],
    "ignore" => ["/token"],
    "secret" => "123456789helo_secret",
    "secure" => false,
    "error" => function ($response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));

$url = $_SERVER['SERVER_NAME'];
$mode = 'development';
if (strpos($url, 'xyz') !== false) {
    $mode = 'production';
}
if (strpos($url, 'com') !== false) {
    $mode = 'production';
}
if ($mode == 'production') {
    $path_tmp = '/var/www/tmp';
    $cache = "ssdb";
    //production
} else {
    $path_tmp = 'tmp';
    $cache = 'files';
    //development
}
