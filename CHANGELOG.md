# Release Notes for Template Guard

## 3.2.0 - 2024-12-11
- Fixed XSS vulnerability

## 3.1.0 - 2024-02-18
- Added support for Craft CMS 5.0.0-beta.2 or newer.

## 3.0.0 - 2024-01-24 [CRITICAL]
> [!WARNING]  
> The `key` argument on the protect method is now mandatory, `craft.templateGuard.protect('password', 'secret-page-key')`. Previously, if the key argument was omitted, the system would default to the current URL. This resulted in the potential bypassing of the `maxLoginAttempts` rule.

- Make the `key` argument required.
- Improve documentation.
- Add log out support.

## 2.1.0 - 2022-10-07
- Added more settings: `cookieLifetimeInSeconds`, `maxLoginAttempts` and `maxLoginAttemptsPeriodInSeconds`.

## 2.0.0 - 2022-10-04

## 2.0.0-beta.1 - 2022-03-31
- Template Guard now requires Craft CMS 4.0.0-beta.3 or newer.
- Added PHPStan.

## 1.1.1 - 2022-03-31
- Added `X-Robots-Tag: noindex` header to login route.

## 1.1.0 - 2022-03-29
> [!TIP]
> You can now provide an array of passwords `{% do craft.templateGuard.protect(['passwords1', 'passwords2']) %}`. Any of the passwords provided in the array can be used to log in.

- Added support for multiple passwords on one page.
- Added tests.

## 1.0.0 - 2021-07-05
- Initial release.
