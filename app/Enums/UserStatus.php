<?php

namespace App\Enums;

enum UserStatus: int
{
    case ACTIVE = 0;
    case BAN = 1;
}
