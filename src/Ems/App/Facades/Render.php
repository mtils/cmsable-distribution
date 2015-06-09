<?php namespace Ems\App\Facades;

use Illuminate\Support\Facades\Facade;

class Render extends Facade{

    protected static function getFacadeAccessor(){
        return 'cmsable-dist.multi-renderer';
    }

}