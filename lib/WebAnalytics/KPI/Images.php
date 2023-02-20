<?php
namespace WebAnalytics\KPI;

use WebAnalytics;

/**
 * This class calculates Images KPI
 *  - number of unique images. Calculates out of an HTML content
 * 
 * @author Dan Sazonov
 *
 */
class Images
{
    
    /**
     * Returns a number of unique images
     * 
     * @return int  
     * @param WebAnalytics\Results $results   Results of crawling
     */
    static public function getNumOfUnique(WebAnalytics\Results $results): int
    {
        $number = 0;
        
        foreach (self::getAllSources($results) as $src) {
            $src = $src[0] == '/' ? WebAnalytics\WebCrawler::$domainName.$src : $src;
            
            try {
                $checksum = md5(file_get_contents($src));
                $images[$checksum] = $src;
            } catch (\Exception $e) {
                WebAnalytics\Log::trace()->error('Image couldn\'t be opened due to: ', $e->getMessage());
            }
        }
            
        WebAnalytics\Log::trace()->debug(count($images) . ' of unique images found.');
        //WebAnalytics\Log::trace()->debug('UNIQ_IMAGES: ' . print_r($images, true));
        
        $number = count($images);
        
        return $number;
    }
    
    /**
     * Returns all images sources as array
     * 
     * @return array   
     * @param WebAnalytics\Results $results   Results of crawling
     */
    static private function getAllSources(WebAnalytics\Results $results): array
    {
        $sources = [];
        WebAnalytics\Log::trace()->debug('Building list of image sources. Count of Pages: ' . count($results->get()));
        
        foreach ($results->get() as $key=>$result) {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($result['content']);
            //WebAnalytics\Log::trace()->debug('Seeking SRCs in: ' . $result['content']);
            libxml_clear_errors();
                        
            $dom->preserveWhiteSpace = false;
            foreach ($dom->getElementsByTagName('img') as $image) {
                $sources[] = $image->getAttribute('src');
            }
        }
        
        $sources = array_unique($sources);
        
        //WebAnalytics\Log::trace()->debug('List of image sources prepared. Count of sources: ' . count($sources));
        return $sources;
    }
}

