#!/usr/bin/perl -w

##### MODULES  #####{
use File::Basename;
use Cwd qw(abs_path);

use File::Slurp;

use feature 'say';

use strict;
use warnings;
# use diagnostics;
####################}

if($> != 0) { die("Script must be run as root!"); }

my $trackrLocation = dirname(abs_path(__FILE__)) =~ s/[\/|\\]_scripts$//r;

my $userscriptFile = read_file($trackrLocation."/public/userscripts/manga-tracker.dev.user.js");
my $constantFile   = read_file($trackrLocation."/application/config/constants.php");

my $userscriptVersion = ($1 =~ s/^\s+|\s+$//gr) if $userscriptFile =~ /\/\/ \@version[\s]*(.*)/;

$constantFile =~ s/define\('USERSCRIPT_VERSION', '[0-9\.]+'\);/define\('USERSCRIPT_VERSION', '$userscriptVersion'\);/;


overwrite_file($trackrLocation."/application/config/constants.php", {binmode => ':utf8'}, $constantFile);