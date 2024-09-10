<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductStoreAction: string
{
    case Init = 'init';
    case Add = 'add';
    case Remove = 'remove';
}
