<?php
/*
Plugin Name: RedPen Text Validator
Plugin URI: http://redpen.cc
Description: Validates posts with RedPen while editing.
Author: Anton Keks & Takahiko Ito
Version: 0.1
*/
$redpen_proxy_url = plugins_url('/proxy.php/', __FILE__);

function add_redpen_to_edit_form() {
	global $redpen_proxy_url;
	$plugin_root = plugins_url('/', __FILE__);
	echo <<< HTML
		<script src="{$redpen_proxy_url}js/redpen.js"></script>
		<script src="{$plugin_root}js/plugin.js"></script>
		<link rel="stylesheet" type="text/css" href="{$plugin_root}css/redpen.css">
		<div class="redpen-container">
			<div class="postbox">
				<h2 class="redpen-title"></h2>
				<ol class="redpen-error-list"></ol>
			</div>
			<div class="postbox closed">
				<button type="button" class="handlediv button-link"><span class="toggle-indicator"></span></button>
				<h2 class="redpen-settings-toggle hndle">
					<span class="redpen-red">Red</span>Pen Settings
				</h2>
				<div class="inside redpen-settings">
					<ul class="redpen-validators"></ul>
				</div>
			</div>
		</div>
		<script>
		jQuery(function($) {
			$(".redpen-container").appendTo("#normal-sortables");
			window.redpenPlugin = new RedPenPlugin('$redpen_proxy_url').autoValidate('#content');
		});
		</script>
HTML;
}

function start_redpen_on_tinymce_init($settings) {
	$settings['setup'] = "function(editor) {redpenPlugin.autoValidate(editor)}";
	return $settings;
}

add_action('edit_form_advanced', 'add_redpen_to_edit_form');
add_filter('tiny_mce_before_init', 'start_redpen_on_tinymce_init');
?>
