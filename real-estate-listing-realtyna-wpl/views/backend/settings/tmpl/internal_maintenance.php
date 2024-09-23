<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl_maintenance"><div class="wpl_show_message"></div></div>
<div class="wpl-maintenance-container">

    <h3 class="wpl-clear-cache-subject"><?php wpl_esc::html_t('Clear WPL Caches'); ?></h3>
    <hr>
    <form class="wpl-clear-cache-form" id="wpl_clear_cache_form">
        <ul>
            <li>
                <input type="checkbox" name="cache[wpl_cache_directory]" value="1" checked="checked" id="wpl_cache_wpl_cache_directory" />
                <span class="title">
                    <label for="wpl_cache_wpl_cache_directory"><?php wpl_esc::html_t('Purge WPL cache directory'); ?></label>
                </span>
            </li>
            <li>
                <input type="checkbox" name="cache[unfinalized_properties]" value="1" id="wpl_cache_unfinalized_properties" />
                <span class="title">
                    <label for="wpl_cache_unfinalized_properties"><?php wpl_esc::html_t('Purge unfinalized listings'); ?></label>
                </span>
            </li>
            <?php $manual_title_generation = intval(wpl_settings::get('manual_title_generation')) ?? null; ?>
            <li>
                <input type="checkbox" <?php if ($manual_title_generation) wpl_esc::e("disabled"); ?> name="cache[properties_title]" value="1" id="wpl_cache_properties_title" />
                <span class="title">
                    <label for="wpl_cache_properties_title"><?php wpl_esc::html_t('Clear properties titles'); ?></label>
                </span>
            </li>
            <li>
                <input type="checkbox" <?php if ($manual_title_generation) wpl_esc::e("disabled"); ?> name="cache[properties_page_title]" value="1" id="wpl_cache_properties_page_title" />
                <span class="title">
                    <label for="wpl_cache_properties_page_title"><?php wpl_esc::html_t('Clear properties page titles'); ?></label>
                </span>
            </li>
            <?php if(wpl_global::check_addon('complex')): ?>
            <li>
                <input type="checkbox" name="cache[complexes_title]" value="1" id="wpl_cache_complexes_title" />
                <span class="title">
                    <label for="wpl_cache_complexes_title"><?php wpl_esc::html_t('Clear complexes titles'); ?></label>
                </span>
            </li>
            <li>
                <input type="checkbox" name="cache[complexes_page_title]" value="1" id="wpl_cache_complexes_page_title" />
                <span class="title">
                    <label for="wpl_cache_complexes_page_title"><?php wpl_esc::html_t('Clear complexes page titles'); ?></label>
                </span>
            </li>
            <?php endif; ?>
            <?php if(wpl_global::check_addon('neighborhoods')): ?>
            <li>
                <input type="checkbox" name="cache[neighborhoods_title]" value="1" id="wpl_cache_neighborhoods_title" />
                <span class="title">
                    <label for="wpl_cache_neighborhoods_title"><?php wpl_esc::html_t('Clear neighborhoods titles'); ?></label>
                </span>
            </li>
            <li>
                <input type="checkbox" name="cache[neighborhoods_page_title]" value="1" id="wpl_cache_neighborhoods_page_title" />
                <span class="title">
                    <label for="wpl_cache_neighborhoods_page_title"><?php wpl_esc::html_t('Clear neighborhoods page titles'); ?></label>
                </span>
            </li>
            <?php endif; ?>
            <li>
                <input type="checkbox" name="cache[properties_cached_data]" value="1" checked="checked" id="wpl_cache_properties_cached_data" />
                <span class="title">
                    <label for="wpl_cache_properties_cached_data"><?php wpl_esc::html_t('Clear listings cached data'); ?></label>
                </span>
            </li>
            <li>
                <input type="checkbox" name="cache[listings_meta_keywords]" value="1" id="wpl_cache_listings_meta_keywords" />
                <span class="title">
                    <label for="wpl_cache_listings_meta_keywords"><?php wpl_esc::html_t('Listings meta keywords'); ?></label>
                </span>
            </li>
            <li>
                <input type="checkbox" name="cache[listings_meta_description]" value="1" id="wpl_cache_listings_meta_description" />
                <span class="title">
                    <label for="wpl_cache_listings_meta_description"><?php wpl_esc::html_t('Listings meta description'); ?></label>
                </span>
            </li>
            <li>
                <input type="checkbox" name="cache[location_texts]" value="1" checked="checked" id="wpl_cache_location_texts" />
                <span class="title">
                    <label for="wpl_cache_location_texts"><?php wpl_esc::html_t('Clear listings cached location texts'); ?></label>
                </span>
            </li>
            <li>
                <input type="checkbox" name="cache[listings_thumbnails]" value="1" id="wpl_cache_listings_thumbnails" />
                <span class="title">
                    <label for="wpl_cache_listings_thumbnails"><?php wpl_esc::html_t('Clear listing thumbnails'); ?></label>
                </span>
            </li>
            <li>
                <input type="checkbox" name="cache[users_cached_data]" value="1" checked="checked" id="wpl_cache_users_cached_data" />
                <span class="title">
                    <label for="wpl_cache_users_cached_data"><?php wpl_esc::html_t('Clear users cached data'); ?></label>
                </span>
            </li>
            <li>
                <input type="checkbox" name="cache[users_thumbnails]" value="1" id="wpl_cache_users_thumbnails" />
                <span class="title">
                    <label for="wpl_cache_users_thumbnails"><?php wpl_esc::html_t('Clear user thumbnails'); ?></label>
                </span>
            </li>
            <li class="wpl-clear-cache-form-submit">
                <input type="hidden" id="wpl_clear_cache_confirm" value="0" />
                <button type="submit" class="wpl-button button-1" id="wpl_clear_cache_form_submit"><?php wpl_esc::html_t('Clear'); ?></button>
            </li>
        </ul>
    </form>
    <?php if(wpl_global::check_addon('calendar')): ?>
    <h3 class="wpl-clear-cache-subject"><?php wpl_esc::html_t('Clear Listing Calendar data'); ?></h3>
    <hr>
    <ul>
        <li onclick="wpl_clear_calendar_data(0);">
            <span class="title" id="wpl_maintenance_clear_calendar_data">
                 <i class="icon-trash"></i>
                <?php wpl_esc::html_t('Clear listings calendar data'); ?>
            </span>
        </li>
    </ul>
    <?php endif; ?>
</div>
