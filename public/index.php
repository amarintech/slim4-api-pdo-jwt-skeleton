<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use \Interop\Container\ContainerInterface as ContainerInterface;
use DI\Container;
require __DIR__ . '/../vendor/autoload.php';
$container = new Container();
require '../app/container.php';
// Set container to create App with on AppFactory
AppFactory::setContainer($container);
$app = AppFactory::create();

require '../app/start.php';

use Firebase\JWT\JWT;
use \Model\Meta as Meta;
use \Model\Tools as Tools;
use \Model\Api as Api;
use Tuupola\Base62;


$app->post("/token", function (Request $request, Response $response, $args){
$Api = new Api();

/* Here generate and return JWT to the client. */
//$valid_scopes = ["read", "write", "delete"]
$requested_scopes = $request->getParsedBody() ?: [];

if(!$Api->UserAuth($requested_scopes['email'],$requested_scopes['password'])){
    $data["error"] = 'Incorrect Login/Password';
}
else {

$now = new DateTime();
$future = new DateTime("+10 minutes");
$server = $request->getServerParams();
$jti = (new Base62)->encode(random_bytes(16));
$payload = [
"iat" => $now->getTimeStamp(),
"exp" => $future->getTimeStamp(),
"jti" => $jti
];
$secret = "123456789helo_secret";
$token = JWT::encode($payload, $secret, "HS256");
$data["token"] = $token;
$data["expires"] = $future->getTimeStamp();
}
$body = $response->getBody();
$body->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
return $response->withStatus(201)
->withHeader("Content-Type", "application/json")
->withBody($body);
});


$app->get("/secure", function ($request, $response, $args) {
$data = ["status" => 1, 'msg' => "This route is secure!"];
$body = $response->getBody();
$body->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
return $response->withStatus(201)
->withHeader("Content-Type", "application/json")
->withBody($body);
});

$app->get("/not-secure", function ($request, $response, $args) {

$data = ["status" => 1, 'msg' => "No need of token to access me"];
$body = $response->getBody();
$body->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
return $response->withStatus(201)
->withHeader("Content-Type", "application/json")
->withBody($body);
});

$app->post("/formData", function ($request, $response, $args) {
$data = $request->getParsedBody();
$result = ["status" => 1, 'msg' => $data];
// Request with status response
$body = $response->getBody();
$body->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
return $response->withStatus(201)
->withHeader("Content-Type", "application/json")
->withBody($body);
});

// Define Custom Error Handler
$customErrorHandler = function (

    //ServerRequestInterface $request,
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $payload = ['error' => $exception->getMessage()];

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(
        json_encode($payload, JSON_UNESCAPED_UNICODE)
    );

    return $response;
};

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);
$app->run();
