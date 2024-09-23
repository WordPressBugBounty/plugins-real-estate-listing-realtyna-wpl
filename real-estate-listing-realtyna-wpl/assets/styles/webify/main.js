/**
 * Created by Eduard on 26.01.2021
 */
 jQuery(function($) {
    $(document).ajaxStop(function () {
        if($('.wpl-rss-wp,.wpl-print-rp-wp,.wpl-save-search-wp,.wpl-landing-page-generator-wp').parent().hasClass('wpl-save-rss')){
            return false;
        }
        else {
            $('.wpl-rss-wp,.wpl-print-rp-wp,.wpl-save-search-wp,.wpl-landing-page-generator-wp').wrapAll('<div class="wpl-save-rss"></div>');
        }
    })
    $(document).ready(function() {
        $(".wpl-profile-listing-wp .wpl_profile_container ul li.fax").wrapInner("<a></a>");
        $('a[href="#wpl_gallery_external"],a[href="#wpl_gallery_uploader"]').off('click');
    })
    // $(document).ready(function() {
    //     if (document.querySelector('.wpl_property_listing_container') != null) {
    //          if (document.querySelector('.wpl-gallery-pshow-wp') != null) {
    //             const DOM = (sliders) => {
    //                 let qtyGallery = document.createElement('div');
    //                 qtyGallery.classList.add('gallery-qty');
    //                 let allGallery = document.createTextNode(sliders.length);
    //                 let backSlash = document.createTextNode('/');
    //                 let currentGalleryNode = document.createElement('p');
    //                 currentGalleryNode.setAttribute('id', 'current-wpl-gallery');
    //                 currentGalleryNode.textContent = 1;
    //                 qtyGallery.appendChild(currentGalleryNode)
    //                 qtyGallery.appendChild(backSlash)
    //                 qtyGallery.appendChild(allGallery)
    //                 sliders[0].parentNode.parentNode.appendChild(qtyGallery)
    //             }
    //             const currentGallery = () => {
    //                 allProperties()
    //                 allPropertiesDom.forEach(item => {
    //                     let gallery = item.querySelector('.wpl-gallery-pshow-wp');
    //                     let sliders = item.querySelectorAll('.wpl-gallery-pshow li');
    //                     sliders.forEach((slider, index) => {
    //                         if (slider.classList.contains('active')) {
    //                             gallery.querySelector('#current-wpl-gallery').textContent = index + 1;
    //                         }
    //                     })
    //                 })
    //             }
    //             const allProperties = () => {
    //                 return allPropertiesDom = document.querySelectorAll('.wpl_property_listing_listings_container .wpl-column');
    //             }
    //             const qtyGallery = () => {
    //                 allProperties()
    //                 allPropertiesDom.forEach(item => {
    //                     let sliders = item.querySelectorAll('.wpl-gallery-pshow li');
    //                     DOM(sliders)
    //                 })
    //                 setInterval(currentGallery, 1000)
    //             }
    //             qtyGallery()
    //     }
    //     }
    // });
});