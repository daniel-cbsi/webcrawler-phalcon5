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
        $matches = [];
        
        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
        if(preg_match_all("/$regexp/siU", $this->htmlContent, $matches)) {
            foreach ($matches[2] as $key=>$link) {
                if ($link[0] == '#' || $link == '/')
                    unset($matches[2][$key]);
            }
            
            $links = array_unique($matches[2]);
            // $matches[3] = links innerHTML
        }
        
        return $links;
    }
    
    /**
     * Returns internal links array found
     *  in the HTML content
     * 
     * @return array    Array of internal links
     * @param int $number   Number of links to retrieve
     * @param boolean  $random  Randomize selection of links
     */
    public function getInternal(int $number, $random = false): array
    {
        $internal_links = [];
        
        foreach ($this->getAllUnique() as $link) {
            
            // TODO: here should be more options for possible internal links, 
            //       e.g. when it starts with a domain name
            //            when it doesn't start with http word
            if ($link[0] == '/') {
                $internal_links[] = $link;
            }
        }
        
        $internal_links = array_chunk($internal_links, $number)[0] ?? [];
        Log::trace()->debug('INTERNAL LINKS: ' . print_r($internal_links, true));
        
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
    public function getExternal(int $number, $random = false): array
    {
        return [];
    }
}

