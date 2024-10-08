<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Profile Wizard Shortcode for Elementor
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_elementor_profile_wizard extends \Elementor\Widget_Base
{
	public function get_name()
	{
		return 'wpl_profile_wizard';
	}

	public function get_title()
	{
		return wpl_esc::return_t('Profile Wizard');
	}

	public function get_icon()
	{
		return 'fa fa-user';
	}

	public function get_categories()
	{
        return array('wpl');
	}

	protected function _register_controls()
	{
	}

	protected function render()
	{
		wpl_esc::e(do_shortcode('[wpl_my_profile]'));
	}
}