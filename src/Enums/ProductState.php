<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductState: string
{
    case Active = 'active';
    case InActive = 'in-active';
    case Test = 'test';
}
