<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.herooutoftime.com
 * @since      1.0.0
 *
 * @package    Cf7_Statifier
 * @subpackage Cf7_Statifier/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cf7_Statifier
 * @subpackage Cf7_Statifier/public
 * @author     Andreas Bilz <anti@herooutoftime.com>
 */
class Cf7_Statifier_Public {

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

	public $processed;

	const STATICS = array(
		'form' => 'form',
		'mail' => 'body',
		'mail_2' => 'body'
	);
	const STATIC_DIR = 'cf7_static/';
	const STATIC_EXT = '.html';
	const STATIC_TXT = '.txt';
	const STATIC_PERM = 0777;
	const PROC_PERM = 0777;
	const PROC_DIR = 'cf7_proc/';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->static_path = trailingslashit(get_stylesheet_directory()) . self::STATIC_DIR;
		$this->static_path_proc = trailingslashit($this->static_path) . self::PROC_DIR;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cf7-statifier-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cf7-statifier-public.js', array( 'jquery' ), $this->version, false );

	}

//	public function phpmailer_init($phpmailer)
//	{
////		var_dump($phpmailer);
////		var_dump($this->processed);
////		$phpmailer->AltBody = "plain text\nplain text\nplain text";
//	}

	public function mail_components($cmps, $form, $t)
	{
		$contents = $this->get_processed($form);
		$name = $t->name();
		$mail_props = $form->prop($name);

		$args = array(
			'html' => $mail_props['use_html'],
			'exclude_blank' => $mail_props['exclude_blank']
		);
		foreach($contents[$name] as $type => $content) {
			$contents[$name][$type] = wpcf7_mail_replace_tags( $content, $args );
		}
		$cmps['body'] = array();
		$cmps['body'] = $contents[$name];
		return $cmps;
	}

	public function before_send_mail($cf)
	{
		$properties = $cf->get_properties();
		$processed = $this->get_processed($cf);
		$this->cf_id = $cf->id();
		$this->processed = $processed;
		foreach ($processed as $key => $item) {
			if(!$item) continue;
			$properties[$key]['body'] = $item['text/html'];
		}
		$properties['additional_settings'] = $properties['additional_settings'] . PHP_EOL . 'passthrough:yes';
		$cf->set_properties($properties);
		return true;
	}

	public function get_processed($cf)
	{
		if(!filter_var($cf->additional_setting('static_file_enabled')[0], FILTER_VALIDATE_BOOLEAN))
			return false;

		$proc_path = trailingslashit($this->static_path . $cf->id() . '/' . self::PROC_DIR);
		$contents = array();
		foreach (self::STATICS as $key => $item) {
			$fp = $proc_path . $key . self::STATIC_EXT;
			$contents[$key]['text/html'] = false;
			if(file_exists($fp)) {
				$contents[$key]['text/html'] = file_get_contents($fp);
			}
			$fp_txt = $proc_path . $key . self::STATIC_TXT;
			$contents[$key]['text/plain'] = false;
			if(file_exists($fp_txt)) {
				$contents[$key]['text/plain'] = file_get_contents($fp_txt);
			}
		}
		return $contents;

	}

}
