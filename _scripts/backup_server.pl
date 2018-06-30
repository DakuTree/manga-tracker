#!/usr/bin/perl -w

##### MODULES  #####{
use File::Basename;
use Cwd qw(abs_path);

use File::Temp qw(tempdir);
use Config::Simple; #for reading config, variables etc

use POSIX qw(strftime);

use feature qw(say);

use strict;
use warnings;
# use diagnostics;
####################}

if($> != 0) { die("Script must be run as root!"); }

say "Running backup_server.pl @ ".localtime();

##### CORE VARIABLES #####{
my ($tempDir, $S3URI, $trackr_root);

my $dirname = dirname(abs_path(__FILE__));
if(!($dirname =~ /\/public_html\/_scripts$/)) { die("This is being run in an invalid location?"); }
my $trackrLocation = ($dirname =~ s/\/_scripts$//r);

my $cfg      = loadConfig();
$S3URI       = $cfg->param('backup.s3uri');
$trackr_root = $cfg->param('general.trackr_root');

$tempDir = tempdir('backup_XXXXXX', CLEANUP => 1, TMPDIR => 1);

if(!length $S3URI) { die("Config params are empty??\n"); }
$S3URI = ($S3URI =~ s/\/?$/\//r . strftime("%Y-%m-%d", localtime)."/");
#####################}

open STDERR, ">>", "/var/log/perl-error.log" or die "Can't open file for STDERR";

###### SCRIPT ######{
chdir($tempDir);

# Server Config {
system("sudo s3cmd sync --recursive --preserve /etc/letsencrypt ${S3URI}etc/");
system("sudo s3cmd sync --recursive --preserve /etc/webalizer ${S3URI}etc/");
system("sudo tar -zcvf config_files.tar.gz /etc/apache2/apache2.conf /etc/mysql /etc/php/7.2");

system("sudo s3cmd sync --recursive --preserve config_files.tar.gz ${S3URI}");
# }

system("sudo tar -zcvf virualhosts.tar.gz /etc/apache2/sites-available");
system("sudo s3cmd sync --recursive --preserve virualhosts.tar.gz ${S3URI}");

system("sudo tar --exclude=\"/var/log/journal/*\" -zcvf logs.tar.gz /var/log ${trackr_root}shared/application/logs");
system("sudo s3cmd sync --recursive --preserve logs.tar.gz ${S3URI}");

system("sudo bash -c 'dpkg --get-selections > dpkg.list'");
system("sudo s3cmd sync dpkg.list ${S3URI}");

system("sudo bash -c 'find /var/www -type f -follow -print | gzip > www_filelist.txt.gz'");
system("sudo s3cmd sync www_filelist.txt.gz ${S3URI}");

system("sudo crontab -l > crontab.cron");
system("sudo s3cmd sync crontab.cron ${S3URI}");

mkdir("mysql_dump");
my $lines = `mysql --defaults-extra-file=/etc/mysql/conf.d/mysqldump_pwd.cnf -A --skip-column-names  -e "SHOW DATABASES;"`;
$lines =~ s/^(?:Database|information_schema|performance_schema|test|sys)\n//gm;
for my $DB (split("\n", $lines)){
	system("sudo mysqldump --defaults-extra-file=/etc/mysql/conf.d/mysqldump_pwd.cnf --hex-blob --routines --triggers --quote-names --opt -E --single-transaction ${DB} | gzip > mysql_dump/${DB}.sql.gz");
}
system("sudo s3cmd sync --recursive mysql_dump ${S3URI}");

say "Finished backup @ ".strftime("%a %b %e %H:%M:%S %Z %Y", localtime);
####################}

sub loadConfig {
	my $cfgLocation = dirname(__FILE__)."/config.ini";
	if(!(-e $cfgLocation)) { die("Config file does not exist. Did you forgot to rename the default config?\n"); }
	return new Config::Simple($cfgLocation) or die Config::Simple->error();
}
