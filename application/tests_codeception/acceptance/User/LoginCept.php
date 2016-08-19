<?php
	$I = new AcceptanceTester($scenario);

	$I->wantTo('Check the Login page works');
	$I->amOnPage('/user/login');
	$I->see('©2016 tracker.codeanimu.net');
	$I->see('Please Sign In');
	$I->see('Login');
	$I->see('Create an account');
	$I->see('Remember Me');
	$I->seeLink('Forgot Password?');
	$I->seeInTitle('Manga Tracker - Login');

	//TODO: Test actual login
