<?php


namespace Ems\App\Services\Auth\Permit;

use InvalidArgumentException;
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

        if (!$codes = $this->castToCodes($resourceOrCode, $context)) {
            return;
        }

        foreach ($codes as $code) {
            if ($code == $this->systemPermission) {
                return false;
            }
        }
    }

    protected function castToCodes($resourceOrCode, $context)
    {
        if ($resourceOrCode instanceof PermissionableInterface) {
            return $resourceOrCode->requiredPermissionCodes($context);
        }

        if (is_string($resourceOrCode) || is_array($resourceOrCode)) {
            return (array)$resourceOrCode;
        }

        return [];
    }
}