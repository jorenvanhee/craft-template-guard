# Template Guard plugin for Craft CMS 💂
Password protect any page or entry in Craft CMS.

- Protect one or more pages.
- Protect multiple entries each with their own password.
- Customize the login page to match your site's design.
- Easily add log out button.

## Quick start

### Installation

Install the plugin via the plugin store or install manually using composer.

```
composer require jorenvanhee/craft-template-guard
```

## Usage

This plugin provides a `protect` method in your templates, redirecting users to a login page.

```twig
{% do craft.templateGuard.protect('password', 'key') %}
```

### Arguments

#### Password (string, array)

The password your users need to provide before accessing the page. You can also provide an array of passwords. The page will not be protected when the password is an empty string or array.

#### Key (string)

Identify a protected area on your site using the key argument. When logged in to a page with a specific key, other pages using the same key will also be accessible.

## Examples

### Protect a single page

Get started quickly by adding the following code to the template you want to protect.

```twig
{% do craft.templateGuard.protect('Pa$$w0rd', 'secret-page') %}
```

You can also use a password stored on the entry.

```twig
{% do craft.templateGuard.protect(entry.myPasswordField, 'secret-page') %}
```

Provide multiple passwords: any of the passwords provided in the array can be used to log in.

```twig
{% do craft.templateGuard.protect(['password1', 'password2'], 'secret-page') %}
```

### Protect multiple pages with the same password

The second argument (key) identifies a protected area on your site. When logged in to a page with a certain key, other pages using the same key will also be accessible.

Add this line to every template you want to protect with that password.

```twig
{% do craft.templateGuard.protect('Pa$$w0rd', 'secret-pages-group') %}
```

### Protect multiple entries each with their own password

Add this line to the template(s) rendering your entries.

```twig
{% do craft.templateGuard.protect(entry.myPasswordField, entry.uid) %}
```

> ❗️ Only templates with this line of code will be protected. Ensure your entry is not split across multiple pages.

### Add log out button

```twig
<form method="post">
    {{ csrfInput() }}
    {{ actionInput('template-guard/sessions/delete') }}
    <input type="hidden" name="key" value="secret-page">

    <button type="submit">Log out</button>
</form>
```

> ℹ️ Omit the hidden `key` input to log out from all template guard instances.

## Configuration

Configure Template Guard via Settings → Plugins → Template Guard in the Craft Control Panel.

You can also create a configuration file: `config/template-guard.php`.

```php
<?php

return [
    '*' => [
        // This template will be used for the login page. Leave empty for the
        // default. Take a look at `src/templates/login.twig` in the plugin
        // repository to develop your own custom template.
        'template' => '',
        
        // The URI for the login page.
        'loginRoute' => 'template-guard/login',
        
        // Cookie lifetime in seconds.
        'cookieLifetimeInSeconds' => 60 * 60,
        
        // Maximum amount of login attempts across a period.
        'maxLoginAttempts' => 5,
        
        // Period in seconds used to count the max login attempts.
        'maxLoginAttemptsPeriodInSeconds' => 300,
    ],
];
```

## License

This plugin requires a commercial license purchasable through the [Craft Plugin Store](https://plugins.craftcms.com).
