<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();
$index = 1;

$content = '<h3>'.wpl_esc::return_html_t('Manage Existing Listings').'</h3><p>'.wpl_esc::return_html_t("Here you can find all existing listings to manage. You can edit them, remove them or unpublish them.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.wrap.wpl-wp h2:first', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'))));

$content = '<h3>'.wpl_esc::return_html_t('Different Kinds').'</h3><p>'.wpl_esc::return_html_t("Switch to different kinds of listings.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'#wpl_listings_top_tabs_container', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Search Listings').'</h3><p>'.wpl_esc::return_html_t("Do you have many listings? You can filter them using this form simply.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.wpl_listing_manager_search_form_element_cnt .wpl-button', 'content'=>$content, 'position'=>array('edge'=>'left', 'align'=>'middle'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Confirm / Unconfirm').'</h3><p>'.wpl_esc::return_html_t("You can unconfirm the listings using this button if needed. This way the listing don't appear on search results untill it confirmed again.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.properties-wp .propery-wp:first .p-actions-wp .p-action-btn:first', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Purge Listing').'</h3><p>'.wpl_esc::return_html_t("If you need to remove a listing completely. You can use purge button.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.properties-wp .propery-wp:first .p-actions-wp .p-action-btn:nth-child(4)', 'content'=>$content, 'position'=>array('edge'=>'right', 'align'=>'middle'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Edit Listing').'</h3><p>'.wpl_esc::return_html_t("For modifying the listing click this button.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.properties-wp .propery-wp:first .p-actions-wp .p-action-btn:nth-child('.(wpl_global::check_addon('pro') ? '6' : '5').')', 'content'=>$content, 'position'=>array('edge'=>'right', 'align'=>'middle'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$next_page = NULL;
if(wpl_global::check_addon('pro')) $next_page = 'wpl_admin_listing_stats';

$content = '<h3>'.wpl_esc::return_html_t('Property Details Page').'</h3><p>'.wpl_esc::return_html_t("Open the property details page in the frontend of website.").'</p>';

$buttons = array();
if($next_page) $buttons[2] = array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page='.$next_page.'&wpltour=1";');
$buttons[3] = array('label'=>wpl_esc::return_html_t('Previous'));

$tips[] = array('id'=>$index++, 'selector'=>'.properties-wp .propery-wp:first .property-image .p-links', 'content'=>$content, 'position'=>array('edge'=>'left', 'align'=>'middle'), 'buttons'=>$buttons);

return $tips;