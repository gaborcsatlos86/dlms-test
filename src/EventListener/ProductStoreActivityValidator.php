<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\ProductStoreActivity;
use App\Enums\ProductStoreAction;
use App\Repository\ProductStoreActivityRepositoryInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ProductStoreActivity::class)]
class ProductStoreActivityValidator
{
    public function __construct(
        readonly private ProductStoreActivityRepositoryInterface $productStoreActivityRepository
    ) {}
    
    public function prePersist(ProductStoreActivity $productStoreActivity, PrePersistEventArgs $args): void
    {
        $entityManager = $args->getObjectManager();
        $product = $productStoreActivity->getProduct();
        $hasProductInitActivity = $this->productStoreActivityRepository->hasProductInitAction($product);
        if ( ($productStoreActivity->getAction() == ProductStoreAction::Init && $hasProductInitActivity) || ($productStoreActivity->getAction() != ProductStoreAction::Init && !$hasProductInitActivity)) {
            throw new UnprocessableEntityHttpException(($hasProductInitActivity) ? 'Product has init activity action! You can not add more' : 'Product do not have any init activity action! Please add that first!');
        }
        switch ($productStoreActivity->getAction())
        {
            case ProductStoreAction::Init: $product->setActualStockAmount($productStoreActivity->getAmount());
            break;
            
            case ProductStoreAction::Add: $product->increaseActualStockAmount($productStoreActivity->getAmount());
            break;
            
            case ProductStoreAction::Remove: 
                if ($productStoreActivity->getAmount() > $product->getActualStockAmount()) {
                    throw new UnprocessableEntityHttpException('Product has less stock amount then you want to remove!');
                }
                $product->decreaseActualStockAmount($productStoreActivity->getAmount());
                break;
                
            default:
                throw new UnprocessableEntityHttpException('Activity has an uncontrolled action!');
        }
        
        $entityManager->persist($product);
        $entityManager->flush();
    }
}
