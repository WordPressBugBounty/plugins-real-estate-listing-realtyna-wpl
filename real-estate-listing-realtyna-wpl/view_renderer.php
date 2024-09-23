<?php

trait ViewRenderer {

    /**
     * Holds all variables that should be passed to view file
     * @var array
     * @author David.M
     */
    protected $viewVars = [];


    protected function showView($___viewAbsolutePath, $___once = false) {
		// for backward-compatibility
		$___viewVars = $this->viewVars;
		if(empty($___viewVars)) {
			foreach (get_object_vars($this) as $__key => $__value) {
				$___viewVars[$__key] = $__value;
			}
		}

		extract($___viewVars, EXTR_SKIP);
		$___once ? include_once $___viewAbsolutePath : include $___viewAbsolutePath;
    }


    /**
     * Sample: [key1 => val1, key2 => val2]
     * @param array $keyValues
     */
	protected function setViewVars($keyValues = []) {
		$this->viewVars = array_merge($this->viewVars, $keyValues);

		// for backward-compatibility
		foreach ($this->viewVars as $key => $value) {
			$this->{$key} = $value;
		}
	}

	protected function setViewVar($key, $value) {
		$this->viewVars[$key] = $value;
		// for backward-compatibility
		$this->{$key} = $value;
	}

    /**
     * For importing internal files in object mode
     * @author Howard <howard@realtyna.com>
     * @param string $include
     * @param boolean $override
     * @param boolean $set_footer
     * @param boolean $once
     * @return void
     */
    protected function _wpl_import($include, $override = true, $set_footer = false, $once = false)
    {
        $path = _wpl_import($include, $override, true);

        /** check exists **/
        if(!wpl_file::exists($path)) return;

        if(!$set_footer) {
            $this->showView($path, $once);
            return;
        }
        ob_start();
        $this->showView($path, $once);
        wpl_html::set_footer(ob_get_clean());
    }
}