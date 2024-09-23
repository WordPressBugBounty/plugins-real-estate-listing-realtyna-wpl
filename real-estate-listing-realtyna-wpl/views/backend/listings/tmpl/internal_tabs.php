<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div id="wpl_listings_top_tabs_container">
    <ul class="wpl-tabs">
        <?php foreach($this->kinds as $kind): ?>
        <?php if(($kind['id'] == 1 and !wpl_users::check_access('complex_addon')) or ($kind['id'] == 4 and !wpl_users::check_access('neighborhoods'))) continue; ?>
        <li class="<?php wpl_esc::attr($this->kind == $kind['id'] ? 'wpl-selected-tab' : '') ?>" id="wplkind<?php wpl_esc::attr($kind['id']); ?>">
            <a href="<?php wpl_esc::url(wpl_global::add_qs_var('kind', $kind['id'])); ?>">
				<?php wpl_esc::html_t($kind['name']); ?>
			</a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>