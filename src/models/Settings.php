<?php

namespace jorenvanhee\templateguard\models;

use craft\base\Model;

class Settings extends Model
{
    public $template;

    public $loginRoute = 'template-guard/login';

    public $maxAttempts = 5;

    public $maxAttemptsPeriodInSeconds = 300;

    public function rules()
    {
        return [
            [['loginRoute', 'maxAttempts', 'maxAttemptsPeriodInSeconds'], 'required'],
            [['maxAttempts'], 'integer', 'min' => 1],
            [['maxAttemptsPeriodInSeconds'], 'integer', 'min' => 1],
        ];
    }
}
