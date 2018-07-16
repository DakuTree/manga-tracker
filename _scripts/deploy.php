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
	'_scripts/config.ini',
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
//task('deploy:symlink', function() {}); //Uncomment when testing.
//task('cleanup', function() {});        //Uncomment when testing.

task('deploy:compile_assets', function() {
	// less
	//// themes
	writeln('➤➤ <comment>Compiling Themes</comment>');
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
	writeln('➤➤ <comment>Compiling Userscript CSS</comment>');
	run('( \
		cd {{release_path}}/public/userscripts/assets/ && \
		exec lessc \
		--csscomb \
		main.less \
		main.css \
	)');

	//js
	writeln('➤➤ <comment>Compiling JavaScript</comment>');
	run('( \
		cd {{release_path}}/public/assets/js && \
		exec google-closure-compiler-js \
		--compilationLevel SIMPLE_OPTIMIZATIONS \
		{{release_path}}/public/assets/js/main.js \
		{{release_path}}/public/assets/js/pages/*.js \
		> {{release_path}}/public/assets/js/compiled.min.js \
	)');

	//icons
	writeln('➤➤ <comment>Compiling Icons</comment>');
	run('( \
		cd {{release_path}} && \
		php -r "include \'_scripts/SpritesheetGenerator.php\'; (new SpriteSheet(\'site\', FALSE))->generate();" && \
		php -r "include \'_scripts/SpritesheetGenerator.php\'; (new SpriteSheet(\'time\', FALSE))->generate();"
	)');
});

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

task('deploy:migrate_db', function () {
	// Migration is disabled by default on production, so we need to toggle it temporally.
	run('( \
		cd {{release_path}} && \
		sed -i -r "s/(migration_enabled.*?)FALSE/\1TRUE/g" application/config/production/migration.php && \
		CI_ENV="production" php public/index.php admin/migrate && \
		sed -i -r "s/(migration_enabled.*?)TRUE/\1FALSE/g" application/config/production/migration.php \
	)');
});

task('deploy:maintenance_enable', function () {
	//define('MAINTENANCE', FALSE);
	run('( \
		cd {{release_path}} && \
		sed -i -r "s/(\'MAINTENANCE\',) FALSE/\1 TRUE/" public/index.php \
	)');
});
task('deploy:maintenance_disable', function () {
	//define('MAINTENANCE', FALSE);
	run('( \
		cd {{release_path}} && \
		sed -i -r "s/(\'MAINTENANCE\',) TRUE/\1 FALSE/" public/index.php \
	)');
});

// Events
after('deploy:vendors', 'deploy:compile_assets');
after('deploy:vendors', 'deploy:clear_paths');

after('deploy:shared', 'deploy:copy_files');

before('deploy:symlink', 'deploy:maintenance_enable');
after('deploy:symlink', 'deploy:migrate_db');
after('deploy:migrate_db', 'cachetool:clear:opcache');

after('deploy', 'deploy:maintenance_disable');
after('deploy:failed', 'deploy:unlock'); // [Optional] if deploy fails automatically unlock.

//TODO: After deploy:success, ask if we want to send a tweet and/or update notices?
//      https://deployer.org/docs/api
