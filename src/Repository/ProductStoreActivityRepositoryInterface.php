<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;

interface ProductStoreActivityRepositoryInterface
{
    public function hasProductInitAction(Product $product): bool;
}
