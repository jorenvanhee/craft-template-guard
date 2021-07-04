<?php

namespace jorenvanhee\templateguard\records;

use craft\db\ActiveRecord;

class LoginAttempt extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%templateguard_login_attempts}}';
    }
}
