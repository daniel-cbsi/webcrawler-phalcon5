<?php
namespace WebAnalytics\KPI;

use WebAnalytics;

/**
 * This class calculates Titles KPI
 *  - average title length
 * 
 * 
 * @author Dan Sazonov
 *
 */
class Titles
{
    
    /**
     * Returns an average length of a title
     * 
     * @return int  
     * @param WebAnalytics\Results $results   Results of crawling
     */
    public function getAvgLength(WebAnalytics\Results $results): int
    {
        $avg_length = 0;
        $all_titles = [];
        
        foreach ($results->get() as $result) {
            $all_titles = array_merge($all_titles, self::getFromContent($result['content']));
        }
        
        $avg_length = strlen(implode(null, $all_titles)) / count($all_titles);
        
        return (int)$avg_length;
    }

    /**
     * Returns array of innerHTML from all <h...> tags found
     *
     * @return array   An array of words
     * @param string $html_content  And HTML content to seek through
     */
    static public function getFromContent($html_content): array
    {
        $titles    = [];
        $seek_in   = [
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
        ];
    
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html_content);
        libxml_clear_errors();
        $dom->preserveWhiteSpace = false;
    
        foreach ($seek_in as $tag) {
            foreach ($dom->getElementsByTagName($tag) as $element) {
                $titles[] = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", strip_tags($element->nodeValue))));
            }
        }
    
        return $titles;
    }
}

