<?php

namespace App\Enums;

enum PostStatus: int
{
    case Draft = 0;
    case Published = 1;
}
