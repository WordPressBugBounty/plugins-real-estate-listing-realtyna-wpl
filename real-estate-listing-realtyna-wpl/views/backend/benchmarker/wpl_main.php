<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_benchmarker_controller extends wpl_controller
{
    
	public $tpl_path = 'views.backend.benchmarker.tmpl';
    public $tpl;

    public function home()
    {
        wpl_global::min_access('administrator');
		parent::render($this->tpl_path, $this->tpl);
    }
	
    public function wizard()
    {
    }
	
}