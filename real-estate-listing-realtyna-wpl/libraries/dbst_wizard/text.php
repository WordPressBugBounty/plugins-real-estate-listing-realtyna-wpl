<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'text' and !$done_this)
{
    $current_language = wpl_global::get_current_language();
?>
<?php
    if(isset($field->multilingual) and $field->multilingual == 1 and wpl_global::check_multilingual_status()):
        wp_enqueue_script('jquery-effects-clip', false, array('jquery-effects-core'));
    ?>
    <label class="wpl-multiling-label wpl-multiling-text">
        <?php wpl_esc::html_t($label); ?>
        <?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?>
    </label>

    <div class="wpl-multiling-field wpl-multiling-text">

        <div class="wpl-multiling-flags-wp">

            <div class="wpl-multiling-flag-cnt">
                <?php foreach($wpllangs as $wpllang): $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $wpllang, false); ?>
                    <div data-wpl-field="wpl_c_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
                         data-wpl-title="<?php wpl_esc::attr($wpllang); ?>"
                        class="wpl-multiling-flag wpl-multiling-flag-<?php wpl_esc::attr(strtolower(substr($wpllang,-2))); wpl_esc::attr(empty($values[$lang_column])? ' wpl-multiling-empty': ''); ?>"
                        ></div>
                <?php endforeach; ?>
            </div>

            <div class="wpl-multiling-edit-btn"></div>

            <div class="wpl-multilang-field-cnt">

                <?php foreach($wpllangs as $wpllang): $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $wpllang, false); ?>
                    <div class="wpl-lang-cnt" id="wpl_langs_cnt_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>">

                        <label for="wpl_c_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"><?php wpl_esc::html($wpllang); ?></label>

                        <input type="text" class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>"
                               id="wpl_c_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
                               name="<?php wpl_esc::attr($field->table_column); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
                               placeholder="<?php wpl_esc::attr_t('Enter Specific Language Value...'); ?>"
                               value="<?php wpl_esc::attr(isset($values[$lang_column]) ? esc_attr($values[$lang_column]) : ''); ?>"
                               onchange="ajax_multilingual_save('<?php wpl_esc::js($field->id); ?>', '<?php wpl_esc::js(strtolower($wpllang)); ?>', this.value, '<?php wpl_esc::js($item_id); ?>');" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />

                        <span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>" class="wpl_listing_saved_span"></span>

                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
<?php else: ?>
    <label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
    <input type="text" class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>" id="wpl_c_<?php wpl_esc::attr($field->id); ?>" name="<?php wpl_esc::attr($field->table_column); ?>" value="<?php wpl_esc::attr($value); ?>" onchange="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::js($field->table_column); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::js($field->id); ?>');" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
    <span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
<?php endif; ?>

<?php
	$done_this = true;
}