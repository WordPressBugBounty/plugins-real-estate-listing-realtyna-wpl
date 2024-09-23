<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$result = NULL;

$type = isset($params['type']) ? $params['type'] : 1; # 1 == ul and 0 == selectbox
$kind = isset($params['kind']) ? $params['kind'] : 0;
$return_array = isset($params['return_array']) ? $params['return_array'] : 0;

$sort_options = isset($params['sort_options']) ? $params['sort_options'] : wpl_sort_options::get_sort_options($kind, 1);
$active_orderby = str_replace("`", '', $this->orderby);
$active_order = strtoupper($this->order);
$result_array = array();
foreach($sort_options as $sort_option)
{
	$result_array['sort_options'][] = array
	(
		'field_name' => $sort_option['field_name'],
		'url' => '',
		'active' => $active_orderby == $sort_option['field_name'] ? 1 : 0,
		'order' => ($active_order == 'DESC' and $active_orderby == $sort_option['field_name']) ? 'ASC' : 'DESC',
		'name' => $sort_option['name']
	);
}

$html = '';
if($type == 0)
{
	$html .= '<select class="wpl_plist_sort" onchange="wpl_page_sortchange(this.value);">';
	
	foreach($sort_options as $sort_option)
	{
        if(in_array($sort_option['field_name'], array('ptype_adv', 'ltype_adv')))
        {
            $searched_types = array();
            if($sort_option['field_name'] == 'ptype_adv')
            {
                $types = wpl_global::get_property_types();
                $column = 'property_type';
                
                $multiple_types = explode(',', wpl_request::getVar('sf_multiple_property_type', ''));
                if(count($multiple_types) == 1 and trim($multiple_types[0] ?? '') == '') $multiple_types = array();
                
                $single_type = wpl_request::getVar('sf_select_property_type', '');
                $searched_types = array_unique(array_merge($multiple_types, array($single_type)));
            }
            elseif($sort_option['field_name'] == 'ltype_adv')
            {
                $types = wpl_global::get_listings();
                $column = 'listing';
                
                $multiple_types = explode(',', wpl_request::getVar('sf_multiple_listing', ''));
                if(count($multiple_types) == 1 and trim($multiple_types[0] ?? '') == '') $multiple_types = array();
                
                $single_type = wpl_request::getVar('sf_select_listing', '');
                $searched_types = array_unique(array_merge($multiple_types, array($single_type)));
            }
            
            if(count($searched_types) == 1 and trim($searched_types[0] ?? '') == '') $searched_types = array();
            
            foreach($types as $type)
            {
                if(is_array($searched_types) and count($searched_types) > 0 and !in_array($type['id'], $searched_types)) continue;
                
                if(!isset($sort_option['asc_enabled']) or (isset($sort_option['asc_enabled']) and $sort_option['asc_enabled'])) $html .= '<option value="wplorderby='.urlencode($sort_option['field_name'].':'.$type['id']).'&amp;wplorder=ASC" '.(($active_orderby == "(p.".$column." != '".$type['id']."'), p.".$column) ? 'selected="selected"' : '').'>'.sprintf(wpl_esc::return_html_t('%s first'), wpl_esc::return_html_t(wpl_global::pluralize(2, $type['name']))).'</option>';
                if(!isset($sort_option['desc_enabled']) or (isset($sort_option['desc_enabled']) and $sort_option['desc_enabled'])) $html .= '<option value="wplorderby='.urlencode($sort_option['field_name'].':'.$type['id']).'&amp;wplorder=DESC" '.(($active_orderby == "(p.".$column." = '".$type['id']."'), p.".$column) ? 'selected="selected"' : '').'>'.sprintf(wpl_esc::return_html_t('%s last'), wpl_esc::return_html_t(wpl_global::pluralize(2, $type['name']))).'</option>';
            }
        }
        else
        {
            $desc_label = sprintf(wpl_esc::return_html_t('%s descending'), wpl_esc::return_html_t($sort_option['name']));
            $asc_label = sprintf(wpl_esc::return_html_t('%s ascending'), wpl_esc::return_html_t($sort_option['name']));

            if( trim($sort_option['desc_label'] ?? '') ) $desc_label = wpl_esc::return_html_t($sort_option['desc_label']);
            if( trim($sort_option['asc_label'] ?? '') ) $asc_label = wpl_esc::return_html_t($sort_option['asc_label']);

            if(!isset($sort_option['desc_enabled']) or (isset($sort_option['desc_enabled']) and $sort_option['desc_enabled'])) $html .= '<option value="wplorderby='.urlencode($sort_option['field_name']).'&amp;wplorder=DESC" '.(($active_orderby == $sort_option['field_name'] and $active_order == 'DESC') ? 'selected="selected"' : '').'>'.$desc_label.'</option>';
            if(!isset($sort_option['asc_enabled']) or (isset($sort_option['asc_enabled']) and $sort_option['asc_enabled'])) $html .= '<option value="wplorderby='.urlencode($sort_option['field_name']).'&amp;wplorder=ASC" '.(($active_orderby == $sort_option['field_name'] and $active_order == 'ASC') ? 'selected="selected"' : '').'>'.$asc_label.'</option>';
        }
	}
	
	$html .= '</select>';
}
elseif($type == 1)
{
	$html .= '<ul>';
	$sort_type = '';
    
	foreach($sort_options as $sort_option)
	{
        if(in_array($sort_option['field_name'], array('ptype_adv', 'ltype_adv')))
        {
            $searched_types = array();
            if($sort_option['field_name'] == 'ptype_adv')
            {
                $types = wpl_global::get_property_types();
                $column = 'property_type';
                
                $multiple_types = explode(',', wpl_request::getVar('sf_multiple_property_type', ''));
                if(count($multiple_types) == 1 and trim($multiple_types[0] ?? '') == '') $multiple_types = array();
                
                $single_type = wpl_request::getVar('sf_select_property_type', '');
                $searched_types = array_unique(array_merge($multiple_types, array($single_type)));
            }
            elseif($sort_option['field_name'] == 'ltype_adv')
            {
                $types = wpl_global::get_listings();
                $column = 'listing';
                
                $multiple_types = explode(',', wpl_request::getVar('sf_multiple_listing', ''));
                if(count($multiple_types) == 1 and trim($multiple_types[0] ?? '') == '') $multiple_types = array();
                
                $single_type = wpl_request::getVar('sf_select_listing', '');
                $searched_types = array_unique(array_merge($multiple_types, array($single_type)));
            }
            
            if(count($searched_types) == 1 and trim($searched_types[0] ?? '') == '') $searched_types = array();
            
            foreach($types as $type)
            {
                if(is_array($searched_types) and count($searched_types) > 0 and !in_array($type['id'], $searched_types)) continue;
                
                $class = "wpl_plist_sort";
                
                if($active_orderby == "(p.".$column." ".($active_order == 'ASC' ? '!' : '')."= '".$type['id']."'), p.".$column) $class = "wpl_plist_sort wpl_plist_sort_active";
                $order = $order_label = 'ASC';
                
                $html .= '<li><div class="'.$class;

                if($active_orderby == "(p.".$column." ".($active_order == 'ASC' ? '!' : '')."= '".$type['id']."'), p.".$column)
                {
                    if($active_order == "ASC") $sort_type = 'sort_up';
                    else $sort_type = 'sort_down';

                    $order = ($active_order == 'ASC' ? 'DESC' : 'ASC');
                    $order_label = $active_order;
                    
                    $html .= ' '.$sort_type;
                }

                $html .= '" onclick="wpl_page_sortchange(\'wplorderby='.urlencode($sort_option['field_name'].':'.$type['id']).'&amp;wplorder='.$order.'\');">'.($order_label == "ASC" ? sprintf(wpl_esc::return_html_t('%s first'), wpl_esc::return_html_t(wpl_global::pluralize(2, $type['name']))) : sprintf(wpl_esc::return_html_t('%s last'), wpl_esc::return_html_t(wpl_global::pluralize(2, $type['name']))));
                $html .= '</div></li>';
            }
        }
        else
        {
            $class = "wpl_plist_sort";
            $order = isset($sort_option['default_order']) ? $sort_option['default_order'] : 'DESC';
            $current_order = $order;
            
            if($active_orderby == $sort_option['field_name'])
            {
                $class = "wpl_plist_sort wpl_plist_sort_active";
                $order = ($active_order == 'ASC' ? 'DESC' : 'ASC');
                
                $current_order = $active_order;
            }
            
            $label = wpl_esc::return_html_t($sort_option['name']);
            
            if($current_order == 'ASC' and trim($sort_option['asc_label'] ?? '') ) $label = wpl_esc::return_html_t($sort_option['asc_label']);
            if($current_order == 'DESC' and trim($sort_option['desc_label'] ?? '') ) $label = wpl_esc::return_html_t($sort_option['desc_label']);
        
            $html .= '<li><div class="'.$class;

            if($active_orderby == $sort_option['field_name'])
            {
                if($active_order == 'ASC') $sort_type = 'sort_up';
                else $sort_type = 'sort_down';

                $html .= ' '.$sort_type;
            }

            $html .= '" onclick="wpl_page_sortchange(\'wplorderby='.urlencode($sort_option['field_name']).'&amp;wplorder='.$order.'\');">'.$label;
            $html .= '</div></li>';
        }
	}
	
	$html .= '</ul>';
}

$result_array['html'] = $html;

if($return_array) $result = $result_array;
else $result = $html;