<?php

declare(strict_types=1);

namespace App\DataTable\Column;

use Omines\DataTablesBundle\Column\TextColumn;

class EnumColumn extends TextColumn
{
    public function normalize(mixed $value): string
    {
        $value = (string) $value->value;
        
        return $this->isRaw() ? $value : htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE);
    }
}
