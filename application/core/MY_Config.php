<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Config extends CI_Config {
	public function __construct() {
		parent::__construct();
	}
	/**
	 * Load Config File
	 * Tweaked by @DakuTree to allow loading from config/_secure. This is intended to stop duplication of API keys (or other settings) that shouldn't be committed.
	 *
	 * @param	string	$file			Configuration file name
	 * @param	bool	$use_sections		Whether configuration values should be loaded into their own section
	 * @param	bool	$fail_gracefully	Whether to just return FALSE or display an error message
	 * @return	bool	TRUE if the file was loaded correctly or FALSE on failure
	 */
	public function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		$file = ($file === '') ? 'config' : str_replace('.php', '', $file);
		$loaded = FALSE;

		foreach ($this->_config_paths as $path)
		{
			foreach (array($file, '_secure'.DIRECTORY_SEPARATOR.$file, ENVIRONMENT.DIRECTORY_SEPARATOR.$file) as $location)
			{
				$file_path = $path.'config/'.$location.'.php';
				if (in_array($file_path, $this->is_loaded, TRUE))
				{
					return TRUE;
				}

				if ( ! file_exists($file_path))
				{
					continue;
				}

				include($file_path);

				if ( ! isset($config) OR ! is_array($config))
				{
					if ($fail_gracefully === TRUE)
					{
						return FALSE;
					}

					show_error('Your '.$file_path.' file does not appear to contain a valid configuration array.');
				}

				if ($use_sections === TRUE)
				{
					$this->config[$file] = isset($this->config[$file])
						? array_merge($this->config[$file], $config)
						: $config;
				}
				else
				{
					$this->config = array_merge($this->config, $config);
				}

				$this->is_loaded[] = $file_path;
				$config = NULL;
				$loaded = TRUE;
				log_message('debug', 'Config file loaded: '.$file_path);
			}
		}

		if ($loaded === TRUE)
		{
			return TRUE;
		}
		elseif ($fail_gracefully === TRUE)
		{
			return FALSE;
		}

		show_error('The configuration file '.$file.'.php does not exist.');
	}

	/**
	 * Set a config file item by index
	 *
	 * @param	string	$item	Config item key
	 * @param	string	$value	Config item value
	 * @param	string	$index	Config item index
	 * @return	void
	 */
	public function set_item_by_index($item, $value, $index) : void {
		$this->config[$index][$item] = $value;
	}
}
