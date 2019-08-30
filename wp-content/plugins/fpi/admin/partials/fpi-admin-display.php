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
    <div>
        <textarea id="fpi-json-input"></textarea>
        <button id='fpi-json-single-data'>Use single test case</button>
        <button id='fpi-json-large-data'>Use large test case</button>
        <input id='fpi-json-file-upload' type='file' value='upload'/> 
        <button id='fpi-json-submit'>Submit</button>
  </div>
</div>