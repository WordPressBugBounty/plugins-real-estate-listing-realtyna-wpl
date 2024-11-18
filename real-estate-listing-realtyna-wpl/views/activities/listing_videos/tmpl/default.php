<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.js', true, true);

$js = (object)array('param1' => 'jquery-video-js-script', 'param2' => 'packages/video-js/video.js');
$style = (object)array('param1' => 'ajax-video-js-style', 'param2' => 'packages/video-js/video-js.min.css');

/** import styles and javascripts **/
wpl_extensions::import_javascript($js);
wpl_extensions::import_style($style);

/** set params **/
$wpl_properties = $params['wpl_properties'] ?? array();
$property_id = $wpl_properties['current']['data']['id'] ?? NULL;
$raw_videos = $wpl_properties['current']['items']['video'] ?? NULL;
$videos = wpl_items::render_videos($raw_videos);

$video_width = $params['video_width'] ?? 640;
$video_height = $params['video_height'] ?? 270;

if (!count($videos) or !is_array($videos)) return;
?>
<div itemprop="video" itemscope itemtype="http://schema.org/VideoObject" class="wpl_videos_container"
	 id="wpl_videos_container<?php wpl_esc::attr($property_id); ?>">
	<ul class="wpl_videos_list_container">
		<?php foreach ($videos as $video): ?>
			<li class="wpl_videos_video wpl_video_type<?php wpl_esc::attr($video['item_cat'] ?? ''); ?>"
				id="wpl_videos_video<?php wpl_esc::attr($video['id'] ?? ''); ?>">
				<?php if ($video['category'] == 'video'): ?>
					<video id="example_video_<?php wpl_esc::attr($video['raw']['id']); ?>" class="video-js vjs-default-skin"
						   controls preload="none" width="<?php wpl_esc::attr($video_width); ?>"
						   height="<?php wpl_esc::attr($video_height); ?>" data-setup="{}">
						<source src="<?php wpl_esc::url($video['url']); ?>"
								type='video/<?php wpl_esc::attr(pathinfo($video['url'], PATHINFO_EXTENSION)); ?>'/>
					</video>
				<?php elseif ($video['category'] == 'video_embed'): ?>
					<?php wpl_esc::e($video['url']); ?>
				<?php endif; ?>
				<?php if (isset($video['title'])) wpl_esc::e('<h3 class="wpl-util-hidden" itemprop="name">' . wpl_esc::return_html_t($video['title']) . '</h3>'); ?>
				<?php if (isset($video['description'])) wpl_esc::e('<p class="wpl-util-hidden" itemprop="description">' . wpl_esc::return_kses($video['description']) . '</p>'); ?>
				<?php if (isset($video['thumbnail'])) wpl_esc::e('<p class="wpl-util-hidden" itemprop="thumbnailUrl" content="' . wpl_esc::return_url($video['thumbnail']) . '"></p>'); ?>
				<?php if (isset($video['date'])) wpl_esc::e('<p class="wpl-util-hidden" itemprop="uploadDate" content="' . wpl_esc::return_attr($video['date']) . '"></p>'); ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>