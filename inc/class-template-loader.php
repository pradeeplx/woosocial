<?php
 
if( ! class_exists( 'Gamajo_Template_Loader' ) ) {
	require plugin_dir_path( __FILE__ ) . 'vendor/class-gamajo-template-loader.php';
}

/**
 * Template loader for Meal Planner.
 *
 * Only need to specify class properties here.
 *
 * @package Meal_Planner
 * @author  Gary Jones
 */
class JCK_WooSocial_TemplateLoader extends Gamajo_Template_Loader {

	/**
	 * Prefix for filter names.
	 *
	 * @since 1.0.0
	 * @type string
	 */
	protected $filter_prefix = 'jck_woosocial';

	/**
	 * Directory name where custom templates for this plugin should be found in the theme.
	 *
	 * @since 1.0.0
	 * @type string
	 */
	protected $theme_template_directory = 'jck-woosocial';

	/**
	 * Reference to the root directory path of this plugin.
	 *
	 * Can either be a defined constant, or a relative reference from where the subclass lives.
	 *
	 * In this case, `MEAL_PLANNER_PLUGIN_DIR` would be defined in the root plugin file as:
	 *
	 * ~~~
	 * define( 'MEAL_PLANNER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	 * ~~~
	 *
	 * @since 1.0.0
	 * @type string
	 */
	protected $plugin_directory = JCK_WOOSOCIAL_PLUGIN_PATH;

}