<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.herooutoftime.com
 * @since      1.0.0
 *
 * @package    Cf7_Statifier
 * @subpackage Cf7_Statifier/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cf7_Statifier
 * @subpackage Cf7_Statifier/admin
 * @author     Andreas Bilz <anti@herooutoftime.com>
 */
class Cf7_Statifier_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $cf7_additional_settings;

	private $static_file_enabled;

	const STATICS = array(
		'form' => 'form',
		'mail' => 'body',
		'mail_2' => 'body'
	);
	const STATIC_DIR = 'cf7_static/';
	const STATIC_EXT = '.html';
	const STATIC_PERM = 0777;
	const PROC_PERM = 0777;
	const PROC_DIR = 'cf7_proc/';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->static_path = trailingslashit(get_stylesheet_directory()) . self::STATIC_DIR;
		$this->static_path_proc = trailingslashit($this->static_path) . self::PROC_DIR;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cf7_Statifier_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cf7_Statifier_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

//		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cf7-statifier-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cf7_Statifier_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cf7_Statifier_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

//		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cf7-statifier-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function contact_form_properties($properties, $t) {
		$this->cf7_additional_settings = $properties['additional_settings'];
		if(
			filter_var(reset($this->get_cf7_additional_setting('passthrough', 1)), FILTER_VALIDATE_BOOLEAN) ||
			$_POST['action'] == 'save' ||
			!filter_var(reset($this->get_cf7_additional_setting('static_file_enabled', 1)), FILTER_VALIDATE_BOOLEAN)
		)
			return $properties;

		$statics = self::STATICS;
		foreach ($statics as $key => $prop) {
			$static_file = $this->static_path . $key . self::STATIC_EXT;
			// Check if we are dealing with a static file reference
			if(file_exists($static_file)) {
				if($key == $prop) {
					$properties[$key] = file_get_contents($static_file);
				} else {
					$properties[$key][$prop] = file_get_contents($static_file);
				}
			}
		}
		return $properties;
	}

	public function after_save($t)
	{
		$oldmask = umask(0);
		foreach (self::STATICS as $key => $prop) {
			if(!filter_var($this->get_cf7_additional_setting('static_file_' . $key, 1)[0], FILTER_VALIDATE_BOOLEAN))
				continue;
			$static_file = $key . self::STATIC_EXT;
			$static_file_path = $this->static_path . $static_file;
			$use_premailer = filter_var($this->get_cf7_additional_setting('static_file_' . $key . '_premailer', 1)[0], FILTER_VALIDATE_BOOLEAN);

			if(!is_dir($this->static_path))
				mkdir($this->static_path, self::STATIC_PERM, true);

			if(!is_writable($this->static_path)){
				/**
				 * @todo Error handling
				 */
				continue;
			}

			$meta = get_post_meta($t->id, '_' . $key, true);
			if(is_string($meta)) {
				var_dump($static_file_path);
				if(!file_put_contents($static_file_path, $meta))
					die('ERROR: Saving form file');
			} else {
				if($use_premailer)
					$this->store_processed($static_file_path, $meta[$prop], $t);
				if(!file_put_contents($static_file_path, $meta[$prop]))
					die('ERROR: Saving mail file');
			}
		}
		umask($oldmask);
	}

	public function store_processed($fp, $content, $cf)
	{
		$_proc = Premailer::html($content);
		$fi = pathinfo($fp);
		$suffix = self::PROC_DIR . $cf->id . '/';
		$proc_dir_path = trailingslashit($fi['dirname']) . $suffix;
		$proc_file_path = $proc_dir_path .  $fi['basename'];
		if(!is_dir($proc_dir_path))
			mkdir($proc_dir_path, self::PROC_PERM, true);
		file_put_contents($proc_file_path, $_proc['html']);
		return true;
	}

	public function get_cf7_additional_setting($name, $max = 1)
	{
		$count = 0;
		$values = array();
		foreach ((array)explode("\n", $this->cf7_additional_settings) as $setting) {
			if (preg_match('/^([a-zA-Z0-9_]+)[\t ]*:(.*)$/', $setting, $matches)) {
				if ($matches[1] != $name)
					continue;

				if (!$max || $count < (int)$max) {
					$values[] = trim($matches[2]);
					$count += 1;
				}
			}
		}
		return $values;
	}
}
