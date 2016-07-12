<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sekati CodeIgniter Asset Helper
 *
 * @package        Sekati
 * @author         Jason M Horwitz
 * @copyright      Copyright (c) 2013, Sekati LLC.
 * @license        http://www.opensource.org/licenses/mit-license.php
 * @link           http://sekati.com
 * @version        v1.2.7
 * @filesource
 *
 * @usage        $autoload['config'] = array('asset');
 *                $autoload['helper'] = array('asset');
 * @example        <img src="<?=asset_url();?>imgs/photo.jpg" />
 * @example        <?=img('photo.jpg')?>
 *
 * @install        Copy config/asset.php to your CI application/config directory
 *                & helpers/asset_helper.php to your application/helpers/ directory.
 *                Then add both files as autoloads in application/autoload.php:
 *
 *                $autoload['config'] = array('asset');
 *                $autoload['helper'] = array('asset');
 *
 *                Autoload CodeIgniter's url_helper in application/config/autoload.php:
 *                $autoload['helper'] = array('url');
 *
 * @notes          Organized assets in the top level of your CodeIgniter 2.x app:
 *                    - assets/
 *                        -- css/
 *                        -- download/
 *                        -- img/
 *                        -- js/
 *                        -- less/
 *                        -- swf/
 *                        -- upload/
 *                        -- xml/
 *                    - application/
 *                        -- config/asset.php
 *                        -- helpers/asset_helper.php
 */

// ------------------------------------------------------------------------
// URL HELPERS

/**
 * Get asset URL
 *
 * @access  public
 * @return  string
 */

if (!function_exists('asset_url')) {
	function asset_url()
	{
		//get an instance of CI so we can access our configuration
		$CI =& get_instance();

		//return the full asset path
		return $CI->config->slash_item('static_url')/* . $CI->config->item('asset_path')*/;
	}
}

/**
 * Get css URL
 *
 * @access  public
 * @return  string
 */
if (!function_exists('css_url')) {
	function css_url()
	{
		$CI =& get_instance();

		return $CI->config->slash_item('static_url') . $CI->config->item('css_path');
	}
}

/**
 * Get less URL
 *
 * @access  public
 * @return  string
 */
if (!function_exists('less_url')) {
	function less_url()
	{
		$CI =& get_instance();

		return $CI->config->slash_item('static_url') . $CI->config->item('less_path');
	}
}

/**
 * Get js URL
 *
 * @access  public
 * @return  string
 */
if (!function_exists('js_url')) {
	function js_url()
	{
		$CI =& get_instance();

		return $CI->config->slash_item('static_url') . $CI->config->item('js_path');
	}
}

/**
 * Get image URL
 *
 * @access  public
 * @return  string
 */
if (!function_exists('img_url')) {
	function img_url()
	{
		$CI =& get_instance();

		return $CI->config->slash_item('static_url') . $CI->config->item('img_path');
	}
}

/**
 * Get SWF URL
 *
 * @access  public
 * @return  string
 */
if (!function_exists('swf_url')) {
	function swf_url()
	{
		$CI =& get_instance();

		return $CI->config->slash_item('static_url') . $CI->config->item('swf_path');
	}
}

/**
 * Get Upload URL
 *
 * @access  public
 * @return  string
 */
if (!function_exists('upload_url')) {
	function upload_url()
	{
		$CI =& get_instance();

		return base_url() . $CI->config->item('upload_path');
	}
}

/**
 * Get Download URL
 *
 * @access  public
 * @return  string
 */
if (!function_exists('download_url')) {
	function download_url()
	{
		$CI =& get_instance();

		return base_url() . $CI->config->item('download_path');
	}
}

/**
 * Get XML URL
 *
 * @access  public
 * @return  string
 */
if (!function_exists('xml_url')) {
	function xml_url()
	{
		$CI =& get_instance();

		return base_url() . $CI->config->item('xml_path');
	}
}


// ------------------------------------------------------------------------
// PATH HELPERS

/**
 * Get asset Path
 *
 * @access  public
 * @return  string
 */
if (!function_exists('asset_path')) {
	function asset_path()
	{
		//get an instance of CI so we can access our configuration
		$CI =& get_instance();

		return FCPATH . $CI->config->item('asset_path');
	}
}

/**
 * Get CSS Path
 *
 * @access  public
 * @return  string
 */
if (!function_exists('css_path')) {
	function css_path()
	{
		//get an instance of CI so we can access our configuration
		$CI =& get_instance();

		return FCPATH . $CI->config->item('css_path');
	}
}

/**
 * Get LESS Path
 *
 * @access  public
 * @return  string
 */
if (!function_exists('less_path')) {
	function less_path()
	{
		//get an instance of CI so we can access our configuration
		$CI =& get_instance();

		return FCPATH . $CI->config->item('less_path');
	}
}

/**
 * Get JS Path
 *
 * @access  public
 * @return  string
 */
if (!function_exists('js_path')) {
	function js_path()
	{
		//get an instance of CI so we can access our configuration
		$CI =& get_instance();

		return FCPATH . $CI->config->item('js_path');
	}
}

/**
 * Get image Path
 *
 * @access  public
 * @return  string
 */
if (!function_exists('img_path')) {
	function img_path()
	{
		//get an instance of CI so we can access our configuration
		$CI =& get_instance();

		return FCPATH . $CI->config->item('img_path');
	}
}

/**
 * Get SWF Path
 *
 * @access  public
 * @return  string
 */
if (!function_exists('swf_path')) {
	function swf_path()
	{
		$CI =& get_instance();

		return FCPATH . $CI->config->item('swf_path');
	}
}

/**
 * Get XML Path
 *
 * @access  public
 * @return  string
 */
if (!function_exists('xml_path')) {
	function xml_path()
	{
		$CI =& get_instance();

		return FCPATH . $CI->config->item('xml_path');
	}
}

/**
 * Get the Absolute Upload Path
 *
 * @access  public
 * @return  string
 */
if (!function_exists('upload_path')) {
	function upload_path()
	{
		$CI =& get_instance();

		return FCPATH . $CI->config->item('upload_path');
	}
}

/**
 * Get the Relative (to app root) Upload Path
 *
 * @access  public
 * @return  string
 */
if (!function_exists('upload_path_relative')) {
	function upload_path_relative()
	{
		$CI =& get_instance();

		return './' . $CI->config->item('upload_path');
	}
}

/**
 * Get the Absolute Download Path
 *
 * @access  public
 * @return  string
 */
if (!function_exists('download_path')) {
	function download_path()
	{
		$CI =& get_instance();

		return FCPATH . $CI->config->item('download_path');
	}
}

/**
 * Get the Relative (to app root) Download Path
 *
 * @access  public
 * @return  string
 */
if (!function_exists('download_path_relative')) {
	function download_path_relative()
	{
		$CI =& get_instance();

		return './' . $CI->config->item('download_path');
	}
}


// ------------------------------------------------------------------------
// EMBED HELPERS

/**
 * Load CSS
 * Creates the <link> tag that links all requested css file
 * @access  public
 *
 * @param   string
 *
 * @return  string
 */
if (!function_exists('css')) {
	function css($file, $media = 'all')
	{
		return '<link rel="stylesheet" type="text/css" href="' . css_url() . $file . '" media="' . $media . '">' . "\n";
	}
}

/**
 * Load LESS
 * Creates the <link> tag that links all requested LESS file
 * @access  public
 *
 * @param   string
 *
 * @return  string
 */
if (!function_exists('less')) {
	function less($file)
	{
		return '<link rel="stylesheet/less" type="text/css" href="' . less_url() . $file . '">' . "\n";
	}
}

/**
 * Load JS
 * Creates the <script> tag that links all requested js file
 * @access  public
 *
 * @param          string
 * @param    array $atts Optional, additional key/value attributes to include in the SCRIPT tag
 *
 * @return  string
 */
if (!function_exists('js')) {
	function js($file, $atts = array())
	{
		$element = '<script type="text/javascript" src="' . js_url() . $file . '"';

		foreach ($atts as $key => $val)
			$element .= ' ' . $key . '="' . $val . '"';
		$element .= '></script>' . "\n";

		return $element;
	}
}

/**
 * Load Image
 * Creates an <img> tag with src and optional attributes
 * @access  public
 *
 * @param          string
 * @param    array $atts Optional, additional key/value attributes to include in the IMG tag
 *
 * @return  string
 */
if (!function_exists('img')) {
	function img($file, $atts = array())
	{
		$url = '<img src="' . img_url() . $file . '"';
		foreach ($atts as $key => $val)
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= " />\n";

		return $url;
	}
}

/**
 * Load Minified JQuery CDN w/ failover
 * Creates the <script> tag that links all requested js file
 * @access  public
 *
 * @param   string
 *
 * @return  string
 */
if (!function_exists('jquery')) {
	function jquery($version = '')
	{
		// Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline
		$out = '<script src="//ajax.googleapis.com/ajax/libs/jquery/' . $version . '/jquery.min.js"></script>' . "\n";
		$out .= '<script>window.jQuery || document.write(\'<script src="' . js_url() . 'jquery-' . $version . '.min.js"><\/script>\')</script>' . "\n";

		return $out;
	}
}

/**
 * Load Google Analytics
 * Creates the <script> tag that links all requested js file
 * @access  public
 *
 * @param   string
 *
 * @return  string
 */
if (!function_exists('google_analytics')) {
	function google_analytics($ua = '')
	{
		// Change UA-XXXXX-X to be your site's ID
		$out = "<!-- Google Webmaster Tools & Analytics -->\n";
		$out .= '<script type="text/javascript">';
		$out .= '	var _gaq = _gaq || [];';
		$out .= "    _gaq.push(['_setAccount', '$ua']);";
		$out .= "    _gaq.push(['_trackPageview']);";
		$out .= '    (function() {';
		$out .= "      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;";
		$out .= "      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';";
		$out .= "      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);";
		$out .= "    })();";
		$out .= "</script>";

		return $out;
	}
}

/* End of file asset_helper.php */
