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
    static public function init($domain = null): WebCrawler
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
    public function crawl($passed_url = null): array
    {
        $url = $passed_url ?? WebCrawler::$domainName;
        
        $status       = $this->getStatus($url);
        $html_content = $this->scrapeContent($url);
        
        return [
            'url'          => $url,
            'statusCode'   => $status['statusCode'],
            'transferTime' => $status['transferTime'],
            'content'      => $html_content
        ];
    }    
    
    /**
     * Returns status on a URL passed
     * 
     *  [
     *    'url' => 'https://agencyanalytics.com/',
     *    'statusCode' => '200',
     *    'transferTime' => '0.9444523'
     *  ]
     * 
     * @return array   A set of status params
     * @param string $url  The URL of the page to crawl
     */
    public function getStatus($url): array
    {   
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($curl);
        
        if (curl_error($curl)){
            Log::trace()->error('URL '.$url.' couldn\'t be crawled due to: ' . curl_error($curl));
            return [];
        }
        
        $status_code   = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $transfer_time = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
        
        Log::trace()->debug('URL '.$url.' was crawled. StatusCode: '. $status_code .'. TransferTime: '. $transfer_time);
        
        curl_close($curl);
        
        return [
            'url'          => $url,
            'statusCode'   => $status_code,
            'transferTime' => $transfer_time
        ];
    }
    
    /**
     * Scrapes HTML content from URL
     *
     * @return string
     * @param string $passed_url  The URL of the page to crawl
     */
    public function scrapeContent($passed_url = null)
    {
        $url  = $passed_url ?? WebCrawler::$domainName;
    
        curl_setopt_array($curl = curl_init(), $this->getCurlSettings($url));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err){
            Log::trace()->error('URL '.$url.' couldn\'t be crawled due to: ' . $err);
            return [];
        }
        
        return $response;
    }
    
    /**
     * Returns an array of cURL settings
     *  - Currently WebScrapingApi is used
     * 
     * @return array   cURL settings
     * @param string $url   A URL to request
     */
    private function getCurlSettings($url): array
    {
        $api_key = '36yICX4hjnL28517tFKOYBSMjcQvsxki';
        
        return [
            CURLOPT_URL => 
                'https://api.webscrapingapi.com/v1?api_key='.$api_key
               .'&url='. urlencode($url)
               .'&render_js=1'
               .'&js_instructions='
                   . urlencode('[
                        {"action":"scrollInf","count":"25","timeout": 100}
                   ]'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ];
    }
    
}

