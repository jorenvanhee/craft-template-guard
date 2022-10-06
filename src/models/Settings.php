<?php

namespace jorenvanhee\templateguard\models;

use craft\base\Model;

class Settings extends Model
{
    public $template;

    public $loginRoute = 'template-guard/login';

    public $cookieLifetimeInSeconds = 60 * 60;

    public $maxLoginAttempts = 5;

    public $maxLoginAttemptsPeriodInSeconds = 300;

    public function rules(): array
    {
        return [
            [[
                'loginRoute',
                'cookieLifetimeInSeconds',
                'maxLoginAttempts',
                'maxLoginAttemptsPeriodInSeconds',
            ], 'required'],
            [[
                'maxLoginAttempts',
                'maxLoginAttemptsPeriodInSeconds',
            ], 'integer', 'min' => 1],
            [['cookieLifetimeInSeconds'], 'integer', 'min' => 0],
        ];
    }
}
