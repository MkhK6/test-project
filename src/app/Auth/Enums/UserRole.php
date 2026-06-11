<?php

namespace App\Auth\Enums;

enum UserRole: string
{
    case Guest = 'guest';
    case User = 'user';
    case Admin = 'admin';
}
