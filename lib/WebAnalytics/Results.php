<?php

namespace WebAnalytics;


/**
 * Web Crawling results holder and processor
 * 
 * @author Dan Sazonov
 *
 */
class Results
{
    /**
     * Data crawled holder
     * 
     *  [
     *   'url' => '',
     *   'statusCode' => '',
     *   'loadTime' => '',
     *   'content' => ''
     *  ]
     * 
     * @var array
     */
    private array $dataMatrix = [];
    
    /**
     * Adds crawled results to the holder
     * 
     * @return Results
     * @param array $results   Results of crawling
     */
    public function add(array $results): Results
    {
        $this->dataMatrix[] = $results;
        return $this;
    }
    
    /**
     * Returns crawled results
     * 
     * @return array   Array of stored results
     * @param int $position  A position in the results
     */
    public function get($position = -1): array
    {
        if ($position < 0){
            return $this->dataMatrix;
        } 
        
        return $this->dataMatrix[$position] ?? [];
    }
}

