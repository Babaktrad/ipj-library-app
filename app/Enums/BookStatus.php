<?php

namespace App\Enums;

enum BookStatus: int
{
    case ACCESSIBLE = 0;
    case RESERVED = 1;
}