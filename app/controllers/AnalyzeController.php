<?php

use Phalcon\Mvc\Controller;

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
        
        
        $this->view->kpi = ['a', '2', 4];
    }
}

