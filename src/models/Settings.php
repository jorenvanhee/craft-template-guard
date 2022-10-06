<?php

namespace jorenvanhee\templateguard\models;

use craft\base\Model;

class Settings extends Model
{
    public $template;

    public $loginRoute = 'template-guard/login';

    public $cookieLifetimeInSeconds = 60 * 60;

    public function rules(): array
    {
        return [
            [['loginRoute', 'cookieLifetimeInSeconds'], 'required'],
            [['cookieLifetimeInSeconds'], 'integer', 'min' => '0'],
        ];
    }
}
