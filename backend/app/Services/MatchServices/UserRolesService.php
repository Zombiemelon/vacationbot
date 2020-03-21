<?php


namespace App\Services\MatchServices;


use App\User;

class UserRolesService
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const MANAGER = 'MANAGER';
    const USER = 'USER';

    /**
     * @var array
     */
    protected static $roleHierarchy = [
        self::ROLE_ADMIN => ['*'],
        self::MANAGER => [
            self::MANAGER,
            self::USER
        ],
        self::USER => [
            self::USER
        ]
    ];

    /**
     * @param string $role
     * @return array
     */
    public static function getAllowedRoles(string $role)
    {
        if (isset(self::$roleHierarchy[$role])) {
            return self::$roleHierarchy[$role];
        }

        return [];
    }

    /***
     * @return array
     */
    public static function getRoleList()
    {
        return [
            static::ROLE_ADMIN =>'Admin',
            static::MANAGER => 'Manager',
        ];
    }

    public static function check(User $user, string $role)
    {
        // Admin has everything
        if ($user->hasRole(UserRolesService::ROLE_ADMIN)) {
            return true;
        }
        else if($user->hasRole(UserRolesService::MANAGER)) {
            $managementRoles = UserRolesService::getAllowedRoles(UserRolesService::MANAGER);

            if (in_array($role, $managementRoles)) {
                return true;
            }
        }

        return $user->hasRole($role);
    }
}
