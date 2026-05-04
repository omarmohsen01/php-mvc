<?php

namespace PhpMvc\Database\Concerns;

use PhpMvc\Database\Managers\Contracts\DatabaseManager;

trait ConnectsTo
{
    public static function connect(DatabaseManager $manager)
    {
        return $manager->connect();
    }
}