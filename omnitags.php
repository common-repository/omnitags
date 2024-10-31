<?php
	/*
	Plugin Name:    Omnitags
	Description:    Simplifies the insertion of scripts and meta data in the source code, such as SEO meta data, analytics scripts, comments and live chat services, CRM tracking, marketing automation, widgets, ...
	Version:        1.2.1
	Author:         Optima Lab
	Author URI:     https://www.optima-lab.net/
	Text Domain:    omnitags
	License:        GPLv2 or later
	License URI:    https://www.gnu.org/licenses/gpl-2.0.html
	Copyright 2018-2019 Optima Lab (johannfrot@gmail.com)

	Omnitags is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	any later version.

	Omnitags is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with Omnitags. If not, see {License URI}.
	*/

//define( 'OMNITAGS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
//require_once(OMNITAGS__PLUGIN_DIR . 'class.omnitags.php');

	if ( !class_exists( 'omnitags' ) ) {

		class omnitags
		{

			function omnitags_install()
			{
				global $wpdb;
				$tb_omnitags_config = $wpdb->prefix.'omnitags_config';

				if ($wpdb->get_var("SHOW TABLES LIKE '$tb_omnitags_config'") != $tb_omnitags_config) {
					$sql = "CREATE TABLE `$tb_omnitags_config` (
                    `field_key` VARCHAR(100) NOT NULL PRIMARY KEY,
                    `value` VARCHAR(1000) NOT NULL,
                    `wp_hook` VARCHAR(45) NOT NULL
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
					require_once(ABSPATH.'wp-admin/includes/upgrade.php');
					dbDelta($sql);
				}
			}

			function init()
			{
				$my_omnitags_admin_page = add_menu_page(
					'Omnitags',
					'Omnitags',
					'administrator',
					'omnitags',
					'omnitags_admin_page',
					'',
					100
				);
				add_action( "load-".$my_omnitags_admin_page, array($this, 'omnitags_admin_header' ) );
			}

			function omnitags_admin_header()
			{
				wp_enqueue_style("admin_omnitags_bootstrap_css", plugins_url("assets/bootstrap/css/bootstrap.min.css", __FILE__));
				wp_enqueue_style("admin_omnitags_bootstrap_theme_css", plugins_url("assets/bootstrap/css/bootstrap-theme.min.css", __FILE__));
				wp_enqueue_style("admin_omnitags_css", plugins_url("css/admin-omnitags.min.css", __FILE__));
				wp_enqueue_script("admin_omnitags_bootstrap_js", plugins_url("assets/bootstrap/js/bootstrap.min.js", __FILE__));
				wp_enqueue_script("admin_omnitags_js", plugins_url("js/admin-omnitags.min.js", __FILE__), array("jquery"));

				wp_localize_script('admin_omnitags_js', 'omnitags_key_value', array(
					'ajaxurl'  => admin_url('admin-ajax.php'),
					'action'	=> 'omnitags_save',
					'nonce'		=> wp_create_nonce('omnitags_nonce')
				));
			}

			function omnitags_select($wp_hook = null){
				global $wpdb;
				$tb_omnitags_config = $wpdb->prefix.'omnitags_config';

				if (isset($wp_hook) && $wp_hook != "") {
					$wp_hook = "%;".$wp_hook.";%";
					$results =  $wpdb->get_results($wpdb->prepare("SELECT field_key, value FROM $tb_omnitags_config WHERE wp_hook LIKE %s", $wp_hook));
				}
				else
				{
					$results =  $wpdb->get_results("SELECT field_key, value FROM $tb_omnitags_config");
				}

				return $results;
			}

			function omnitags_ajax_save() {
				check_ajax_referer('omnitags_nonce', 'nonce');

				global $wpdb;
				$tb_omnitags_config = $wpdb->prefix.'omnitags_config';
				$field_key = (isset($_POST['field_key']) ? trim($_POST['field_key']) : "");
				$value = (isset($_POST['value']) ? stripslashes(trim($_POST['value'])) : "");
				$wp_hook = (isset($_POST['wp_hook']) ? trim($_POST['wp_hook']) : "");

				$nbResults = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(field_key) FROM $tb_omnitags_config WHERE field_key = %s", $field_key) );

				if ($nbResults == 0) {
					$wpdb->insert(
						$tb_omnitags_config,
						array(
							'field_key' => $field_key,
							'value' => $value,
							'wp_hook' => $wp_hook
						),
						array(
							'%s',
							'%s',
							'%s'
						)
					);
				}
				else
				{
					if ($value != "") {
						$wpdb->update(
							$tb_omnitags_config,
							array(
								'value' => $value,    // string
								'wp_hook' => $wp_hook    // string
							),
							array('field_key' => $field_key),
							array(
								'%s',    // value1
								'%s'    // value2
							),
							array('%s')
						);
					}
					else
					{
						$wpdb->delete(
							$tb_omnitags_config,
							array('field_key' => $field_key),
							array('%s')
						);
					}
				}
			}

			public static function searchForFieldValue($field_key, $array) {
				foreach ($array as $config_row) {
					if ($config_row->field_key === $field_key) {
						return $config_row->value;
					}
				}
				return null;
			}

			function omnitags_insert_scripts__wp_head(){
				$this->omnitags_merge_tags("wp_head");
			}

			function omnitags_insert_scripts__wp_print_footer_scripts(){
				$this->omnitags_merge_tags("wp_print_footer_scripts");
			}

			function omnitags_merge_tags($wp_hook){
				$saved_config = $this->omnitags_select($wp_hook);
				if (count($saved_config) > 0) {
					foreach ($saved_config as $config) {
						${"omnitags_".$config->field_key} = $config->value;
					}
					include(__DIR__."/inc/scripts_".$wp_hook.".inc.php");
				}
			}
		}


		$inst_omnitags = new omnitags();
		register_activation_hook( __FILE__, array($inst_omnitags, 'omnitags_install' ) );
		add_action( "admin_menu", array($inst_omnitags, 'init' ) );

		if(isset($_POST['action'])){
			add_action( 'wp_ajax_omnitags_save', array($inst_omnitags, 'omnitags_ajax_save' ));
		}

		add_action ( 'wp_head', array($inst_omnitags, 'omnitags_insert_scripts__wp_head' ));
		add_action ( 'wp_print_footer_scripts', array($inst_omnitags, 'omnitags_insert_scripts__wp_print_footer_scripts' ));
	}

	function omnitags_admin_page()
	{
		require_once(__DIR__."/omnitags_admin.php");
	}
	//}