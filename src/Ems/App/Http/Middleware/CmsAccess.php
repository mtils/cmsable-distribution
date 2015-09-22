<?php namespace Ems\App\Http\Middleware;

use URL;
use Permit\Support\Laravel\Middleware\HasAccess;

class CmsAccess extends HasAccess
{
    protected function retrievePermissionsFromRequest($request)
    {
        return ['cms.access'];
    }
}
