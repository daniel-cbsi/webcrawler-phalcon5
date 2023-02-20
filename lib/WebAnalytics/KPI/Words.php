<?php
namespace WebAnalytics\KPI;

use WebAnalytics;

/**
 * This class calculates Words KPI
 *  - average words count
 * 
 * 
 * @author Dan Sazonov
 *
 */
class Words
{
    
    /**
     * Returns an average words count
     * 
     * @return int  
     * @param WebAnalytics\Results $results   Results of crawling
     */
    public function getAvgCount(WebAnalytics\Results $results): int
    {
        $avg_number  = 0;
        $total_words = 0;
        $all_results = $results->get();
        
        foreach ($all_results as $result) {
            $total_words += count(self::getFromContent($result['content']));
        }
        
        $avg_number = $total_words / count($all_results);
        
        return $avg_number;
    }
    
    /**
     * Returns array of all words from the content
     * 
     * @return array   An array of words
     * @param string $html_content  And HTML content to seek through
     */
    static public function getFromContent($html_content): array
    {
        $words     = [];
        $raw_words = [];
        $seek_in   = [
            'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'a', 'button', 'li'
        ];
        
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html_content);
        libxml_clear_errors();
        $dom->preserveWhiteSpace = false;
        
        foreach ($seek_in as $tag) {
            foreach ($dom->getElementsByTagName($tag) as $element) {
                $raw_words = array_merge(
                    $raw_words, 
                    explode(' ', strip_tags($element->nodeValue))
                );
            }
        }
        
        foreach ($raw_words as $word) {
            if (strlen($word) > 0 && $word != ' ') {
                $words[] = $word;
            }
        }
        
        return $words;
    }
}

