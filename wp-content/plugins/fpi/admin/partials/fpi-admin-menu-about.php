<?php

/**
 * Admin template
 *
 * @link       
 * @since      1.0.0
 *
 * @package    FPI
 * @subpackage FPI/admin/partials
 */
?>

<?php
  if ( ! defined( 'WPINC' ) ) {
  	die;
  }
?>

<div class="fpi-tab-content " id="fpi-tab-1">
  <h2 class="rmp-admin-title"><?php echo ( esc_html__( 'About Plugin', 'fpi' ) ); ?></h2>
  <p>
    <?php echo ( esc_html__( 'This is my plugin', 'fpi' ) ); ?>,
  </p>
</div>

