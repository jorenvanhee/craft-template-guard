# Release Notes for Template Guard

## 1.1.1 - 2022-03-31
- Add `X-Robots-Tag: noindex` header to login route.

## 1.1.0 - 2022-03-29
> {tip} You can now provide an array of passwords `{% do craft.templateGuard.protect(['passwords1', 'passwords2']) %}`. Any of the passwords provided in the array can be used to log in.

- Add support for multiple passwords on one page.
- Add tests.

## 1.0.0 - 2021-07-05
- Initial release.
