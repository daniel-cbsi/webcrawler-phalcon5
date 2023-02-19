<?php
namespace WebAnalytics\KPI;

use WebAnalytics;

/**
 * This class calculates Pages KPI
 *  - number of pages analyzed,
 *  - average time of page HTML transfer
 * 
 * 
 * @author Dan Sazonov
 *
 */
class Pages
{
    
    /**
     * Returns a number of pages analyzed
     * 
     * @return int  
     * @param WebAnalytics\Results $results   Results of crawling
     */
    public function getNumberCrawled(WebAnalytics\Results $results): int
    {
        $number = 0;
        
        
        return $number;
    }
    
    /**
     * Returns an average page transfer time
     *   in seconds
     *
     * @return float
     * @param WebAnalytics\Results $results   Results of crawling
     */
    public function getAvgLoad(WebAnalytics\Results $results): float
    {
        $number = 0;
    
    
        return $number;
    }
}

