<?php

class ProtectPageWithMultiplePasswordsCest
{
    public function loginWithFirstPassword(\FunctionalTester $I)
    {
        $I->amGoingTo('login on a protected page with the first password');

        $I->amOnPage('protect-page-with-multiple-passwords');
        $I->dontSee('Protected page');

        $I->fillField('password', 'password1');
        $I->click('Login');

        $I->see('Protected page');
    }

    public function loginWithSecondPassword(\FunctionalTester $I)
    {
        $I->amGoingTo('login on a protected page with the second password');

        $I->amOnPage('protect-page-with-multiple-passwords');
        $I->dontSee('Protected page');

        $I->fillField('password', 'password2');
        $I->click('Login');

        $I->see('Protected page');
    }

    public function protectedPageWithEmptyPasswordsArray(\FunctionalTester $I)
    {
        $I->amGoingTo('visit a protected page with an empty passwords array');

        $I->amOnPage('protect-page-with-empty-passwords-array');
        $I->see('Protected page');
    }
}
