<?php

use jorenvanhee\templateguard\records\LoginAttempt;

class LogoutCest
{
    public function logout(\FunctionalTester $I)
    {
        $I->amGoingTo('log in');

        $I->amOnPage('protect-page');
        $I->fillField('password', 'password');
        $I->click('Login');
        $I->see('Protected page');

        $I->amGoingTo('log out');
        $I->click('#log_out');
        $I->dontSee('Protected page');
    }

    public function logoutSpecificKey(\FunctionalTester $I)
    {
        $I->amGoingTo('log in');

        $I->amOnPage('protect-page');
        $I->fillField('password', 'password');
        $I->click('Login');
        $I->see('Protected page');

        $I->amGoingTo('log out');
        $I->click('#log_out_specific_key');
        $I->dontSee('Protected page');
    }

    public function logoutIncorrectKey(\FunctionalTester $I)
    {
        $I->amGoingTo('log in');

        $I->amOnPage('protect-page');
        $I->fillField('password', 'password');
        $I->click('Login');
        $I->see('Protected page');

        $I->amGoingTo('log out');
        $I->click('#log_out_incorrect_key');
        $I->see('Protected page');
    }
}
