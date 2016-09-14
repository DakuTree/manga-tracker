#!/usr/bin/perl -w

##### MODULES  #####{
use File::Basename;
use Config::Simple; #for reading config, variables etc

use strict;
use warnings;
# use diagnostics;
####################}

if($> != 0) { die("Script must be run as root!"); }

##### CORE VARIABLES #####{
my ($trackr_prod, $testing_email);

my $cfgLocation = dirname(__FILE__)."/config.ini";
if(!(-e $cfgLocation)) { die("Config file does not exist. Did you forgot to rename the default config?\n"); }
my $cfg = new Config::Simple($cfgLocation) or die Config::Simple->error();
$trackr_prod   = $cfg->param('general.trackr_prod') =~ s/\/?$//r;
$testing_email = $cfg->param('testing.email');

if(!length $trackr_prod || !length $testing_email) { die("Config params are empty??\n"); }
#####################}

open STDERR, ">>", "/var/log/perl-error.log" or die "Can't open file for STDERR";

###### SCRIPT ######{

#Run the tests and grab the output
my $test_output = `php -dxdebug.profiler_enable=off ${trackr_prod}/vendor/phpunit/phpunit/phpunit --bootstrap ${trackr_prod}/application/tests/Bootstrap.php --configuration ${trackr_prod}/application/tests/phpunit.xml Site_Model_test ${trackr_prod}/application/tests/models/Sites_Model_test.php`;

if(index($test_output, "FAILURES") == -1) {
	system("mailx -s 'Trackr.moe DailyTest: SUCCESS' < /dev/null '${testing_email}' &");
} else {
	open MAIL, "| mailx -s 'Trackr.moe DailyTest: FAILURE' '${testing_email}'";
	print MAIL $test_output;
	close MAIL;
}

####################}
