<?php

namespace jorenvanhee\templateguard\services;

use Craft;
use craft\helpers\UrlHelper;
use jorenvanhee\templateguard\Plugin;
use yii\base\Component;
use yii\web\Cookie;

class GuardService extends Component
{
    const COOKIE_NAME = 'CraftTemplateGuard';
    const MAX_KEYS = 10;

    public function protect(array $passwords = [], ?string $key = null)
    {
        $key = $this->_generateKey($key);
        $passwordAttempt = Craft::$app->request->getParam('password');
        $passwords = array_filter($passwords);

        if (empty($passwords) || $this->_loggedIn($key)) {
            return;
        }

        if ($passwordAttempt) {
            return $this->_login($key, $passwords, $passwordAttempt);
        }

        $this->_redirectToLoginPage();
    }

    private function _generateKey(?string $key = null): string
    {
        // The length of the key will be 40 chars (sha1). We'll use this
        // predictable length to check how many keys we can store in
        // one cookie.
        return sha1($key ?: $this->_defaultKey());
    }

    private function _loggedIn(string $key): bool
    {
        return in_array($key, $this->_getKeysFromCookie(), true);
    }

    private function _redirectToLoginPage()
    {
        $params = ['ref' => $this->_protectedUrl()];
        $route = Plugin::getInstance()->getSettings()->loginRoute;

        $loginUrl = UrlHelper::siteUrl($route, $params);

        Craft::$app->response->redirect($loginUrl);
        Craft::$app->end();
    }

    private function _login(
        string $key,
        array $passwords,
        string $passwordAttempt
    ) {
        $loginAttempts = Plugin::getInstance()->loginAttempt;

        if (!Craft::$app->request->validateCsrfToken()) {
            $this->_setError('Invalid CSRF token.');

            return $this->_redirectToLoginPage();
        }

        if ($loginAttempts->tooMany($key)) {
            $this->_setError('Too many attempts, try again later.');

            return $this->_redirectToLoginPage();
        }

        if (!in_array($passwordAttempt, $passwords)) {
            $loginAttempts->register($key);

            $this->_setError('Invalid password.');

            return $this->_redirectToLoginPage();
        }

        $this->_addToCookie($key);
    }

    private function _defaultKey(): string
    {
        return $this->_protectedUrl();
    }

    private function _protectedUrl()
    {
        return Craft::$app->request->absoluteUrl;
    }

    private function _setError(string $error)
    {
        Craft::$app->session->setFlash('error', Craft::t('template-guard', $error));
    }

    private function _addToCookie(string $key)
    {
        $keys = array_slice([
            ...$this->_getKeysFromCookie(), $key
        ], self::MAX_KEYS * -1);

        Craft::$app->response->cookies->add(new Cookie([
            'name' => self::COOKIE_NAME,
            'value' => json_encode($keys),
            'expire' => time() + Plugin::getInstance()->getSettings()->cookieLifetimeInSeconds,
        ]));
    }

    private function _getKeysFromCookie(): array
    {
        $cookies = Craft::$app->request->cookies;
        $cookie = $cookies->getValue(self::COOKIE_NAME);

        return json_decode($cookie) ?: [];
    }
}
