<?php

namespace WebAnalytics;


/**
 * This class is intended to operate links
 *   in an HTML content
 * 
 * @author Dan Sazonov
 *
 */
class Links
{
    /**
     * Current HTML content to work with
     * 
     * @var string
     */
    private $htmlContent = null;
    
    /**
     * Adds crawled results to the holder
     * 
     * @return Links
     * @param string $html_content   HTML content
     */
    public function setContent($html_content): Links
    {
        $this->htmlContent = $html_content;
        return $this;
    }
    
    /**
     * Returns current HTML content
     * 
     * @return string   Current HTML content
     */
    public function getContent()
    {
        return $this->htmlContent;
    }
    
    /**
     * Returns all unique links found as array
     *   in the HTML content
     *   
     * @return array  An array with all possible links found
     */
    public function getAllUnique(): array
    {
        $links   = [];
        
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($this->htmlContent);
        libxml_clear_errors();
        
        $dom->preserveWhiteSpace = false;
        foreach ($dom->getElementsByTagName('a') as $image) {
            $links[] = $image->getAttribute('href');
        }
        
        return array_unique($links);
    }
    
    /**
     * Returns internal links array found
     *  in the HTML content
     * 
     * @return array    Array of internal links
     * @param int $number   Number of links to retrieve
     * @param boolean  $random  Randomize selection of links
     */
    public function getInternal(int $number = -1, $random = false): array
    {
        $internal_links = [];
        
        foreach ($this->getAllUnique() as $link) {
            //TODO: more options possible
            if ($link[0] == '/' 
                && strlen($link) > 1
            ) {
                $internal_links[] = $link;
            }
        }
        
        if ($number != -1) {
            $internal_links = array_chunk($internal_links, $number)[0] ?? [];
        }
        
        Log::trace()->debug('INTERNAL LINKS count: ' . count($internal_links));
        
        return $internal_links;
    }
    
    /**
     * Returns external links array found
     *  in the HTML content
     *
     * @return array    Array of external links
     * @param int $number   Number of links to retrieve
     * @param boolean  $random  Randomize selection of links
     */
    public function getExternal(int $number = -1, $random = false): array
    {
        $external_links = [];
        
        foreach ($this->getAllUnique() as $link) {
            //TODO: more options possible
            if (substr($link, 0, 4) == 'http') {
                $external_links[] = $link;
            }
        }
        
        if ($number != -1) {
            $external_links = array_chunk($external_links, $number)[0] ?? [];
        }
        
        Log::trace()->debug('INTERNAL LINKS count: ' . count($external_links));
        
        return $external_links;
    }
}

