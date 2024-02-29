<?php

use App\Kernel;
 
/*
require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
}; */


use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);

    if ($context['APP_DEBUG']) {
        umask(0000);

        Debug::enable();
    }

    $request = Request::createFromGlobals();
    $response = $kernel->handle($request);
    $response->send();

    $kernel->terminate($request, $response);
};

