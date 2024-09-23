<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.images');

$image_width = isset($image_width) ? $image_width : 180;
$image_height = isset($image_height) ? $image_height : 125;

/*Agent and office name for mls compliance*/
$show_agent_name = wpl_global::get_setting('show_agent_name');
$show_office_name = wpl_global::get_setting('show_listing_brokerage');

foreach($this->wpl_properties as $key=>$property)
{
	$property_id = $property['data']['id'];

    $kind = $property['data']['kind'];
    $locations	 = $property['location_text'];
    
    // Get blog ID of property
    $blog_id = wpl_property::get_blog_id($property_id);

	$room       = isset($property['materials']['bedrooms']) ? '<div class="bedroom">'.$property['materials']['bedrooms']['value'].'<span class="name">'.wpl_esc::return_html_t("Bedroom(s)").'</span></div>' : '';
    if((!isset($property['materials']['bedrooms']) or (isset($property['materials']['bedrooms']) and $property['materials']['bedrooms']['value'] == 0)) and (isset($property['materials']['rooms']) and $property['materials']['rooms']['value'] != 0)) $room = '<div class="room">'.$property['materials']['rooms']['value'].'<span class="name">'.wpl_esc::return_html_t("Room(s)").'</span></div>';
    
    $bathroom   = isset($property['materials']['bathrooms']) ? '<div class="bathroom">'.$property['materials']['bathrooms']['value'].'<span class="name">'.wpl_esc::return_html_t("Bathroom(s)").'</span></div>' : '';

	$parking_number = (isset($property['materials']['f_150']) and isset($property['materials']['f_150']['values']) and isset($property['materials']['f_150']['values'][0])) ? $property['materials']['f_150']['values'][0] : NULL;
    $parking    = (isset($property['raw']['f_150']) and trim($property['raw']['f_150_options'])) ? '<div class="parking">'.$parking_number.'</div>' : '';

    $pic_count  = '<div class="pic_count">'.$property['raw']['pic_numb'].'</div>';
    $price 		= '<div class="price">'.$property['materials']['price']['value'].'</div>';

    $office_name = '';
    $agent_name = '';
    if(wpl_global::check_addon('MLS') and ($show_agent_name or $show_office_name))
    {
        $office_name = isset($property['raw']['field_2111']) ? '<div class="wpl-prp-office-name">'.$property['raw']['field_2111'].'</div>' : '';
        $agent_name = isset($property['raw']['field_2112']) ? '<div class="wpl-prp-agent-name">'.$property['raw']['field_2112'].'</div>' : '';
    }
?>
	<div id="main_infowindow">
		<div class="main_infowindow_l">
		<?php
            if(isset($property['items']['gallery']))
			{
				$i = 0;
                $images_total = count($property['items']['gallery']);
                $property_path = wpl_items::get_path($property_id, $kind, $blog_id);

                $image = $property['items']['gallery'][0];
                $params = array();
                $params['image_name'] = $image->item_name;
                $params['image_parentid'] = $image->parent_id;
                $params['image_parentkind'] = $image->parent_kind;
                $params['image_source'] = $property_path.$image->item_name;

                $image_alt = '';
                if(isset($image->item_extra2)) $image_alt = $image->item_extra2;
                elseif(isset($property['raw']['meta_keywords'])) $image_alt = $property['raw']['meta_keywords'];

                if(isset($image->item_cat) and $image->item_cat != 'external') $image_url = wpl_images::create_gallery_image($image_width, $image_height, $params);
                else $image_url = $image->item_extra3;

				wpl_esc::e('<a href="'.$property['property_link'].'"><img itemprop="image" id="wpl_gallery_image'.$property_id .'_'.$i.'" src="'.$image_url.'" class="wpl_gallery_image" width="'.$image_width.'" height="'.$image_height.'" style="width: '.$image_width.'px; height: '.$image_height.'px;" alt="'.$image_alt.'" title="'.$image_alt.'" /></a>');
                $i++;
			}
			else
			{
				wpl_esc::e('<a href="'.$property['property_link'].'"><div class="no_image_box"></div></a>');
			}

			?>
		</div>
		<div class="main_infowindow_r">
			<div class="main_infowindow_r_t">
				<a itemprop="url" class="main_infowindow_title" href="<?php wpl_esc::attr($property['property_link']); ?>"><?php wpl_esc::html($property['property_title']); ?></a>
				<div class="main_infowindow_location" itemprop="address" ><?php wpl_esc::html($locations); ?></div>
			    <?php
                    if($show_agent_name) wpl_esc::html($agent_name);
                    if($show_office_name) wpl_esc::html($office_name);
			    ?>
            </div>
			<div class="main_infowindow_r_b">
				<?php wpl_esc::e($room.$bathroom.$parking.$pic_count.$price); ?>
			</div>
		</div>
	</div>
<?php } ?>