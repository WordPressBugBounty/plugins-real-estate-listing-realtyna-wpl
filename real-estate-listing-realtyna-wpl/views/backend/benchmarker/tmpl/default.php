<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.css');

if ( isset( $_GET['run_tests'] ) || isset( $_GET['update'] )  || isset( $_GET['home'] )  ){
	_wpl_import($this->tpl_path.'.benchmarker');
}else{
	_wpl_import($this->tpl_path.'.main');
}

$this->_wpl_import($this->tpl_path.'.scripts.js');

?>