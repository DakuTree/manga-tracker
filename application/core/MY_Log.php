<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * CodeIgniter Monolog Plus
 *
 * Version 1.4.3
 * (c) Josh Highland <JoshHighland@venntov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Monolog\Logger;
use Monolog\ErrorHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\NewRelicHandler;
use Monolog\Handler\HipChatHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\IntrospectionProcessor;


/**
 *  replaces CI's Logger class, use Monolog instead
 *
 *  see https://github.com/stevethomas/codeigniter-monolog & https://github.com/Seldaek/monolog
 *
 */
class MY_Log extends CI_Log {
	// CI log levels
	protected $_levels = array(
		'OFF' => '0',
		'ERROR' => '1',
		'DEBUG' => '2',
		'INFO' => '3',
		'ALL' => '4'
	);

	// config placeholder
	protected $config = array();

	private $log;

	/**
	 * prepare logging environment with configuration variables
	 */
	public function __construct() {
		$file_path = APPPATH.'config/monolog.php';
		$found = FALSE;
		if (file_exists($file_path)) {
			$found = TRUE;
			require($file_path);
		}

		// Is the config file in the environment folder?
		if (file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/monolog.php')) {
			require($file_path);
		} elseif (!$found) {
			exit('monolog.php config does not exist');
		}

		// Is the config file in the _secure folder?
		if (file_exists($file_path = APPPATH.'config/_secure/monolog.php')) {
			require($file_path);
		} elseif (!$found) {
			exit('monolog.php config does not exist');
		}

		// make $config from config/monolog.php accessible to $this->write_log()
		$this->config = $config;

		$this->log = new Logger($config['channel']);
		// detect and register all PHP errors in this log hence forth
		ErrorHandler::register($this->log);

		if ($this->config['introspection_processor'])
		{
			// add controller and line number info to each log message
			// 2 = depth in the stacktrace to ignore. This gives us the file
			// making the call to log_message();
			$this->log->pushProcessor(new IntrospectionProcessor(Logger::DEBUG, [], 2));
		}

		// decide which handler(s) to use
		foreach ($this->config['handlers'] as $value)
		{
			switch ($value)
			{
				case 'file':
					$handler = new RotatingFileHandler($this->config['file_logfile']);
					$formatter = new LineFormatter(null, null, $config['file_multiline']);
					$handler->setFormatter($formatter);
					break;

				case 'ci_file':
					$handler = new RotatingFileHandler($this->config['ci_file_logfile']);
					$formatter = new LineFormatter("%level_name% - %datetime% --> %message% %extra%\n", null, $config['ci_file_multiline']);
					$handler->setFormatter($formatter);
					break;

				case 'new_relic':
					$handler = new NewRelicHandler(Logger::ERROR, true, $this->config['new_relic_app_name']);
					break;

				case 'hipchat':
					$handler = new HipChatHandler(
						$config['hipchat_app_token'],
						$config['hipchat_app_room_id'],
						$config['hipchat_app_notification_name'],
						$config['hipchat_app_notify'],
						$config['hipchat_app_loglevel']
					);
					break;

				case 'stderr':
					$handler = new StreamHandler('php://stderr');
					break;

				case 'papertrail':
					$handler = new SyslogUdpHandler($this->config['papertrail_host'], $this->config['papertrail_port']);
					$formatter = new LineFormatter('%channel%.%level_name%: %message% %extra%', null, $config['papertrail_multiline']);
					$handler->setFormatter($formatter);
					break;

				case 'cli':
					if(is_cli()) {
						$handler = new StreamHandler('php://stdout');
					}
					break;

				default:
					exit('log handler not supported: ' . $value . "\n");
			}

			$this->log->pushHandler($handler);
		}

		$this->write_log('DEBUG', 'Monolog replacement logger initialized');
	}


	/**
	 * Write to defined logger. Is called from CodeIgniters native log_message()
	 *
	 * @param string $level
	 * @param $msg
	 * @return bool
	 */
	public function write_log($level = 'error', $msg)
	{
		$level = strtoupper($level);

		// verify error level
		if (!isset($this->_levels[$level]))
		{
			$this->log->addError('unknown error level: ' . $level);
			$level = 'ALL';
		}

		// filter out anything in $this->config['exclusion_list']
		if (!empty($this->config['exclusion_list']))
		{
			foreach ($this->config['exclusion_list'] as $findme)
			{
				$pos = strpos($msg, $findme);
				if ($pos !== false)
				{
					// just exit now - we don't want to log this error
					return true;
				}
			}
		}

		if ($this->_levels[$level] <= $this->config['threshold'])
		{
			switch ($level)
			{
				case 'ERROR':
					$this->log->addError($msg);
					break;

				case 'DEBUG':
					$this->log->addDebug($msg);
					break;

				case 'ALL':
				case 'INFO':
					$this->log->addInfo($msg);
					break;
			}
		}
		return true;
	}

}
