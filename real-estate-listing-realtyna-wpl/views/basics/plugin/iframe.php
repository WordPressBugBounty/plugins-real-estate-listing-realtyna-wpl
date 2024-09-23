<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
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