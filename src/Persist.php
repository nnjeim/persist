<?php

namespace Nnjeim\Persist;

use Illuminate\Support\Facades\Facade;

class Persist extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'persist';
    }
}
