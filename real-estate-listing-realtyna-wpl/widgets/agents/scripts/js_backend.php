<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var wpl_agents_user_ids<?php wpl_esc::numeric($this->widget_id); ?> = false;
function random_clicked_agents<?php wpl_esc::numeric($this->widget_id); ?>()
{
	if(!wpl_agents_user_ids<?php wpl_esc::numeric($this->widget_id); ?>)
		wpl_agents_user_ids<?php wpl_esc::numeric($this->widget_id); ?> = wplj("#<?php wpl_esc::attr($this->get_field_id('data_user_ids')); ?>").val();
	
	if(wplj('#<?php wpl_esc::js($this->get_field_id('data_random')); ?>').is(':checked'))
	{
		wpl_agents_user_ids<?php wpl_esc::numeric($this->widget_id); ?> = wplj("#<?php wpl_esc::attr($this->get_field_id('data_user_ids')); ?>").val();
		wplj("#<?php wpl_esc::attr($this->get_field_id('data_user_ids')); ?>").val('');
	}
	else
	{
		wplj("#<?php wpl_esc::attr($this->get_field_id('data_user_ids')); ?>").val(wpl_agents_user_ids<?php wpl_esc::numeric($this->widget_id); ?>);
	}
}
</script>
<script type="text/javascript">
    (function($){$(function(){isWPL();})})(jQuery);
</script>