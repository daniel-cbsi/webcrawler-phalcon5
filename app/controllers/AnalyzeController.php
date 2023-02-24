<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;

//Custom library
use WebAnalytics\{WebCrawler, Log, Results, Links};
use WebAnalytics\KPI;

/**
 * This class is the web crawl action controller 
 * 
 * @author Dan Sazonov
 *
 */
class AnalyzeController extends Controller
{
    /**
     * Entry point logic processor
     * 
     * @return string
     */
    public function indexAction()   
    {
        $request = new Request();
        
        $domain   = $request->getPost('domain');
        $url_path = $request->getPost('url_path');
        $numpages = abs($request->getPost('numpages'));
        
        Log::trace()->debug('START ANALYTICS.');
        Log::trace()->debug('STEP_1: Domain: '. $domain.$url_path .' and number of internal pages is: ' . $numpages);
        
        $results = new Results();
        $results->add(WebCrawler::init($domain)->crawl(WebCrawler::$domainName . $url_path));
        
        $links = new Links();
        $links->setContent($results->get(0)['content']);
        $internal_links = $links->getInternal($numpages);
        
        Log::trace()->debug('STEP_2: Unique internal links count: '. count($internal_links));
        
        foreach ($internal_links as $int_link_url) {
            $results->add(WebCrawler::init()->crawl(WebCrawler::$domainName.'/'.$int_link_url));
        }
        
        Log::trace()->debug('STEP_3: Calculating KPIs.');
        
        $this->view->kpi = [
            'numPages'     => KPI\Pages::getNumberCrawled($results),
            'avgLoad'      => KPI\Pages::getAvgLoad($results),
            'uniqImgNum'   => KPI\Images::getNumOfUnique($results),
            'uniqExtLinks' => KPI\Links::getNumOfUniqExt($results),
            'uniqIntLinks' => KPI\Links::getNumOfUniqInt($results),
            'avgWordCount' => KPI\Words::getAvgCount($results),
            'avgTitLength' => KPI\Titles::getAvgLength($results)
        ];
        
        Log::trace()->debug('FINISH ANALYTICS.');
        
        $this->view->urlStats = $results->get();
    }
    
    
}

