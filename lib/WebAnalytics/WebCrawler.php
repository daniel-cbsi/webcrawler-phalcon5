<?php

namespace WebAnalytics;


/**
 * This class is used to retrieve webpages data
 *  - statusCode
 *  - load time
 *  - content
 * 
 * @author Dan Sazonov
 *
 */
class WebCrawler
{
    /**
     * Current Domain name
     *
     * @var array
     */
    static public $domainName = null;
    
    /**
     * Crawler initiation
     * 
     * @return WebCrawler
     * @param string $domain  Current domain name
     */
    static public function init($domain = null) : WebCrawler
    {
        if ($domain != null) {
            self::$domainName = $domain;
        }
        
        return new self();
    }
    
    /**
     * Crawls URL
     * 
     * @param string $passed_url  The URL of the page to crawl
     */
    public function crawl($passed_url = null) : array
    {
        $url = $passed_url ?? WebCrawler::$domainName;
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        
        $html_content = curl_exec($curl);
        
        if (curl_error($curl)){
            Log::trace()->error('URL '.$url.' couldn\'t be crawled due to: ' . curl_error($curl));
            return [];
        }
        
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $load_time   = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
        $real_url    = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL); 
        
        Log::trace()->debug('URL '.$url.' was crawled. StatusCode: '. $status_code .'. LoadTime: '. $load_time);
        
        curl_close($curl);
        
        if ($passed_url == null) {
            WebCrawler::$domainName = $real_url;
        }
        
        return [
            'url'        => $real_url,
            'statusCode' => $status_code,
            'loadTime'   => $load_time,
            'content'    => $html_content
        ];
    }
}

