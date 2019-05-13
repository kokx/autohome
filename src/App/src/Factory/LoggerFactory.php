<?php

namespace App\Factory;

use Monolog\Logger;
use Monolog\Handler\BrowserConsoleHandler;

class LoggerFactory
{

    public function __invoke()
    {
        $logger = new Logger('autohome');

        if (PHP_SAPI !== 'cli') {
            // in web, log to browser console
            $logger->pushHandler(new BrowserConsoleHandler());
        }

        return $logger;
    }
}
