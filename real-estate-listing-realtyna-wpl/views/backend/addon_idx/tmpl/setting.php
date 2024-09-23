<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path.'.scripts.js');
?>
<div class="wpl-idx-addon wrap wpl-wp settings-wp">
    <div class="wpl-idx-wizard-main wpl-idx-valid">
        <header>
            <div id="icon-settings" class="icon48"></div>
            <h2><?php wpl_esc::html_t('Organic IDX / Settings'); ?></h2>
        </header>
        <section class="sidebar-wp">
          <div class="wpl_idx_servers_list"><div class="wpl_show_message_idx"></div></div>
          <table id="wpl-idx-setting-table" class="wpl-idx-addon-table">
              <thead>
                  <tr class="wpl-idx-addon-table-row">
                      <td class="wpl-idx-addon-table-title" colspan="3">
                          <?php wpl_esc::html_t('MLS Provider'); ?>
                      </td>
                      <td class="wpl-idx-addon-table-title">
                          <?php wpl_esc::html_t('Status'); ?>
                      </td>
                      <td class="wpl-idx-addon-table-title">
                          <?php wpl_esc::html_t('Actions'); ?>
                      </td>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td colspan="5">
                          <div class="message">
                              <?php wpl_esc::html_t('No MLS Provider is Found! In order to add one please ') ?>
							  <a href="<?php wpl_esc::url(wpl_global::get_wpl_admin_menu('wpl_addon_idx')) ?>">
								  <?php wpl_esc::html_t('Click here') ?>
							  </a>
                          </div>
                      </td>
                  </tr>
              </tbody>
          </table>
        </section>
    </div>
</div>