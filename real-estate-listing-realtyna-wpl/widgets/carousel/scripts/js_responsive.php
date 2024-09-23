<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>

<script type="text/javascript">
    (function($)
    {
        <?php if($this->pager_numbers > 1): ?>
        $('#wpl-modern-<?php wpl_esc::numeric($this->widget_id); ?>').responsiveSlider(
            {
                autoplay: <?php wpl_esc::e($this->auto_play ? 'true' : 'false'); ?>,
                interval: <?php wpl_esc::numeric($this->slide_interval ?: '3000'); ?>,
                transitionTime: 1000,
                onInit: function()
                {
                    <?php if($this->lazy_load): ?>
                    $('#wpl-modern-<?php wpl_esc::numeric($this->widget_id); ?>').removeClass('loading');
                    <?php endif; ?>
                    $('#wpl-modern-<?php wpl_esc::numeric($this->widget_id); ?>').css({'height':'auto'});
                    $('#wpl-modern-<?php wpl_esc::numeric($this->widget_id); ?> .slides').fadeIn(1000);
                }
            });
        <?php else: ?>
        <?php if($this->lazy_load): ?>
            $('#wpl-modern-<?php wpl_esc::numeric($this->widget_id); ?>').removeClass('loading');
        <?php endif; ?>
            $('#wpl-modern-<?php wpl_esc::numeric($this->widget_id); ?>').css({'height':'auto'});
            $('#wpl-modern-<?php wpl_esc::numeric($this->widget_id); ?> .slides').fadeIn(1000);
        <?php endif; ?>

    })(jQuery);
</script>
