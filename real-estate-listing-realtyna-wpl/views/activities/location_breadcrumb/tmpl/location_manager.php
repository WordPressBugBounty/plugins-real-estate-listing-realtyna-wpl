<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$html_element_id = $params['html_element_id'] ?? '';
$root_url = $params['root_url'] ?? wpl_global::get_full_url();
$element_class = $params['element_class'] ?? 'location_breadcrumb';
$separator = $params['separator'] ?? ' > ';
$location_level = $params['location_level'] ?? 1;
$location_id = $params['location_id'] ?? '';
$load_zipcodes = $params['load_zipcodes'] ?? 0;

$location_tree = wpl_locations::get_location_tree($location_id, ($location_level-1));
$levels = count($location_tree)+1;
$breadcrumb_str = "";

$i = 1;
foreach($location_tree as $branch)
{
	if(trim($branch['name'] ?? '') == '') continue;
	
	$link = wpl_global::add_qs_var('level', $levels, $root_url);
	$link = wpl_global::add_qs_var('sf_select_parent', $branch['id'], $link);
	if(($load_zipcodes and $i == 1)) $link = wpl_global::add_qs_var('load_zipcodes', 1, $link);
	
	$breadcrumb_str = $separator.'<a href="'. wpl_esc::return_url($link) .'">'.(($load_zipcodes and $i == 1) ? wpl_esc::return_html($branch['name']).' ('.wpl_esc::return_html_t('Zipcodes').')' : wpl_esc::return_html($branch['name'])).'</a>'.$breadcrumb_str;
	$levels--;
	$i++;
}
?>
<div class="<?php wpl_esc::attr($element_class); ?>" id="<?php wpl_esc::attr($html_element_id); ?>">
	<a href="<?php wpl_esc::url($root_url); ?>"><?php wpl_esc::html_t('All Countries'); ?></a>
    <?php wpl_esc::kses($breadcrumb_str); ?>
    <span data-realtyna-lightbox
		  data-realtyna-lightbox-opts="reloadPage:true"
		  data-realtyna-href="#wpl_location_fancybox_cnt"
		  class="wpl_create_new action-btn icon-plus"
		  id="wpl_add_location_item"
		  onclick="wpl_generate_modify_page('<?php wpl_esc::attr(!$load_zipcodes ? $location_level : 'zips'); ?>','<?php wpl_esc::attr($location_id); ?>')"
		  title="<?php wpl_esc::attr_t('Add location'); ?>"></span>
</div>