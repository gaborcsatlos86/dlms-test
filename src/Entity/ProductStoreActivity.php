<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\ProductStoreAction;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\PrePersistEventArgs;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class ProductStoreActivity extends AbstractEntity
{
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private Product $product;
    
    #[ORM\Column(enumType: ProductStoreAction::class)]
    private ProductStoreAction $action;
    
    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $amount;
    
    public function getProduct(): Product
    {
        return $this->product;
    }
    
    public function getAction(): ProductStoreAction
    {
        return $this->action;
    }
    
    public function getAmount(): int
    {
        return $this->amount;
    }
    
    public function setProduct(Product $product): self
    {
        $this->product = $product;
        
        return $this;
    }
    
    public function setAction(ProductStoreAction $action): self
    {
        $this->action = $action;
        
        return $this;
    }
    
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;
        
        return $this;
    }
    
    #[ORM\PrePersist]
    public function onPrePersist(PrePersistEventArgs $eventArgs)
    {
        $this->onCreateSetTimes();
    }
    
}