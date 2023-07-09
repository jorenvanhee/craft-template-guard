<?php

use jorenvanhee\templateguard\records\LoginAttempt;

class ProtectPageCest
{
    public function _after()
    {
        LoginAttempt::deleteAll();
    }

    public function protectPage(\FunctionalTester $I)
    {
        $I->amGoingTo('visit a protected page');

        $I->amOnPage('protect-page');
        $I->dontSee('Protected page');
        $I->seeInCurrentUrl('template-guard/login');
        $I->seeHttpHeader('X-Robots-Tag', 'noindex');
    }

    public function login(\FunctionalTester $I)
    {
        $I->amGoingTo('login on a protected page');

        $I->amOnPage('protect-page');
        $I->dontSee('Protected page');

        $I->fillField('password', 'password');
        $I->click('Login');

        $I->see('Protected page');
    }

    public function loginWithIncorrectPassword(\FunctionalTester $I)
    {
        $I->amGoingTo('login on a protected page with an incorrect password');

        $I->amOnPage('protect-page');
        $I->dontSee('Protected page');

        $I->fillField('password', 'wrong-password');
        $I->click('Login');

        $I->dontSee('Protected page');
        $I->see('Invalid password.');
    }

    public function maxLoginAttempts(\FunctionalTester $I)
    {
        $I->amGoingTo('login too many times with an incorrect password');

        $I->amOnPage('protect-page');
        $I->dontSee('Protected page');

        // 5 attempts
        for ($i = 1; $i <= 5; $i++) {
            $I->fillField('password', 'wrong-password');
            $I->click('Login');

            $I->see('Invalid password.');
        }

        // 6th attempt
        $I->fillField('password', 'wrong-password');
        $I->click('Login');

        $I->see('Too many attempts, try again later.');
    }

    public function loginOnLastAttempt(\FunctionalTester $I)
    {
        $I->amGoingTo('login with the correct password on my last attempt');

        $I->amOnPage('protect-page');
        $I->dontSee('Protected page');

        // 4 attempts
        for ($i = 1; $i <= 4; $i++) {
            $I->fillField('password', 'wrong-password');
            $I->click('Login');

            $I->see('Invalid password.');
        }

        // 5th attempt
        $I->fillField('password', 'password');
        $I->click('Login');

        $I->see('Protected page');
    }

    public function bypassMaxLoginAttempts(\FunctionalTester $I)
    {
        $I->amGoingTo('fail at bypassing the max login attempts protection');

        $I->amOnPage('https://localhost/protect-page');
        $I->dontSee('Protected page');

        // 5 attempts
        for ($i = 1; $i <= 5; $i++) {
            $I->fillField('password', 'wrong-password');
            $I->click('Login');
        }

        $I->amGoingTo('add a param to the url and try to bypass the max login attempts');
        $I->amOnPage('https://localhost/protect-page?param=1');

        // 6th attempt on same url with different query param
        $I->fillField('password', 'wrong-password');
        $I->click('Login');

        $I->see('Too many attempts, try again later.');
    }
}
