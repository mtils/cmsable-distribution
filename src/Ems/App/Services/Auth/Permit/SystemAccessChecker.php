<?php


namespace Ems\App\Services\Auth\Permit;


use Permit\Access\CheckerInterface;
use Permit\User\UserInterface;
use Permit\Permission\PermissionableInterface;

class SystemAccessChecker implements CheckerInterface
{

    protected $systemPermission = 'system';

    public function hasAccess(UserInterface $user, $resourceOrCode, $context='access')
    {
        if ($user->isSystem()) {
            return;
        }

        if($resourceOrCode instanceof PermissionableInterface) {
            $codes = $resourceOrCode->requiredPermissionCodes($context);
        } else {
            $codes = (array)$resourceOrCode;
        }

        foreach ($codes as $code) {
            if ($code == $this->systemPermission) {
                return false;
            }
        }
    }
}