# Release Notes for Template Guard

## 2.0.0-beta.1 - 2022-03-31
- Template Guard now requires Craft CMS 4.0.0-beta.3 or newer.
- Added PHPStan.

## 1.1.1 - 2022-03-31
- Added `X-Robots-Tag: noindex` header to login route.

## 1.1.0 - 2022-03-29
> {tip} You can now provide an array of passwords `{% do craft.templateGuard.protect(['passwords1', 'passwords2']) %}`. Any of the passwords provided in the array can be used to log in.

- Added support for multiple passwords on one page.
- Added tests.

## 1.0.0 - 2021-07-05
- Initial release.
