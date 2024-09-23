<?php /** @noinspection PhpUndefinedVariableInspection */
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if ($type == 'gallery' and !$done_this) {
	?>
	<div class="search-field-wp search-field-gallery <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>" data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<?php wpl_esc::html_t('No Option Available'); ?>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif (in_array($type, array('date', 'datetime')) and !$done_this) {
	?>
	<div class="search-field-wp search-field-date <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]">
					<option value="datepicker" <?php if (isset($value['type']) and $value['type'] == 'datepicker') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Datepicker'); ?></option>
				</select>
			</div>
			<div class="erow wpl_extoptions_span <?php wpl_esc::attr($value['type'] ?? ''); ?>">
				<input type="text"
					   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][extoption]"
					   value="<?php wpl_esc::attr($value['extoption'] ?? ''); ?>"
					   placeholder="<?php wpl_esc::attr_t('Min,Max,Icon like 1999-01-01,2020-01-01,0'); ?>"
					   title="<?php wpl_esc::attr_t('Min,Max like 1999-01-01,2020-01-01'); ?>"/>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'feature' and !$done_this) {
	?>
	<div class="search-field-wp search-field-feature <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]">
					<option value="checkbox" <?php if (isset($value['type']) and $value['type'] == "checkbox") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Check box'); ?></option>
					<option value="yesno" <?php if (isset($value['type']) and $value['type'] == "yesno") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Any/Yes'); ?></option>
					<option value="select" <?php if (isset($value['type']) and $value['type'] == "select") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Select box'); ?></option>
					<?php if (isset($options['values'])): ?>
						<option value="option_single" <?php if (isset($value['type']) and $value['type'] == "option_single") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Single Option'); ?></option>
						<option value="option_multiple" <?php if (isset($value['type']) and $value['type'] == "option_multiple") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Multiple Options'); ?></option>
					<?php endif; ?>
				</select>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif (in_array($type, array('checkbox', 'tag', 'boolean')) and !$done_this) {
	?>
	<div class="search-field-wp search-field-checkbox <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]">
					<option value="checkbox" <?php if (isset($value['type']) and $value['type'] == "checkbox") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Check box'); ?></option>
					<option value="yesno" <?php if (isset($value['type']) and $value['type'] == "yesno") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Any/Yes'); ?></option>
					<option value="select" <?php if (isset($value['type']) and $value['type'] == "select") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Select box'); ?></option>
				</select>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'listings' and !$done_this) {
	$listings = wpl_global::get_listings();
	?>
	<div class="search-field-wp search-field-listing <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]"
						onchange="selectChange(this,'listings');">
					<option value="select" <?php if (isset($value['type']) and $value['type'] == "select") wpl_esc::e('selected="selected"'); ?> ><?php wpl_esc::html_t('Select box'); ?></option>
					<option value="multiple" <?php if (isset($value['type']) and $value['type'] == "multiple") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Multiple SelectBox'); ?></option>
					<option value="checkboxes" <?php if (isset($value['type']) and $value['type'] == "checkboxes") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Check boxes'); ?></option>
					<option value="radios" <?php if (isset($value['type']) and $value['type'] == "radios") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Radio Buttons'); ?></option>
					<option value="radios_any" <?php if (isset($value['type']) and $value['type'] == "radios_any") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Radio buttons with any'); ?></option>
					<option value="predefined" <?php if (isset($value['type']) and $value['type'] == "predefined") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Predefined'); ?></option>
					<option value="select-predefined" <?php if (isset($value['type']) and $value['type'] == "select-predefined") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Select Box from predefined items'); ?></option>
				</select>
			</div>
			<span class="erow wpl_extoptions_span <?php wpl_esc::attr($value['type'] ?? ''); ?>"
				  id="wpl_extoptions_span_<?php wpl_esc::attr($field->id); ?>_1">
			<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][extoption][]"
					id="wpl_extoptions_select_<?php wpl_esc::attr($field->id); ?>" <?php if (isset($value['type']) and $value['type'] == "select-predefined") wpl_esc::e('multiple="multiple"'); ?>>
				<?php foreach ($listings as $list): ?>
					<option <?php if (isset($value['extoption']) and in_array($list['id'], $value['extoption'])) wpl_esc::e('selected="selected"'); ?> value="<?php wpl_esc::attr($list['id']); ?>"><?php wpl_esc::html($list['name']); ?></option>
				<?php endforeach; ?>
			</select>
		</span>

		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'locations' and !$done_this) {
	?>
	<div class="search-field-wp search-field-locations <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>" data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]"
						onchange="selectChange(this, 'locations');">
					<option value="simple" <?php if (isset($value['type']) and $value['type'] == 'simple') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Simple'); ?></option>
					<option value="locationtextsearch" <?php if (isset($value['type']) and $value['type'] == 'locationtextsearch') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Location Textsearch'); ?></option>
					<option value="advanced_locationtextsearch" <?php if (isset($value['type']) and $value['type'] == 'advanced_locationtextsearch') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Advanced Location Textsearch'); ?></option>
					<?php if (wpl_global::check_addon('pro')): ?>
						<option value="radiussearch" <?php if (isset($value['type']) and $value['type'] == 'radiussearch') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Radius Search'); ?></option>
						<option value="googleautosuggest" <?php if (isset($value['type']) and $value['type'] == 'googleautosuggest') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Google Auto Suggest'); ?></option>
						<option value="mullocationkeys" <?php if (isset($value['type']) and $value['type'] == 'mullocationkeys') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Multiple Keywords'); ?></option>
						<option value="dropdown" <?php if (isset($value['type']) and $value['type'] == 'dropdown') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Dropdown'); ?></option>
						<option value="multiselect_dropdown" <?php if (isset($value['type']) and $value['type'] == 'multiselect_dropdown') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Multi-Select Dropdown'); ?></option>
						<option value="hier_dropdown" <?php if (isset($value['type']) and $value['type'] == 'hier_dropdown') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Hierarchical Dropdown'); ?></option>
						<option value="hier_multiselect_dropdown" <?php if (isset($value['type']) and $value['type'] == 'hier_multiselect_dropdown') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Hierarchical Multi-Select Dropdown'); ?></option>
						<option value="location_multipleradius" <?php if (isset($value['type']) and $value['type'] == 'location_multipleradius') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Location Multiple Radius'); ?></option>
					<?php endif; ?>
				</select>
			</div>
			<div class="erow wpl_extoptions_span <?php wpl_esc::attr($value['type'] ?? ''); ?>">
				<input type="text"
					   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][extoption]"
					   value="<?php wpl_esc::attr($value['extoption'] ?? ''); ?>"
					   placeholder="<?php wpl_esc::attr_t('Location place-holder'); ?>"
					   title="<?php wpl_esc::attr_t('Location place-holder'); ?>"/>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'neighborhood' and !$done_this) {
	?>
	<div class="search-field-wp search-field-neighbornhood <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>" data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]">
					<option value="checkbox" <?php if (isset($value['type']) and $value['type'] == 'checkbox') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Check box'); ?></option>
					<option value="yesno" <?php if (isset($value['type']) and $value['type'] == 'yesno') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Any/Yes'); ?></option>
					<option value="select" <?php if (isset($value['type']) and $value['type'] == 'select') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Select box'); ?></option>
				</select>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'number' and !$done_this) {
	?>
	<div class="search-field-wp search-field-number <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>" data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]"
						onchange="selectChange(this, 'number');">
					<option value="text" <?php if (isset($value['type']) and $value['type'] == 'text') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Text'); ?></option>
					<option value="exacttext" <?php if (isset($value['type']) and $value['type'] == 'exacttext') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Exact Text'); ?></option>
					<option value="minmax" <?php if (isset($value['type']) and $value['type'] == 'minmax') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Min/Max textbox'); ?></option>
					<option value="minmax_slider" <?php if (isset($value['type']) and $value['type'] == 'minmax_slider') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Min/Max Slider'); ?></option>
					<option value="minmax_selectbox" <?php if (isset($value['type']) and $value['type'] == 'minmax_selectbox') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Min/Max SelectBox'); ?></option>
					<option value="minmax_selectbox_plus" <?php if (isset($value['type']) and $value['type'] == 'minmax_selectbox_plus') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('SelectBox+'); ?></option>
					<option value="minmax_selectbox_minus" <?php if (isset($value['type']) and $value['type'] == 'minmax_selectbox_minus') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('SelectBox-'); ?></option>
					<option value="minmax_selectbox_range" <?php if (isset($value['type']) and $value['type'] == 'minmax_selectbox_range') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Range'); ?></option>
				</select>
			</div>
			<div class="erow wpl_extoptions_span <?php wpl_esc::attr($value['type'] ?? ''); ?>">
				<input type="text"
					   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][extoption]"
					   value="<?php wpl_esc::attr($value['extoption'] ?? ''); ?>"
					   placeholder="<?php wpl_esc::attr_t('min,max,increment like 0,10,1'); ?>"
					   title="<?php wpl_esc::attr_t('min,max,increment like 0,10,1'); ?>"/>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'mmnumber' and !$done_this) {
	?>
	<div class="search-field-wp search-field-number <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]"
						onchange="selectChange(this, 'mmnumber');">
					<option value="text" <?php if (isset($value['type']) and $value['type'] == 'text') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Text'); ?></option>
					<option value="selectbox" <?php if (isset($value['type']) and $value['type'] == 'selectbox') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('SelectBox'); ?></option>
				</select>
			</div>
			<div class="erow wpl_extoptions_span <?php wpl_esc::attr($value['type'] ?? ''); ?>">
				<input type="text"
					   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][extoption]"
					   value="<?php wpl_esc::attr($value['extoption'] ?? ''); ?>"
					   placeholder="<?php wpl_esc::attr_t('min,max,increment like 0,10,1'); ?>"
					   title="<?php wpl_esc::attr_t('min,max,increment like 0,10,1'); ?>"/>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'property_types' and !$done_this) {
	$listings = wpl_global::get_property_types();
	?>
	<div class="search-field-wp search-field-property-type <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]"
						onchange="selectChange(this,'property_types');">
					<option value="select" <?php if (isset($value['type']) and $value['type'] == "select") wpl_esc::e('selected="selected"'); ?> ><?php wpl_esc::html_t('Select box'); ?></option>
					<option value="multiple" <?php if (isset($value['type']) and $value['type'] == "multiple") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Multiple SelectBox'); ?></option>
					<option value="checkboxes" <?php if (isset($value['type']) and $value['type'] == "checkboxes") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Check boxes'); ?></option>
					<option value="radios" <?php if (isset($value['type']) and $value['type'] == "radios") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Radio Buttons'); ?></option>
					<option value="radios_any" <?php if (isset($value['type']) and $value['type'] == "radios_any") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Radio buttons with any'); ?></option>
					<option value="predefined" <?php if (isset($value['type']) and $value['type'] == "predefined") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Predefined'); ?></option>
					<option value="select-predefined" <?php if (isset($value['type']) and $value['type'] == "select-predefined") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Select Box from predefined items'); ?></option>
				</select>
			</div>
			<div class="erow wpl_extoptions_span <?php wpl_esc::attr($value['type'] ?? ''); ?>"
				 id="wpl_extoptions_span_<?php wpl_esc::attr($field->id); ?>_1">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][extoption][]"
						id="wpl_extoptions_select_<?php wpl_esc::attr($field->id); ?>" <?php if (isset($value['type']) and $value['type'] == "select-predefined") wpl_esc::e('multiple="multiple"'); ?>>
					<?php foreach ($listings as $list): ?>
						<option <?php if (isset($value['extoption']) and in_array($list['id'], $value['extoption'])) wpl_esc::e('selected="selected"'); ?>
								value="<?php wpl_esc::attr($list['id']); ?>"><?php wpl_esc::html($list['name']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'text' and !$done_this) {
	?>
	<div class="search-field-wp search-field-text <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]">
					<option value="text" <?php if (isset($value['type']) and $value['type'] == 'text') wpl_esc::e('selected="selected"'); ?> ><?php wpl_esc::html_t('Text'); ?></option>
					<option value="exacttext" <?php if (isset($value['type']) and $value['type'] == 'exacttext') wpl_esc::e('selected="selected"'); ?> ><?php wpl_esc::html_t('Exact text'); ?></option>
					<option value="checkbox" <?php if (isset($value['type']) and $value['type'] == "checkbox") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Check box'); ?></option>
					<option value="yesno" <?php if (isset($value['type']) and $value['type'] == "yesno") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Any/Yes'); ?></option>
				</select>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif (in_array($type, array('select', 'multiselect')) and !$done_this) {
	?>
	<div class="search-field-wp search-field-select <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]"
						onchange="selectChange(this, 'select');">
					<option value="select" <?php if (isset($value['type']) and $value['type'] == 'select') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Select box'); ?></option>
					<option value="multiple" <?php if (isset($value['type']) and $value['type'] == 'multiple') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Multiple SelectBox'); ?></option>
					<option value="radios" <?php if (isset($value['type']) and $value['type'] == 'radios') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Radio Buttons'); ?></option>
					<option value="radios_any" <?php if (isset($value['type']) and $value['type'] == 'radios_any') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Radio buttons with any'); ?></option>
					<option value="checkboxes" <?php if (isset($value['type']) and $value['type'] == 'checkboxes') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Check boxes'); ?></option>
					<option value="predefined" <?php if (isset($value['type']) and $value['type'] == 'predefined') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Predefined'); ?></option>
					<option value="select-predefined" <?php if (isset($value['type']) and $value['type'] == "select-predefined") wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Select Box from predefined items'); ?></option>
				</select>
			</div>
			<div class="erow wpl_extoptions_span <?php wpl_esc::attr($value['type'] ?? ''); ?>">
				<select multiple="multiple"
						name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][extoption][]">
					<?php
					$options = $field->options ?? array();
					$options = json_decode($options, true);
					$options = $options['params'];

					foreach ($options as $option) {
						?>
						<option <?php if (isset($value['extoption']) and in_array($option['key'], $value['extoption'])) wpl_esc::e('selected="selected"'); ?>
								value="<?php wpl_esc::attr($option['key']); ?>"><?php wpl_esc::attr($option['value']); ?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif (in_array($type, array('user_type', 'user_membership')) and !$done_this) {
	?>
	<div class="search-field-wp search-field-select <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]"
						onchange="selectChange(this, 'select');">
					<option value="select" <?php if (isset($value['type']) and $value['type'] == 'select') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Select box'); ?></option>
					<option value="multiple" <?php if (isset($value['type']) and $value['type'] == 'multiple') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Multiple SelectBox'); ?></option>
					<option value="radios" <?php if (isset($value['type']) and $value['type'] == 'radios') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Radio Buttons'); ?></option>
					<option value="radios_any" <?php if (isset($value['type']) and $value['type'] == 'radios_any') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Radio buttons with any'); ?></option>
					<option value="checkboxes" <?php if (isset($value['type']) and $value['type'] == 'checkboxes') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Check boxes'); ?></option>
				</select>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'textarea' and !$done_this) {
	?>
	<div class="search-field-wp search-field-textarea <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<?php wpl_esc::html_t('No Option Available'); ?>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif (($type == 'area' or $type == 'mmarea' or $type == 'price' or $type == 'mmprice' or $type == 'length' or $type == 'mmlength' or $type == 'volume' or $type == 'mmvolume') and !$done_this) {
	?>
	<div class="search-field-wp search-field-units <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]"
						onchange="selectChange(this, '<?php wpl_esc::js($type); ?>');">
					<option value="minmax" <?php if (isset($value['type']) and $value['type'] == 'minmax') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Min/Max textbox'); ?></option>
					<option value="minmax_slider" <?php if (isset($value['type']) and $value['type'] == 'minmax_slider') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Min/Max Slider'); ?></option>
					<option value="minmax_selectbox" <?php if (isset($value['type']) and $value['type'] == 'minmax_selectbox') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Min/Max SelectBox'); ?></option>
					<option value="minmax_selectbox_plus" <?php if (isset($value['type']) and $value['type'] == 'minmax_selectbox_plus') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('SelectBox+'); ?></option>
					<option value="minmax_selectbox_minus" <?php if (isset($value['type']) and $value['type'] == 'minmax_selectbox_minus') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('SelectBox-'); ?></option>
					<option value="minmax_selectbox_range" <?php if (isset($value['type']) and $value['type'] == 'minmax_selectbox_range') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Range'); ?></option>
				</select>
			</div>
			<div class="erow wpl_extoptions_span <?php wpl_esc::attr($value['type'] ?? ''); ?>">
				<input type="text"
					   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][extoption]"
					   value="<?php wpl_esc::attr($value['extoption'] ?? ''); ?>"
					   placeholder="<?php wpl_esc::attr_t('min,max,increment like 0,10,1'); ?>"
					   title="<?php wpl_esc::attr_t('min,max,increment like 0,10,1 (Sale)'); ?>"/>
				<?php if ($type == 'price'): ?><input type="text"
													  name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][extoption2]"
													  value="<?php wpl_esc::attr($value['extoption2'] ?? ''); ?>"
													  placeholder="<?php wpl_esc::attr_t('min,max,increment for rental listings'); ?>"
													  title="<?php wpl_esc::attr_t('min,max,increment (Rental)'); ?>" /><?php endif; ?>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'textsearch' and !$done_this) {
	?>
	<div class="search-field-wp search-field-textsearch <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]">
					<option value="textbox" <?php if (isset($value['type']) and $value['type'] == 'textbox') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Textbox'); ?></option>
					<option value="textarea" <?php if (isset($value['type']) and $value['type'] == 'textarea') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Textarea'); ?></option>
				</select>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'addon_calendar' and !$done_this) {
	?>
	<div class="search-field-wp search-field-addon-calendar <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>
		<input type="hidden" id="field_type_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]"
			   value="addon_calendar"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<?php wpl_esc::html_t('No Option Available'); ?>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'ptcategory' and !$done_this) {
	?>
	<div class="search-field-wp search-field-property-type <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<select name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][type]"
						onchange="selectChange(this,'property_types');">
					<option value="select" <?php if (isset($value['type']) and $value['type'] == "select") wpl_esc::e('selected="selected"'); ?> ><?php wpl_esc::html_t('Select box'); ?></option>
				</select>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'separator' and !$done_this) {
	?>
	<div class="search-field-wp search-field-separator <?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-id="<?php wpl_esc::attr($field->id); ?>"
		 data-status="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"
		 data-field-name="<?php wpl_esc::attr_t($field->name); ?> - <?php wpl_esc::attr_t("Separator"); ?>">

		<input type="hidden" id="field_sort_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][sort]"
			   value="<?php wpl_esc::attr($value['sort'] ?? ''); ?>"/>
		<input type="hidden" id="field_enable_<?php wpl_esc::attr($field->id); ?>" onchange="elementChanged(true);"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($field->id); ?>][enable]"
			   value="<?php wpl_esc::attr($value['enable'] ?? ''); ?>"/>

		<h4><span><?php wpl_esc::html_t($field->name); ?> - <?php wpl_esc::attr_t("Separator"); ?></span></h4>

		<div class="field-body">
			<div class="erow">
				<?php wpl_esc::html_t('No Option Available'); ?>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
}