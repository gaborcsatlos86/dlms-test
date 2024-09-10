<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\{Product, ProductStoreActivity};
use App\Enums\ProductStoreAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductStoreActivityRepository extends ServiceEntityRepository implements ProductStoreActivityRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductStoreActivity::class);
    }
    
    public function hasProductInitAction(Product $product): bool
    {
        return ($this->findOneBy(['product' => $product, 'action' => ProductStoreAction::Init]) instanceof ProductStoreActivity);
    }
}
