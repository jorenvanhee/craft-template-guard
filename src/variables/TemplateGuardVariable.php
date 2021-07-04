<?php

namespace jorenvanhee\templateguard\variables;

use jorenvanhee\templateguard\Plugin;

class TemplateGuardVariable
{
    public function protect(?string $password = null, ?string $key = null)
    {
        Plugin::getInstance()->guard->protect($password, $key);
    }
}
