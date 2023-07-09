<?php

namespace jorenvanhee\templateguard\variables;

use jorenvanhee\templateguard\Plugin;

class TemplateGuardVariable
{
    public function protect(string|array|null $passwords = null, string $key)
    {
        Plugin::getInstance()->guard->protect((array) $passwords, $key);
    }
}
