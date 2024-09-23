<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();
$index = 1;

$content = '<h3>'.wpl_esc::return_html_t('Add New Listings').'</h3><p>'.wpl_esc::return_html_t("You can add a new listing using this categorized and advanced form.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.wrap.wpl-wp h2:first', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'))));

$content = '<h3>'.wpl_esc::return_html_t('Fill The Data').'</h3><p>'.wpl_esc::return_html_t("You should fill the form data here. Some fields are mandatory and some fields are optional. Also some fields might appear or hide based on property type or listing type that you select.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.side-12.side-content-wp', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'middle'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Categorized Data').'</h3><p>'.wpl_esc::return_html_t("Also you can click on the data categories and fill all the related data. Some of the most important categories are location and gallery categories.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.side-2.side-tabs-wp', 'content'=>$content, 'position'=>array('edge'=>'left', 'align'=>'top'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Finalize the Listing').'</h3><p>'.wpl_esc::return_html_t("Don't forget to finalize the listing after filling the form otherwise it doesn't appear on the search results.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'#wpl_slide_label_id10000', 'content'=>$content, 'position'=>array('edge'=>'left', 'align'=>'middle'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page=wpl_admin_listings&wpltour=1";'), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

return $tips;