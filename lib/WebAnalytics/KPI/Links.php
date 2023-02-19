<?php
namespace WebAnalytics\KPI;

use WebAnalytics;

/**
 * This class calculates Links KPI.
 * 
 * Calculates out of an HTML content:
 *  - number of unique external links,
 *  - number of unique internal links. 
 * 
 * 
 * @author Dan Sazonov
 *
 */
class Links
{
    
    /**
     * Returns a number of unique external links
     *   found in an HTML content
     * 
     * @return int  
     * @param WebAnalytics\Results $results   Results of crawling
     */
    public function getNumOfUniqExt(WebAnalytics\Results $results): int
    {
        $number = 0;
        
        
        return $number;
    }
    
    /**
     * Returns a number of unique internal links
     *   found in an HTML content
     *
     * @return int
     * @param WebAnalytics\Results $results   Results of crawling
     */
    public function getNumOfUniqInt(WebAnalytics\Results $results): int
    {
        $number = 0;
    
    
        return $number;
    }
}

