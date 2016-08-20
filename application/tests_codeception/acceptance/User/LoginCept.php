<?php
	$I = new AcceptanceTester($scenario);

	$I->wantTo('Check the Login page works');
	$I->amOnPage('/user/login');
	$I->see('2016 tracker.codeanimu.net');
	$I->see('Please Sign In');
	$I->seeElement('input', ['value' => 'Login']);
	$I->see('Create an account');
	$I->see('Remember Me');
	$I->seeLink('Forgot Password?');
	$I->seeInTitle('Manga Tracker - Login');

	$I->wantTo('Check if Login form works');
	$I->amOnPage('/user/login');
	$I->fillField('#identity', 'administrator');
	$I->fillField('#password', 'password');
	$I->click('input[type=submit]');

	$I->see('Delete Selected');
