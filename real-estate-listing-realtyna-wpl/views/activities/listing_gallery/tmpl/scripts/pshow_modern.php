<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
    (function($)
    {
        $(function()
        {
            <?php if($this->lazyload): ?>
            Realtyna.options.ajaxloader.coverStyle.backgroundColor = '#eeeeee';
            var loader = Realtyna.ajaxLoader.show('.wpl-gallery-pshow-wp', 'normal', 'center', true);
            Realtyna.ajaxLoader.show(loader);
            <?php endif; ?>

            var slider = $('#wpl_gallery_wrapper-<?php wpl_esc::attr($this->activity_id); ?> #wpl_gallery_container<?php wpl_esc::attr($this->property_id); ?>').lightSlider(
                {
                    pause : 4000,
                    auto: <?php wpl_esc::e(($this->autoplay) ? 'true' : 'false'); ?>,
                    mode: 'slide',
                    item: 1,
                    slideMargin: 1,
                    thumbItem:<?php wpl_esc::e(($this->thumbnail_numbers) ?: 20); ?>,
                    loop: true,
                    adaptiveHeight: true,
                    gallery: <?php wpl_esc::e(($this->thumbnail and count($this->gallery)) ? 'true' : 'false'); ?>,
                    preload: 1,
                    onSliderLoad: function(el)
                    {
                        $('#wpl_gallery_wrapper-<?php wpl_esc::attr($this->activity_id); ?> #wpl_gallery_container<?php wpl_esc::attr($this->property_id); ?> li').css('opacity','1');
                        if($('#wpl_gallery_wrapper-<?php wpl_esc::attr($this->activity_id); ?> #wpl_gallery_container<?php wpl_esc::attr($this->property_id); ?>').find('.gallery_no_image').length == 0)
                        {
                            el.lightGallery(
                                {
                                    selector: '#wpl_gallery_wrapper-<?php wpl_esc::attr($this->activity_id); ?> #wpl_gallery_container<?php wpl_esc::attr($this->property_id); ?> .lslide',
                                    thumbWidth: <?php wpl_esc::e(intval($this->thumbnail_width)) ?>
                                });
                        }
                        <?php if($this->lazyload): ?>
                        var showActiveSlides = function (entries) {
                            entries.forEach(function (entry) {
                                if (entry.isIntersecting) {
                                    entry.target.src = entry.target.dataset.src;
                                    observer.unobserve(entry.target);
                                }
                            });
                        };

                        var imageWidth = el.find("li").outerWidth() + "px";

                        var observer = new window.IntersectionObserver(showActiveSlides, {
                            root: el.parent()[0],
                            rootMargin: "0px "+ imageWidth + " 0px " + imageWidth
                        });

                        el.find("li img").each(function () {
                            observer.observe(this);
                        });
                        <?php endif; ?>
                        <?php if($this->thumbnail and count($this->gallery)): ?>
                        $('#wpl_gallery_wrapper-<?php wpl_esc::attr($this->activity_id); ?> .lSSlideOuter').append('<div class="wpl-lSSlider-thumbnails"><div class="lSAction"><a href="#" class="lSPrev" aria-label="Prev"></a><a href="#" class="lSNext" aria-label="Next"></a></div><div class="wpl-lSSlider-thumbnails-inner"></div></div>');
                        $('#wpl_gallery_wrapper-<?php wpl_esc::attr($this->activity_id); ?> .lSSlideOuter .wpl-lSSlider-thumbnails-inner').append($('#wpl_gallery_wrapper-<?php wpl_esc::attr($this->activity_id); ?> .lSPager'));

                        $('#wpl_gallery_wrapper-<?php wpl_esc::attr($this->activity_id); ?> .wpl-lSSlider-thumbnails .lSNext').on('click', function()
                        {
                            var id = slider.getCurrentSlideCount();
                            id = id + 1;
                            slider.goToSlide(id);
                        });

                        $('#wpl_gallery_wrapper-<?php wpl_esc::attr($this->activity_id); ?> .wpl-lSSlider-thumbnails .lSPrev').on('click', function()
                        {
                            var id = slider.getCurrentSlideCount();
                            id = id - 1;
                            slider.goToSlide(id);
                        });
                        <?php endif; ?>

                        <?php if($this->lazyload): ?>
                        Realtyna.ajaxLoader.hide(loader);
                        <?php endif; ?>
                    }
                });
                <?php if($this->lazyload): ?>
                    setTimeout(function () {
                        slider.refresh();
                    }, 1500);
                <?php endif; ?>


        });
    })(jQuery);
</script>