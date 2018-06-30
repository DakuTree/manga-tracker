<?php
/** https://deployer.org/
 *
 * Call via: dep -f=_scripts/deploy.php deploy
 *
 */
namespace Deployer;

if(basename(getcwd()) !== 'manga-tracker') die('Bad CWD: Call from manga-tracker with dep -f=_scripts/deploy.php deploy');

require 'recipe/cachetool.php'; //requires deployer/recipes
require 'recipe/codeigniter.php';

// Project name
set('application', 'trackr.moe');

// Project repository
set('repository', 'git@github.com:DakuTree/manga-tracker.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', FALSE);

// Fix bug on windows
set('ssh_multiplexing', false);

set('clear_paths', [
	'.docker',
	'.git',
	'.github',
	'.idea'
]);

add('copy_files', [
	'application/config/_secure/email.php',
	'application/config/_secure/mailgun.php',
	'application/config/_secure/monolog.php',
	'application/config/_secure/recaptcha.php',
	'application/config/_secure/sites.php',
	'application/config/production/database_password.php'
]);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);

set('allow_anonymous_stats', false);

// Hosts
host('trackr.moe')
	->user('deployer')
	->identityFile('~/.ssh/deploy.ppk')
	->set('deploy_path', '/var/www/trackr.moe');

// Tasks
task('build', function () {
	run('cd {{release_path}} && build');
});

//task('deploy:symlink', function() {}); //Uncomment when testing.
//task('cleanup', function() {});        //Uncomment when testing.

task('deploy:compile_assets', function() {
	// less
	//// themes
	run('( \
		cd {{release_path}}/public/assets/less && \
		exec lessc \
		--csscomb \
		--modify-var=themeLocation=common\\\\themes\\\\light \
		main.less \
		../css/main.light.css \
	)');
	run('( \
		cd {{release_path}}/public/assets/less && \
		exec lessc \
		--csscomb \
		--modify-var=themeLocation=common\\\\themes\\\\dark \
		main.less \
		../css/main.dark.css \
	)');
	//// userscript css
	run('( \
		cd {{release_path}}/public/userscripts/assets/ && \
		exec lessc \
		--csscomb \
		main.less \
		main.css \
	)');

	//js
	run('( \
		cd {{release_path}}/public/assets/js && \
		exec google-closure-compiler-js \
		--compilationLevel SIMPLE_OPTIMIZATIONS \
		{{release_path}}/public/assets/js/main.js \
		{{release_path}}/public/assets/js/pages/*.js \
		> {{release_path}}/public/assets/js/compiled.min.js \
	)');
});
after('deploy:vendors', 'deploy:compile_assets');
after('deploy:vendors', 'deploy:clear_paths');

task('deploy:copy_files', function () {
	$sharedPath = '{{deploy_path}}/shared';
	foreach (get('copy_files') as $file) {
		$dirname = dirname(parse($file));
		// Create dir of shared file
		run("mkdir -p $sharedPath/" . $dirname);
		// Check if shared file does not exist in shared.
		// and file exist in release
		if (!test("[ -f $sharedPath/$file ]") && test("[ -f {{release_path}}/$file ]")) {
			// Copy file in shared dir if not present
			run("cp -rv {{release_path}}/$file $sharedPath/$file");
		}
		// Remove from source.
		run("if [ -f $(echo {{release_path}}/$file) ]; then rm -rf {{release_path}}/$file; fi");
		// Ensure dir is available in release
		run("if [ ! -d $(echo {{release_path}}/$dirname) ]; then mkdir -p {{release_path}}/$dirname;fi");
		// Touch shared
		run("touch $sharedPath/$file");

		run("cp $sharedPath/$file {{release_path}}/$file");
	}
});
after('deploy:shared', 'deploy:copy_files');

after('deploy:symlink', 'cachetool:clear:opcache');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

