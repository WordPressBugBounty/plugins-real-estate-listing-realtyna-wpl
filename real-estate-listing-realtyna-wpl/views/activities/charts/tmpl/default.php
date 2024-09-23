<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

wpl_extensions::import_javascript((object) array('param1'=>'wpl-jqplot-canvasTextRenderer', 'param2'=>'packages/jqplot/plugins/jqplot.canvasTextRenderer.min.js'));
wpl_extensions::import_javascript((object) array('param1'=>'wpl-jqplot-canvasAxisLabelRenderer', 'param2'=>'packages/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js'));

$this->data = $params;

$this->chart_background = ( trim($this->data['chart_background'] ?? '') != '') ? $this->data['chart_background'] : '';
$this->chart_title = ( trim($this->data['chart_title'] ?? '') != '') ? $this->data['chart_title'] : '';

$this->rendered = $this->render_data($this->data['data'], 'line');

/** importing js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.line', true, true);
?>
<div id="chartdiv<?php wpl_esc::attr($this->data['unique_chart_id']); ?>" style="width: <?php wpl_esc::attr($params['chart_width']); ?>; height: <?php wpl_esc::attr($params['chart_height']); ?>;"></div>