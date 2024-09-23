<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
jQuery(document).ready(function()
{
	wplj.jqplot.config.enablePlugins = true;
	var s1 = <?php wpl_esc::js($this->rendered[0]); ?>;
	var ticks = <?php wpl_esc::js($this->rendered[1]); ?>;
	
	plot = wplj.jqplot('chartdiv<?php wpl_esc::attr($this->data['unique_chart_id']); ?>', [s1],
	{
		<?php if(trim($this->chart_title) != '') wpl_esc::e("title: '".wpl_esc::return_attr($this->chart_title)."',"); ?>
		animate: !wplj.jqplot.use_excanvas,
		seriesDefaults: {
			renderer: wplj.jqplot.BarRenderer,
			pointLabels: { show: true }
		},
		axes: {
			xaxis: {
				renderer: wplj.jqplot.CategoryAxisRenderer,
				ticks: ticks
			}
		},
		highlighter: { show: true }
	});
});
</script>