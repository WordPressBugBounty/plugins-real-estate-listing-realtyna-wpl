<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="pwizard-section">
        <?php
            $flex = new wpl_flex();
            $flex->kind = $this->kind;
            $flex->generate_wizard_form($this->fields, $this->values, $this->property_id, $this->finds, $this->nonce);
        ?>
    <?php /** including a custom file **/ $this->_wpl_import($this->tpl_path . '.custom.listing' . $this->field_category->id); ?>
</div>