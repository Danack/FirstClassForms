<?php

use Amp\Artax\Client as ArtaxClient;
use ArtaxServiceBuilder\ResponseCache;
use Auryn\Injector;
use Jig\JigConfig;
use Tier\Executable;
use Tier\InjectionParams;
use GithubService\GithubArtaxService\GithubService;
use Blog\Data\TemplateList;

use Room11\HTTP\Request;
use Room11\HTTP\Response;
use Room11\HTTP\Body;
use Room11\HTTP\VariableMap;
use FCForms\Form\Form;
use Room11\HTTP\HeadersSet;
//use Blog\Config;
use Room11\HTTP\Body\RedirectBody;
use ASM\Session;
use ASM\SessionConfig;
use ASM\SessionManager;
use Tier\TierApp;

/**
 * Read config settings from environment with a default value.
 * @param $env
 * @param $default
 * @return string
 */
function getEnvWithDefault($env, $default)
{
    $value = getenv($env);
    if ($value === false) {
        return $default;
    }
    return $value;
}

//function createUploadedFileFetcher()
//{
//    return new \Intahwebz\Utils\UploadedFileFetcher($_FILES);
//}
//
//function createS3Config(Config $config) {
//
//    $key = $config->getKey(Config::AWS_SERVICES_KEY);
//    $value = $config->getKey(Config::AWS_SERVICES_SECRET);
//    
//    return new \Intahwebz\S3Bridge\S3Config($key, $value);
//}

/**
 * @return JigConfig
 */
function createJigConfig()
{
    $jigConfig = new JigConfig(
        __DIR__."/templates/",
        __DIR__."/../var/compile/",
        'tpl',
        getEnvWithDefault('jig.compile', \Jig\Jig::COMPILE_ALWAYS)
    );
    
    return $jigConfig;
}


/**
 * The callable that routes a request.
 * @param Response $response
 * @return Tier
 */


function routeRequest(Request $request, Response $response)
{
    $dispatcher = FastRoute\simpleDispatcher('routesFunction');
    $httpMethod = $request->getMethod();
    $uri = $request->getPath();

    $queryPosition = strpos($uri, '?');
    if ($queryPosition !== false) {
        $uri = substr($uri, 0, $queryPosition);
    }

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

    switch ($routeInfo[0]) {
        case (FastRoute\Dispatcher::NOT_FOUND): {
            $response->setStatus(404);
            return \Tier\getRenderTemplateTier('error/error404');
        }

        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED: {
            // TODO - this is meant to set a header saying which methods
            // are allowed
            $allowedMethods = $routeInfo[1];
            $response->setStatus(405);
            return \Tier\getRenderTemplateTier('error/error405');
        }

        case FastRoute\Dispatcher::FOUND: {
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            $params = InjectionParams::fromParams($vars);

            return new Executable($handler, $params);
        }

        default: {
            //Not supported
            // TODO - this is meant to set a header saying which methods
            $response->setStatus(404);
            return \Tier\getRenderTemplateTier('error/error404');
            break;
        }
    }
}

function redirectToGet(Request $request)
{
    return new RedirectBody("Form missing", $request->getPath(), 303);
}


/**
 * Helper function to bind the route list to FastRoute
 * @param \FastRoute\RouteCollector $r
 */
function routesFunction(FastRoute\RouteCollector $r)
{
    $r->addRoute('GET', '/login', ['Blog\Controller\Login', 'loginGet']);
    $r->addRoute('POST', '/login', ['Blog\Controller\Login', 'loginPost']);
    
    
    $r->addRoute('GET', '/', ['FCFormsTest\Controller\Example', 'index']);
    $r->addRoute('GET', '/list', ['FCFormsTest\Controller\Example', 'listExample']);
    $r->addRoute('GET', '/signup', ['FCFormsTest\Controller\Example', 'signupExample']);
    $r->addRoute('GET', '/file', ['FCFormsTest\Controller\Example', 'fileExample']);
    
    
    
    
    
    $r->addRoute('POST', '/list', 'redirectToGet');
}


//function ensureAbsoluteFilename($filename)
//{
//    $filename = str_replace("..", "", $filename);
//    $filename = str_replace("/", "", $filename);
//    $filename = str_replace("\\", "", $filename);
//    return $filename;
//}



function createASMFileDriver()
{
    return new \ASM\File\FileDriver(__DIR__."/../var/session/");
}

/**
 * @param \ASM\Redis\RedisDriver $redisDriver
 * @return \ASM\Session
 */
function createSession(\ASM\Driver $driver)
{
    $sessionConfig = new SessionConfig(
        'SessionTest',
        1000,
        10
    );

    $sessionManager = new SessionManager(
        $sessionConfig,
        $driver
    );

    $session = $sessionManager->createSession($_COOKIE);

    return $session;
}

/**
 * @param Session $session
 * @param HeadersSet $headerSet
 */
function addSessionHeader(Session $session, HeadersSet $headerSet)
{
    $session->save();
    $headers = $session->getHeaders(\ASM\SessionManager::CACHE_PRIVATE);
    foreach ($headers as $key => $value) {
        $headerSet->addHeader($key, $value);
    }

    return TierApp::PROCESS_CONTINUE;
}
