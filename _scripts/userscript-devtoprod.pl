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

my $trackrLocation = dirname(abs_path(__FILE__)) =~ s/[\\|\/]_scripts$//r;

my $userscriptDev  = read_file($trackrLocation."/public/userscripts/manga-tracker.dev.user.js");
my $userscriptProd = ($userscriptDev =~ s/dev\.trackr\.moe/trackr\.moe/rg);

my $goodReplace  = "=== 'https://dev.trackr.moe'";
my $badReplace = "=== 'https://trackr.moe'";
$userscriptProd =~ s/$badReplace/$goodReplace/g;

overwrite_file($trackrLocation."/public/userscripts/manga-tracker.user.js", {binmode => ':utf8'}, $userscriptProd);
