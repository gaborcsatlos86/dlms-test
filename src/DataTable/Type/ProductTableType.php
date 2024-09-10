<?php


declare(strict_types=1);

namespace App\DataTable\Type;

use App\Entity\Product;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\{TextColumn, NumberColumn, TwigStringColumn};
use App\DataTable\Column\EnumColumn;


class ProductTableType implements DataTableTypeInterface
{
    public function configure(DataTable $dataTable, array $options): void
    {
        $dataTable
            ->add('id', NumberColumn::class, ['label' => 'Id'])
            ->add('name', TwigStringColumn::class, [
                'label' => 'Name',
                'template' => '<a href="{{ url(\'product_edit\', {product: row.id}) }}">{{ row.name }}</a>',
            ])
            ->add('productNumber', TextColumn::class, ['label' => 'Product Number'])
            ->add('description', TwigStringColumn::class, [
                'label' => 'Description',
                'template' => '<span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ row.description }}">{{ row.description }}</span>'
            ])
            ->add('state', EnumColumn::class, ['label' => 'State'])
            ->add('actualStockAmount', NumberColumn::class, ['label' => 'Actual Stock Amunt'])
            ->add('store', TwigStringColumn::class, [
                'label' => 'Actions',
                'template' => '<a class="btn btn-outline-secondary" href="{{ url(\'product_store_activity_create\', {product: row.id}) }}">Add store action</a>',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Product::class,
            ]);
    }

}
