<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = $params['wpl_properties'] ?? array();
$property_id = $wpl_properties['current']['data']['id'] ?? NULL;

// Property Link
$this->url = $wpl_properties['current']['property_link'];

$picture_width = $params['picture_width'] ?? 80;
$picture_height = $params['picture_height'] ?? 80;
$outer_margin = $params['outer_margin'] ?? 2;
$qrfile_prefix = $params['qrfile_prefix'] ?? 'qr_';
$size = $params['size'] ?? 4;
$size = in_array($size, array(1,2,3,4,5,6,7,8,9,10)) ? $size : 4;

$qr_image = $this->get_qr_image($qrfile_prefix, $size, $outer_margin);

global $pdfflyer;
if(isset($pdfflyer) and $pdfflyer) $qr_image = wpl_pdf::get_fixed_url($qr_image);
?>
<div class="wpl_qrcode_container" id="wpl_qrcode_container<?php wpl_esc::attr($property_id); ?>">
	<img
			src="<?php wpl_esc::url($qr_image); ?>"
			width="<?php wpl_esc::attr($picture_width); ?>"
			height="<?php wpl_esc::attr($picture_height); ?>"
			alt="<?php wpl_esc::html_t('QR Code'); ?>"
			title="<?php wpl_esc::html_t('QR Code'); ?>"
	/>
</div>