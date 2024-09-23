<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if(wpl_global::zap_search_enabled())
{
    $search = new Flare\Rush\Search;
}

?>
<div class="side-6 side-statistic1">
    <div class="panel-wp">
        <h3><?php wpl_esc::html_t('Properties by listing types'); ?></h3>
        <div class="panel-body">
        	<?php

                if(isset($search)) $properties = $search->get_stats('listing');
                else $properties = wpl_db::select("SELECT COUNT(*) as count, `listing` FROM `#__wpl_properties` WHERE `finalized`='1' AND `confirmed`='1' AND `expired`='0' AND `deleted`='0' AND `listing`!='0' GROUP BY `listing`", 'loadAssocList');
				
				$data = array();
                $total = 0;
				foreach($properties as $property)
				{
					$listing = wpl_global::get_listings($property['listing']);
					if(is_object($listing))
                    {
                        $data[wpl_esc::return_html_t($listing->name)] = $property['count'];
                        $total += $property['count'];
                    }
				}
				
				$params = array(
					'chart_background'=>'#fafafa',
					'chart_width'=>'100%',
					'chart_height'=>'250px',
					'show_value'=>1,
					'data'=>$data
				);
				
				if(count($data))
                {
                    wpl_esc::e('<div class="wpl-total-properties">'.sprintf(wpl_esc::return_html_t('Total Properties: %s'), $total).'</div>');
                    wpl_global::import_activity('charts:bar', '', $params);
                }
				else wpl_esc::html_t('No data!');
			?>
        </div>
    </div>
</div>
<div class="side-6 side-statistic2">
    <div class="panel-wp">
        <h3><?php wpl_esc::html_t('Properties by property types'); ?></h3>
        <div class="panel-body">
        	<?php
                if(isset($search)) $properties = $search->get_stats('property_type');
                else $properties = wpl_db::select("SELECT COUNT(*) as count, `property_type` FROM `#__wpl_properties` WHERE `finalized`='1' AND `expired`='0' AND `confirmed`='1' AND `deleted`='0' AND `property_type`!='0' GROUP BY `property_type`", 'loadAssocList');
				
				$data = array();
                $total = 0;
				foreach($properties as $property)
				{
					$property_type = wpl_global::get_property_types($property['property_type']);
					if(is_object($property_type))
                    {
                        $data[wpl_esc::return_html_t($property_type->name)] = $property['count'];
                        $total += $property['count'];
                    }
				}
				
				$params = array(
					'chart_background'=>'#fafafa',
					'chart_width'=>'100%',
					'chart_height'=>'250px',
					'show_value'=>1,
					'data'=>$data
				);
				
				if(count($data))
                {
                    wpl_esc::e('<div class="wpl-total-properties">'.sprintf(wpl_esc::return_html_t('Total Properties: %s'), $total).'</div>');
                    wpl_global::import_activity('charts:bar', '', $params);
                }
				else wpl_esc::html_t('No data!');
			?>
        </div>
    </div>
</div>