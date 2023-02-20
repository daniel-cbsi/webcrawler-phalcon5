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
        return count($results->get());
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
        $avgload_sec = 0;
        $total_time  = 0;
        $all_results = $results->get();
        
        foreach ($all_results as $result) {
            $total_time += (float)$result['transferTime'];
        }
        
        $avgload_sec = $total_time / count($all_results);
    
        return $avgload_sec;
    }
}

