<?php

namespace App\Constants;

class UserConstant
{
    public const MSG_GET_LIST_SUCCESS = 'Users retrieved successfully.';
    public const MSG_GET_DETAIL_SUCCESS = 'User details retrieved successfully.';
    public const MSG_CREATE_SUCCESS = 'User created successfully!';
    public const MSG_UPDATE_SUCCESS = 'User updated successfully!';
    public const MSG_DELETE_SUCCESS = 'User deleted successfully!';

    public const MSG_NOT_FOUND = 'User does not exist.';
    public const MSG_CANNOT_MODIFY_LAST_ADMIN = 'Cannot change the role or deactivate the last remaining admin in the system.';
    public const MSG_CANNOT_DELETE_LAST_ADMIN = 'Cannot delete the last remaining admin in the system.';
}