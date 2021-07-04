<?php

namespace jorenvanhee\templateguard\models;

use craft\base\Model;

class Settings extends Model
{
    public $template;

    public $loginRoute = 'template-guard/login';

    public function rules()
    {
        return [
            [['loginRoute'], 'required'],
        ];
    }
}
