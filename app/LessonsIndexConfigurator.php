<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class LessonsIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    protected $settings = [
        //
    ];
}