<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div id="wpl_flex_top_tabs_container">
    <ul class="wpl-tabs">
        <?php foreach($this->kinds as $kind): ?>
        <li class="<?php wpl_esc::e($this->kind == $kind['id'] ? 'wpl-selected-tab' : ''); ?>" id="wplkind<?php wpl_esc::attr($kind['id']); ?>">
            <a href="<?php wpl_esc::url(wpl_global::add_qs_var('kind', $kind['id'], wpl_global::remove_qs_var('wpltour'))); ?>"><?php wpl_esc::html_t($kind['name']); ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>