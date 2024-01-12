<?php

namespace jorenvanhee\templateguard\services;

use Craft;
use InvalidArgumentException;
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
        if ($key === null) {
            throw new InvalidArgumentException("A key describing the protected page(s) must be provided as the second argument to the protect method.\nExample: {% do craft.templateGuard.protect('Pa\$\$w0rd', 'secret-page') %}");
        }

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

    public function logout(?string $key = null): void
    {
        if ($key === null) {
            $this->_removeAllFromCookie();
        } else {
            $this->_removeFromCookie($this->_generateKey($key));
        }
    }

    private function _generateKey(string $key): string
    {
        // The length of the key will be 40 chars (sha1). We'll use this
        // predictable length to check how many keys we can store in
        // one cookie.
        return sha1($key);
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

        $this->_setKeysOnCookie($keys);
    }

    private function _removeAllFromCookie()
    {
        $this->_setKeysOnCookie([]);
    }

    private function _removeFromCookie(string $keyToRemove)
    {
        $keys = array_filter(
            $this->_getKeysFromCookie(),
            fn ($key) => $key !== $keyToRemove,
        );

        $this->_setKeysOnCookie($keys);
    }

    private function _getKeysFromCookie(): array
    {
        $cookies = Craft::$app->request->cookies;
        $cookie = $cookies->getValue(self::COOKIE_NAME);

        return json_decode($cookie) ?: [];
    }

    private function _setKeysOnCookie(array $keys)
    {
        Craft::$app->response->cookies->add(new Cookie([
            'name' => self::COOKIE_NAME,
            'value' => json_encode($keys),
            'expire' => time() + Plugin::getInstance()->getSettings()->cookieLifetimeInSeconds,
        ]));
    }
}
