<?php

class LoginPageCest
{
    public function loginPageWithMissingRefParam(FunctionalTester $I)
    {
        $I->amGoingTo('visit the login page without a ref query param');

        $I->seeExceptionThrown(function() use ($I) {
            $I->amOnPage('template-guard/login');
        });
    }

    public function loginPageWithValidRefParam(FunctionalTester $I)
    {
        $I->amGoingTo('visit the login page with a valid ref query param');

        $validUrl = Craft::$app->security->hashData(
            'http://expected-url.com',
            Craft::$app->getConfig()->getGeneral()->securityKey,
        );

        $I->amOnPage("template-guard/login?ref={$validUrl}");
        $I->see('Protected');
    }

    public function loginPageWithTamperedRefParam(FunctionalTester $I)
    {
        $I->amGoingTo('visit the login page with a tampered ref query param');

        $validUrl = Craft::$app->security->hashData(
            'http://expected-url.com',
            Craft::$app->getConfig()->getGeneral()->securityKey,
        );

        $tamperedUrl = str_replace('expected-url.com', 'tampered-url.com', $validUrl);

        $I->seeExceptionThrown(function() use ($I, $tamperedUrl) {
            $I->amOnPage("template-guard/login?ref={$tamperedUrl}");
        });
    }
}
