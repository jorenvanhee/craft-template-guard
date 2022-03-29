<?php

namespace jorenvanhee\templateguard\variables;

use jorenvanhee\templateguard\Plugin;

class TemplateGuardVariable
{
    public function protect($passwords = null, ?string $key = null)
    {
        Plugin::getInstance()->guard->protect((array) $passwords, $key);
    }
}
