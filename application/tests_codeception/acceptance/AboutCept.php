<?php
	$I = new AcceptanceTester($scenario);

	$I->wantTo('Check the About Page works');
	$I->amOnPage('/about');
	$I->see('This is a site dedicated to tracking manga across multiple aggregate sites.');
	$I->see('©2016 tracker.codeanimu.net');
	$I->seeInTitle('Manga Tracker - About');
