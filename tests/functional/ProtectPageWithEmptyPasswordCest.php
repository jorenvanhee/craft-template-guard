<?php

class ProtectPageWithEmptyPasswordCest
{
    public function protectedPageWithEmptyPassword(FunctionalTester $I)
    {
        $I->amGoingTo('visit a protected page with an empty password');

        $I->amOnPage('protect-page-with-empty-password');
        $I->see('Protected page');
    }
}
