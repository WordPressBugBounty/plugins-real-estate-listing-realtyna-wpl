<?php

class wpl_esc {
	public static function translate($string) {
		return __($string, 'real-estate-listing-realtyna-wpl');
	}
	public static function return_t($string) {
		return wpl_esc::translate($string);
	}
	public static function html_t($string) {
		esc_html_e($string, 'real-estate-listing-realtyna-wpl');
	}
	public static function return_html_t($string) {
		return esc_html(wpl_esc::translate($string));
	}
	public static function return_html($string) {
		return esc_html($string);
	}
	public static function html($string) {
		wpl_esc::e(esc_html($string));
	}
	public static function kses($string) {
		wpl_esc::e(wpl_esc::return_kses($string));
	}

	public static function return_kses($string) {
		return wp_kses($string, [
			'a'       => [
				'href'   => [],
				'title'  => [],
				'target' => [],
			],
			'abbr'    => [ 'title' => [] ],
			'acronym' => [ 'title' => [] ],
			'video'    => [],
			'source'    => [],
			'code'    => [],
			'pre'     => [],
			'em'      => [],
			'b'        => [],
			'i'        => [],
			'strong'  => [],
			'div'     => [],
			'p'       => [],
			'ul'      => [],
			'ol'      => [],
			'li'      => [],
			'h1'      => [],
			'h2'      => [],
			'h3'      => [],
			'h4'      => [],
			'h5'      => [],
			'h6'      => [],
			'img'     => [
				'src'   => [],
				'class' => [],
				'alt'   => [],
			],
		]);
	}
	public static function attr($string) {
		wpl_esc::e(esc_attr($string));
	}
	public static function return_attr($string) {
		return esc_attr($string);
	}
	public static function return_attr_t($string) {
		return esc_attr(wpl_esc::translate($string));
	}
	public static function attr_t($string) {
		wpl_esc::attr(wpl_esc::translate($string));
	}
	public static function attr_str_if($condition, $attr, $value = '') {
		if($condition) {
			wpl_esc::e(' ' . esc_attr($attr) . '="' . esc_attr($value) . '" ');
		}
	}
	public static function js($string) {
		wpl_esc::e(esc_js($string));
	}
	public static function return_js($string) {
		return esc_js($string);
	}
	public static function numeric($string) {
		$string = str_replace([',', '"', "'"], ['','',''], $string);
		wpl_esc::e(floatval($string));
	}
	public static function js_t($string) {
		wpl_esc::js(wpl_esc::translate($string));
	}
	public static function return_js_t($string) {
		return wpl_esc::return_js(wpl_esc::translate($string));
	}
	public static function item_scope($has_microdata) {
		if($has_microdata) {
			wpl_esc::e(' itemscope ');
		}
	}
	public static function item_prop($has_microdata, $value = '') {
		if($has_microdata) {
			wpl_esc::e(' itemprop="' . esc_attr($value) . '" ');
		}
	}
	public static function item_type($has_microdata, $value = '', $showScope = true) {
		if($showScope) {
			wpl_esc::item_scope($has_microdata);
		}
		if($has_microdata) {
			wpl_esc::e(' itemtype="' . esc_url('http://schema.org/' . $value) . '" ');
		}
	}
	public static function item_address($has_microdata) {
		if($has_microdata) {
			wpl_esc::item_type($has_microdata, 'PostalAddress');
			wpl_esc::item_prop($$has_microdata, 'address');
		}
	}

	public static function url($string) {
		wpl_esc::e(wpl_esc::return_url($string));
	}
	public static function return_url($string) {
		return esc_url($string, null, '');
	}
	public static function current_url() {
		wpl_esc::url(wpl_global::get_full_url());
	}
	public static function wp_url() {
		wpl_esc::url(wpl_global::get_wp_url());
	}
	public static function wpl_url() {
		wpl_esc::url(wpl_global::get_wpl_url());
	}
	public static function wp_site_url() {
		wpl_esc::url(wpl_global::get_wp_site_url());
	}
	public static function e($string) {
		echo $string;
	}
}