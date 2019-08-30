<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.bobsyd.com/demos
 * @since      1.0.0
 *
 * @package    Fpi
 * @subpackage Fpi/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h1>Facebook posts import</h1>
<div id="fpi-body">
  <div class="fpi-tab">
    <button id="fpi-options-button" class="fpi-tab-link fpi-tab-current" data-tab="fpi-tab-1">
      <?php echo(esc_html__('About', 'fpi')); ?>
    </button>
    <button id="fpi-customize-button" class="fpi-tab-link" data-tab="fpi-tab-2">
      <?php echo(esc_html__('Plain JSON', 'fpi')); ?>
    </button>
    <button id="fpi-customize-button" class="fpi-tab-link" data-tab="fpi-tab-3">
      <?php echo(esc_html__('File upload', 'fpi')); ?>
    </button>
  </div>
  <?php include_once plugin_dir_path(__FILE__) . 'fpi-admin-menu-about.php'; ?>
  <?php include_once plugin_dir_path(__FILE__) . 'fpi-admin-menu-plain-json.php'; ?>
  <?php include_once plugin_dir_path(__FILE__) . 'fpi-admin-menu-file-upload.php'; ?>
</div>