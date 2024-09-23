<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
jQuery(document).ready(function()
{
	var s1 = <?php wpl_esc::js($this->rendered); ?>;

	var plot = wplj.jqplot('chartdiv<?php wpl_esc::attr($this->data['unique_chart_id']); ?>', s1,
	{
		<?php if(trim($this->chart_title) != '') wpl_esc::e("title: '".wpl_esc::return_attr($this->chart_title)."',"); ?>
		axesDefaults: {
			labelRenderer: wplj.jqplot.CanvasAxisLabelRenderer
		},
		seriesDefaults: {
		  rendererOptions: {
			  smooth: true
		  }
		},
		axes: {}
    });
});
</script>