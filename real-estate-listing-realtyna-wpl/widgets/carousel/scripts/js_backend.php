<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var wpl_carousel_property_ids<?php wpl_esc::numeric($this->widget_id); ?> = false;
function random_clicked<?php wpl_esc::numeric($this->widget_id); ?>()
{
    if(!wpl_carousel_property_ids<?php wpl_esc::numeric($this->widget_id); ?>) wpl_carousel_property_ids<?php wpl_esc::numeric($this->widget_id); ?> = wplj("#<?php wpl_esc::attr($this->get_field_id('data_property_ids')); ?>").val();

    if(wplj("#<?php wpl_esc::attr($this->get_field_id('data_random')); ?>").is(':checked'))
    {
        wpl_carousel_property_ids<?php wpl_esc::numeric($this->widget_id); ?> = wplj("#<?php wpl_esc::attr($this->get_field_id('data_property_ids')); ?>").val();
        wplj("#<?php wpl_esc::attr($this->get_field_id('data_property_ids')); ?>").val('');
    }
    else
    {
        wplj("#<?php wpl_esc::attr($this->get_field_id('data_property_ids')); ?>").val(wpl_carousel_property_ids<?php wpl_esc::numeric($this->widget_id); ?>);
    }
}

wplj(function()
{
    var carouselForms = wplj('.wpl_carousel_widget_backend_form');
    //region + Carousel Init

    // Remove underline _ from Layout names
    wplj('.wpl-carousel-widget-layout').find('option').each(function()
    {
        var text = wplj(this).text().replaceAll('_', ' ');
        wplj(this).text(text);
    });
    //endregion

    //region + Carousel Options
    function _showCarouselCorrectOptions(formObj)
    {
        //Show related options & Hide irrelevant options
        var layoutValue = formObj.find('.wpl-carousel-widget-layout').val();
        formObj.find('.wpl-carousel-opt').not('[data-wpl-carousel-type="general"]').hide().filter('[data-wpl-carousel-type*='+ layoutValue +']').show();

        // Placeholder setter
        formObj.find('.wpl-carousel-opt[data-wpl-pl-init]').each(function()
        {
            var phValues = Realtyna.options2JSON(wplj(this).attr('data-wpl-pl-init'));
            if(phValues.hasOwnProperty(layoutValue))
            {
                wplj(this).find('input[type=text]').attr('placeholder',phValues[layoutValue]);
            }
        });
    }

    carouselForms.each(function()
    {
        _showCarouselCorrectOptions(wplj(this));
    });

    carouselForms.find('.wpl-carousel-widget-layout').off('change.wpl-carousel').on('change.wpl-carousel',function()
    {
        _showCarouselCorrectOptions(wplj('.wpl-carousel-widget-' + wplj(this).attr('data-wpl-carousel-id')));
    });
    //endregion

    //check existing setting
    if (wplj("#<?php wpl_esc::attr($this->get_field_id('tag_group_join_type_or')); ?>").is(':checked'))
    {
        wplj(".<?php wpl_esc::attr($this->get_field_id('data_tags_label')); ?>").each(function()
        {
            var str = wplj(this).text().split('Only');
            wplj(this).text(str.join(''));
        });
    }

    wplj('[id*="-tag_group_join_type_or"]').click(function()
    {
        var carousel_widget_id = wplj(this).attr('id').split('-')[2];
        wplj('.widget-wpl_carousel_widget-' + carousel_widget_id + '-data_tags_label').each(function()
        {
            var str = wplj(this).text().split('Only');
            wplj(this).text(str.join(''));
        });
    });

    wplj('[id*="-tag_group_join_type_and"]').click(function()
    {
        var carousel_widget_id = wplj(this).attr('id').split('-')[2];
        wplj('.widget-wpl_carousel_widget-' + carousel_widget_id + '-data_tags_label').each(function()
        {
            if(wplj(this).text().split(' ')[0] !== 'Only')
            {
                wplj(this).text('Only' + wplj(this).text());
            }
        });
    });
});

(function ($) {$(function () {isWPL();})})(jQuery);
</script>