<?php
/**
 * wpl_users::autocomplete()
 */
$attributes_list = [];
$id = '';
$show_ajax = $show_ajax ?? true;
$attributes = $attributes ?? [];
$wpl_users = $wpl_users ?? [];
if(!is_array($attributes)) {
	$attributes = [$attributes];
}
if(empty($wpl_users)) {
	$show_ajax = false;
}
$opts = 'disable_search_threshold:0';
$multiple = false;
foreach ($attributes as $key => $value) {
	if($show_ajax && ($value == 'data-has-chosen' || $key == 'data-has-chosen')) {
		continue;
	}
	if($key == 'multiple') {
		$multiple = true;
	}
	if($show_ajax && $key == 'data-chosen-opt') {
		$opts .= '|' . wpl_esc::return_attr($value);
		continue;
	}
	if(is_numeric($key)) {
		$attributes_list[] = wpl_esc::return_attr($value);
	} else {
		$attributes_list[] = wpl_esc::return_attr($key) . '="' . wpl_esc::return_attr($value) . '"';
	}
	 if($key == 'id') {
		 $id = $value;
	 }
}
if(empty($id)) {
	$id = uniqid('wpl_select_');
}
?>
<select <?php if($show_ajax): ?> data-chosen-opt="<?php wpl_esc::e($opts); ?>" <?php endif; ?> <?php wpl_esc::e(implode(' ', $attributes_list)); ?>>
	<option value="-1">-- <?php wpl_esc::html_t('User') ?> --</option>
	<?php foreach($wpl_users as $wpl_user): ?>
		<option value="<?php wpl_esc::attr($wpl_user->ID) ?>" <?php wpl_esc::e(in_array($wpl_user->ID, $selected_user_id) ? 'selected="selected"' : ''); ?>><?php wpl_esc::html($wpl_user->user_login); ?></option>
	<?php endforeach; ?>
</select>
<?php if($show_ajax): ?>
<script>
	wplj(function() {
		wplj('#<?php wpl_esc::js($id); ?>').chosen();
		let previousValue = wplj('#<?php wpl_esc::js($id); ?>_chosen input').val();
		<?php if(!$multiple): ?>
		wplj('#<?php wpl_esc::js($id); ?>').on('chosen:showing_dropdown', function () {
			wplj('#<?php wpl_esc::js($id); ?>_chosen input').val(previousValue);
		});
		<?php endif; ?>

		wplj('#<?php wpl_esc::js($id); ?>_chosen input').on('keyup', function () {
			let currentValue = wplj(this).val();
			if(currentValue === previousValue) {
				return;
			}
			previousValue = currentValue;
			wplj('#<?php wpl_esc::js($id); ?>_chosen ul.chosen-results').empty();
			wplj('#<?php wpl_esc::js($id); ?>_chosen ul.chosen-results').append('<li class="active-result"><img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" /></li>');
			const search_value = wplj(this).val();
			wplj.ajax({
				type: 'GET',
				dataType: 'JSON',
				url: '<?php echo wpl_global::get_full_url(); ?>',
				data: 'wpl_format=b:users:ajax&wpl_function=autocomplete&_wpnonce=<?php wpl_esc::attr(wpl_security::create_nonce('wpl_users')); ?>&search=' + search_value,
				success: function (data) {
					const selected_values = wplj('#<?php wpl_esc::js($id); ?>').val();
					wplj('#<?php wpl_esc::js($id); ?> option:not(:selected)').remove();
					const option_values = [];
					wplj("#<?php wpl_esc::js($id); ?> option").each(function() {
						option_values.push(+wplj(this).val());
					});
					if(data.data && data.data.length > 0) {
						for(const user of data.data) {
							if(option_values.includes(+user.id)) {
								continue;
							}
							wplj('#<?php wpl_esc::js($id); ?>').append('<option value="' + user.id + '">' + user.name + '</option>');
						}
						wplj('#<?php wpl_esc::js($id); ?>').trigger("chosen:updated");
						wplj('#<?php wpl_esc::js($id); ?>_chosen input').val(search_value);
						wplj('#<?php wpl_esc::js($id); ?>_chosen input').get(0).focus();
					}
				}
			});
			{

			}
		});
	})
</script>
<?php endif; ?>