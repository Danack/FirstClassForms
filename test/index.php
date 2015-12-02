<?php


use Tier\TierHTTPApp;
use Tier\Executable;
use Room11\HTTP\Request\CLIRequest;

require_once __DIR__.'/../vendor/autoload.php';

// Contains helper functions for the 'framework'.
require __DIR__."/../vendor/danack/tier/src/Tier/tierFunctions.php";

// Contains helper functions for the application.
require_once "appFunctions.php";

\Tier\setupErrorHandlers();

// Read application config params
$injectionParams = require_once "injectionParams.php";

if (strcasecmp(PHP_SAPI, 'cli') == 0) {
    $request = new CLIRequest('/');
}
else {
    $request = \Tier\createRequestFromGlobals();
}

// Create the first Tier that needs to be run.
$executable = new Executable('routeRequest', null, null, 'Room11\HTTP\Body');

// Create the Tier application
$app = new TierHTTPApp($injectionParams);

// Make the body that is generated be shared by TierApp
$app->addExpectedProduct('Room11\HTTP\Body');

// Check to see if a form has been submitted, and we need to do 
// a POST/GET redirect
$app->addPreCallable(['FCForms\HTTP', 'processFormRedirect']);

$app->addGenerateBodyExecutable($executable);
$app->addBeforeSendCallable('addSessionHeader');
$app->addSendCallable('Tier\sendBodyResponse');

$app->createStandardExceptionResolver();

// Run it
$app->execute($request);
