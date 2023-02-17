<?php

namespace WebAnalytics;

use Phalcon\Logger\Logger;
use Phalcon\Logger\Adapter\Stream;

/**
 * This calss is intended to utilize 
 *   framework native logging functionality
 * 
 * @author Dan Sazonov
 *
 */
class Log
{
    /**
     * Log singleton holder
     * 
     * @var Logger
     */
    private static $logger = null;
    
    /**
     * This static returns an instance of
     *    gframework native log
     * 
     * @return string
     */
    static public function trace(): Logger
    {
        if (self::$logger != null) {
            return self::$logger; 
        }
        
        self::$logger  = new Logger(
            'messages',
            ['main' => new Stream(BASE_PATH . '/log/webcrawler.general.log')]
        );
        
        return self::$logger;
    }
}

