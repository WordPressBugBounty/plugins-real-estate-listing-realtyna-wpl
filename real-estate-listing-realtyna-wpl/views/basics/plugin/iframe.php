<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * This is a standalone document rendered into an iframe for the editor popup, not a WordPress page. It has
 * no wp_head() or wp_footer(), so there is nowhere for wp_enqueue_script() to print and the tags below have
 * to be written directly.
 */
// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript, WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="<?php wpl_esc::e(wpl_global::get_wp_admin_url()); ?>load-scripts.php?c=1&load[]=jquery-core,jquery-migrate&ver=<?php wpl_esc::e(wpl_global::wp_version()); ?>"></script>
    <script type="text/javascript">
    wpl_baseUrl="<?php wpl_esc::e(wpl_global::get_wordpress_url()); ?>";
    wpl_baseName="<?php wpl_esc::e(WPL_BASENAME); ?>";
    </script>
    <script type="text/javascript" src="<?php wpl_esc::e(wpl_global::get_wordpress_url().'wp-includes/js/tinymce/tiny_mce_popup.js'); ?>"></script>

    <script type="text/javascript" src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('js/libraries/wpl.jquery.mcustomscrollbar.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('js/libraries/wpl.jquery.chosen.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('js/libraries/wpl.handlebars.min.js')); ?>"></script>

    <script type="text/javascript" src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('js/libraries/realtyna/realtyna.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('js/backend.min.js')); ?>"></script>

    <link rel="stylesheet" id="wpl_backend_main_style-css" type="text/css" media="all" href="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('css/backend.css')); ?>" />
</head>
<body>
	<?php wpl_esc::e($this->$function()); ?>
</body>
</html>