# Template Guard plugin for Craft CMS
Password protect any page or entry in Craft CMS.

- Protect one or more pages.
- Protect multiple entries each with their own password.

## Quick start

### Installation

Install the plugin via the plugin store or install manually using composer.

```
composer require jorenvanhee/craft-template-guard
```

### Examples

#### Protect a single page

The easiest way to get started is by adding the following line of code to the template you want to protect.

```twig
{% do craft.templateGuard.protect('Pa$$w0rd') %}
```

You can also use a password that was stored on the entry.


```twig
{% do craft.templateGuard.protect(entry.myPasswordField) %}
```

#### Protect multiple pages with the same password

Here we've added a second argument, a key that identifies a group of protected pages. Add this line to every template you want to protect with that password.

Once logged in to one of those pages, you'll have access to all of the other pages in that group.

```twig
{% do craft.templateGuard.protect('Pa$$w0rd', 'secret-pages-group') %}
```

#### Protect multiple entries each with their own password

Add this line to the template(s) rendering your entries.

```twig
{% do craft.templateGuard.protect(entry.myPasswordField, entry.uid) %}
```

> ❗️ Only the templates with this line of code will be protected. Please check if your entry is split up into multiple pages.

## Usage

This plugin gives you access to a protect method in your templates. This will redirect users to a login page.

```twig
{% do craft.templateGuard.protect('password', 'key') %}
```

### Arguments

#### Password

The password your users need to provide before accessing the page. The page will not be protected when the password is empty.

#### Key

You can identify a protected area on your site using the key argument. When logged in to a page with a certain key, other pages using the same key will also be accessible. The current url is used as the default key.

## Configuration

Configure Template Guard via Settings → Plugins → Template Guard in the Craft Control Panel.

You can also create a configuration file: `config/template-guard.php`.

```php
<?php

return [
    '*' => [
        // The URI for the login page.
        'loginRoute' => 'template-guard/login',

        // This template will be used for the login page. Leave empty for the
        // default. Take a look at `src/templates/login.twig` in the plugin
        // repository to develop your own custom template.
        'template' => '',
    ],
];
```

## License

This plugin requires a commercial license purchasable through the [Craft Plugin Store](https://plugins.craftcms.com).
