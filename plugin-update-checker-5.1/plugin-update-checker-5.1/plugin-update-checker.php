<?php 
  require plugin_dir_path( __FILE__ ) . 'plugin-update-checker-5.1/plugin-update-checker.php';
  
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
require 'E:\xamp\htdocs\theme\wp-content\plugins\currency-price-checker/plugin-update-checker-5.1/plugin-update-checker.php';
$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/MohammadHasanZare/currency-price-checker/blob/main/plugin-update-checker-5.1/plugin-update-checker-5.1/examples/plugin.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'unique-plugin-or-theme-slug'
);
/**
 * Plugin Update Checker Library 5.1
 * http://w-shadow.com/
 *
 * Copyright 2022 Janis Elsts
 * Released under the MIT license. See license.txt for details.
 */

require dirname(__FILE__) . '/load-v5p1.php';
