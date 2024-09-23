<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<a class="search-tab" href="#wpl-search-tab-content-<?php wpl_esc::attr($this->widget_id . '-' . $category->id); ?>">
    <span>
        <?php wpl_esc::html($category->name); ?>
    </span>
</a>

