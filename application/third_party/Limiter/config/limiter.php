<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Rate Limiter Configuration
 *
 */

$config['table']         = 'rate_limit';
$config['base_limit']    = 0;    // Infinite
$config['header_show']   = TRUE; // Should a rate limit info header be injected?
$config['header_prefix'] = 'X-RateLimit-';
$config['whitelist']     = array('127.0.0.1'); // Clients ignored by the limiter

/**
 * Pick this with care!
 * Some checksum algorithms have a very low entropy such as adler32.
 *
 * See http://php.net/manual/en/function.hash.php#89574 for a good
 * benchmark. Please note that not all algorithms are supported by
 * all platforms.
 */
$config['checksum_algorithm'] = 'md4';
