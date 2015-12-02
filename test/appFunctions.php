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
use Blog\Config;
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
//
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
//
//function createCaching()
//{
//    return new \Room11\Caching\LastModified\Revalidate(3600, 1200);
//}
//
//
///**
// * @param ArtaxClient $client
// * @param \Amp\Reactor $reactor
// * @param ResponseCache $cache
// * @return GithubService
// */
//function createGithubArtaxService(ArtaxClient $client, \Amp\Reactor $reactor, ResponseCache $cache)
//{
//    return new GithubService($client, $reactor, $cache, "Danack/Tier");
//}
//
//
//function createScriptInclude()
//{
//    $packScript = getEnvWithDefault('imagickdemo.packscript', 1);
//    if ($packScript) {
//        return new Intahwebz\Utils\ScriptIncludePacked();
//    }
//    else {
//        return new Intahwebz\Utils\ScriptIncludeIndividual();
//    }
//}

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


//function correctUmask($filename)
//{
//    $umask = umask();
//    $correctMode = ( 0777 - $umask);
//
//    return chmod($filename, $correctMode);
//}
//    
//function saveTmpFile($tmpName, $destFilename)
//{
//    renameMultiplatform($tmpName, $destFilename);
//    correctUmask($destFilename);
//    //@unlink($tmpName);
//}
//
//function getTemplates($directory)
//{
//    $srcPath = realpath($directory);
//
//    $objects = new \RecursiveIteratorIterator(
//        new \RecursiveDirectoryIterator($srcPath),
//        \RecursiveIteratorIterator::SELF_FIRST
//    );
//
//    $templateObjects = new \RegexIterator($objects, '#.*\.tpl#');
//
//    $templates = [];
//    foreach ($templateObjects as $key => $var) {
//        $templateName = str_replace(
//            [".tpl", $srcPath.'/'],
//            '',
//            $var->getRealPath()
//        );
//        $templates[$templateName] = $templateName;
//    }
//
//    return $templates;
//}
//
//
//function createTemplateList()
//{
//    $srcPath = __DIR__."/../templates/";
//    $templates = getTemplates($srcPath);
//
//    return new TemplateList($templates);
//}
//
///* Stops passwords from being put into log files.
// * Need to make it generate valid php arrays so make life easier.
// */
//function dump_table($var)
//{
//    $forbiddenKeys = array(
//        //'password',
//    );
//
//    if (is_array($var) or is_object($var)) {
//        foreach ($var as $key => $value) {
//            if (is_array($value) or is_object($value)) {
//                dump_table($value);
//            }
//            else {
//                if (in_array($key, $forbiddenKeys) == true) {
//                    $value = '********';
//                }
//                echo "'$key' => '$value' ";
//            }
//        }
//    }
//    else {
//        echo "'$var' ";
//    }
//}
//
//
//function getVar_DumpOutput($response)
//{
//    ob_start();
//    dump_table($response);
//    $obContents = ob_get_contents();
//    ob_end_clean();
//
//    return $obContents;
//}

/**
 * Helper function to bind the route list to FastRoute
 * @param \FastRoute\RouteCollector $r
 */
function routesFunction(FastRoute\RouteCollector $r)
{
//    $r->addRoute('GET', "/css/{cssInclude}", ['ScriptServer\Controller\ScriptServer', 'getPackedCSS']);
//    $r->addRoute('GET', '/js/{jsInclude}', ['ScriptServer\Controller\ScriptServer', 'getPackedJavascript']);
    $r->addRoute('GET', '/login', ['Blog\Controller\Login', 'loginGet']);
    $r->addRoute('POST', '/login', ['Blog\Controller\Login', 'loginPost']);
    
    $r->addRoute('GET', '/', ['FCFormsTest\Controller\Example', 'index']);

    $r->addRoute('GET', '/list', ['FCFormsTest\Controller\Example', 'listExample']);
}

//function routeIndex()
//{
//    return "/";
//}
//
//function ensureAbsoluteFilename($filename)
//{
//    $filename = str_replace("..", "", $filename);
//    $filename = str_replace("/", "", $filename);
//    $filename = str_replace("\\", "", $filename);
//    return $filename;
//}
//
///**
// * @param $filename
// * @param string $size
// * @return string
// */
//function urlStaticImage($filename, $size = 'original')
//{
//    $imageName = $filename;
//    $sizeString = $size;
//    return "/staticImage/".$sizeString."/".urlencode($imageName);
//}
//
///**
// * @param $imageFilename
// * @param $size
// * @param string $float
// * @param bool $description
// * @return string
// */
//function articleImage($imageFilename, $size, $float = 'left', $description = false)
//{
//    $output = '';
//    $marginClass = '';
//    if ($float == 'left') {
//        $marginClass = 'articleMarginFloatLeft';
//    }
//    if ($float == 'right') {
//        $marginClass = 'articleMarginFloatRight';
//    }
//    $output .= "<div class='articleImage $marginClass' style='float: $float;'>";
//    $thumbnailURL = urlStaticImage($imageFilename, $size);
//    $fullImageURL = urlStaticImage($imageFilename);
//    $output .= "<a href='$fullImageURL' target='_blank' class='plainLink'>";
//    $output .= "<img src='$thumbnailURL'/> ";
//    //Size could actually just be setting the height - which would be annoying.
//    //So we don't support that.
//    $width = intval($size);
//    if ($description != false) {
//        $output .= "<br/>";
//        $output .= "<div style='width: ".$width."px'>";
//        $output .= $description;
//        $output .= "</div>";
//    }
//    $output .= "</a></div>";
//
//    return $output;
//}
//
//
//function routeBlogPost($blogPostID)
//{
//    return sprintf('/blog/%d', $blogPostID);
//}
//
//function routeDraft($draftFilename)
//{
//    return sprintf('/draft/%s', $draftFilename);
//}
//
//
//function routeBlogPostWithFormat($blogPostID, $format)
//{
//    return sprintf('/blog/%d.%d', $blogPostID, $format);
//}
//
//function routeJSInclude($url)
//{
//    return "/js/".$url;
//}
//
//function routeBlogEdit($blogPostID)
//{
//    return "/blogedit/".$blogPostID;
//}
//
//function routeBlogReplace($blogPostID)
//{
//    return "/blogreplace/".$blogPostID;
//}
//

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

//function createUserPermissions(Session $session)
//{
//    $role = $session->getSessionVariable(\BaseReality\Content\BaseRealityConstant::$userRole);
//    
//    if ($role == false) {
//        return new \Blog\User\AnonymousPermissions();
//    }
//    
//    return new \Blog\User\LoggedInPermissions($role);
//}
