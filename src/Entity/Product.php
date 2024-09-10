<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\ProductState;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\{PrePersistEventArgs, PreUpdateEventArgs};

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: "productNumber", columns: ["product_number"])]
class Product extends AbstractEntity
{
    #[ORM\Column(type: Types::STRING)]
    private string $name;
    
    #[ORM\Column(type: Types::STRING)]
    private string $productNumber;
    
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;
    
    #[ORM\Column(enumType: ProductState::class)]
    private ?ProductState $state = null;
    
    #[ORM\Column(type: Types::INTEGER)]
    private int $actualStockAmount;
    
    public function getName(): string
    {
        return $this->name;
    }

    public function getProductNumber(): string
    {
        return $this->productNumber;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getState(): ?ProductState
    {
        return $this->state;
    }

    public function getActualStockAmount(): int
    {
        return $this->actualStockAmount;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }

    public function setProductNumber(string $productNumber): self
    {
        $this->productNumber = $productNumber;
        
        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        
        return $this;
    }

    public function setState(ProductState $state): self
    {
        $this->state = $state;
        
        return $this;
    }

    public function setActualStockAmount(int $actualStockAmount): self
    {
        $this->actualStockAmount = $actualStockAmount;
        
        return $this;
    }
    
    public function increaseActualStockAmount(int $stockAmount): self
    {
        $this->actualStockAmount += $stockAmount;
        
        return $this;
    }
    
    public function decreaseActualStockAmount(int $stockAmount): self
    {
        $this->actualStockAmount -= $stockAmount;
        
        return $this;
    }
    
    #[ORM\PrePersist]
    public function onPrePersist(PrePersistEventArgs $eventArgs)
    {
        $this->onCreateSetTimes();
    }
    
    #[ORM\PreUpdate]
    public function onPreUpdate(PreUpdateEventArgs $eventArgs)
    {
        $this->onUpdateSetTime();
    }
    
}
