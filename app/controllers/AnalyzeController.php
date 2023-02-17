<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;

//Custom library
use WebAnalytics\{WebCrawler, Log, Results, Links};
use WebAnalytics\KPI\{Pages, Images, Links as KpiLinks, Words, Titles};

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
        $numpages = $request->getPost('numpages');
        
        Log::trace()->debug('START ANALYTICS.');
        Log::trace()->debug('STEP_1: Domain: '. $domain .' and number of pages is: ' . $numpages);
        
        $results = new Results();
        $results->add(WebCrawler::init($domain)->crawl());
        
        $links = new Links();
        $links->setContent($results->get(0)['content']);
        $internal_links = $links->getInternal($numpages);
        
        Log::trace()->debug('STEP_2: Unique internal links count: '. count($internal_links));
        
        foreach ($internal_links as $int_link_url) {
            $results->add(WebCrawler::init()->crawl(WebCrawler::$domainName.'/'.$int_link_url));
        }
        
        Log::trace()->debug('STEP_3: Calculating KPIs.');
        /*
        $this->view->kpi = [
            'numPages'     => Pages::getNumberCrawled($results),
            'avgLoad'      => Pages::getAvgLoad($results),
            'uniqImgNum'   => Images::getNumOfUnique($results),
            'uniqExtLinks' => Links::getNumOfUniqExt($results),
            'uniqIntLinks' => Links::getNumOfUniqInt($results),
            'avgWordCount' => Words::getAvgCount($results),
            'avgTitLength' => Titles::getAvgLength($results)
        ];
        
        Log::trace()->debug('FINISH ANALYTICS.');
        
        $this->view->urlStats = $results->get();
        /**/
    }
    
    
}

