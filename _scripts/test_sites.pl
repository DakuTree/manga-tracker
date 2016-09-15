#!/usr/bin/perl -w

##### MODULES  #####{
use File::Basename;
use Cwd qw(abs_path);

use Config::Simple; #for reading config, variables etc

use strict;
use warnings;
# use diagnostics;
####################}

if($> != 0) { die("Script must be run as root!"); }

print "Running test_sites.pl @ ".localtime()."\n";

##### CORE VARIABLES #####{
my ($testing_email);

my $dirname = dirname(abs_path(__FILE__));
if(!($dirname =~ /\/public_html\/_scripts$/)) { die("This is being run in an invalid location?"); }
my $trackrLocation = ($dirname =~ s/\/_scripts$//r);

my $cfgLocation = dirname(__FILE__)."/config.ini";
if(!(-e $cfgLocation)) { die("Config file does not exist. Did you forgot to rename the default config?\n"); }
my $cfg = new Config::Simple($cfgLocation) or die Config::Simple->error();
$testing_email = $cfg->param('testing.email');

if(!length $testing_email) { die("Config params are empty??\n"); }
#####################}

open STDERR, ">>", "/var/log/perl-error.log" or die "Can't open file for STDERR";

###### SCRIPT ######{

#Run the tests and grab the output
my $test_output = `php -dxdebug.profiler_enable=off ${trackrLocation}/vendor/phpunit/phpunit/phpunit --bootstrap ${trackrLocation}/application/tests/Bootstrap.php --configuration ${trackrLocation}/application/tests/phpunit.xml Site_Model_test ${trackrLocation}/application/tests/models/Sites_Model_test.php`;

if(index($test_output, "FAILURES") == -1) {
	system("mailx -s 'Trackr.moe DailyTest: SUCCESS' < /dev/null '${testing_email}' &");
} else {
	open MAIL, "| mailx -s 'Trackr.moe DailyTest: FAILURE' '${testing_email}'";
	print MAIL $test_output;
	close MAIL;
}

####################}
