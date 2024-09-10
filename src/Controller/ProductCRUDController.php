<?php

declare(strict_types=1);

namespace App\Controller;


use App\DataTable\Type\ProductTableType;
use App\Entity\{Product, ProductStoreActivity};
use App\Form\{ProductCreateType, ProductEditType};
use App\Enums\ProductStoreAction;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;
use Omines\DataTablesBundle\DataTableFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Exception;

#[Route('/', name: 'product_')]
class ProductCRUDController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->createFromType(ProductTableType::class)
            ->handleRequest($request);
            
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('list.html.twig', ['datatable' => $table]);
    }
    
    #[Route('create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductCreateType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $product */
            $product = $form->getData();
            $entityManager->persist($product);
            try {
                if ($product->getActualStockAmount() !== null) {
                    $initProductStore = (new ProductStoreActivity())
                        ->setProduct($product)
                        ->setAmount($product->getActualStockAmount())
                        ->setAction(ProductStoreAction::Init)
                    ;
                    $entityManager->persist($initProductStore);
                }
                $entityManager->flush();
            } catch (Exception $e) {
                $form->addError(new FormError($e->getMessage()));
                return $this->render('product/create.html.twig', [
                    'form' => $form,
                ]);
            }
            return $this->redirectToRoute('product_list');
        }
        return $this->render('product/create.html.twig', [
            'form' => $form,
        ]);
    }
    
    #[Route('edit/{product}', name: 'edit')]
    public function edit(Product $product ,Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductEditType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $product */
            $product = $form->getData();
            
            try {
                $entityManager->persist($product);
                $entityManager->flush();
            } catch (Exception $e) {
                $form->addError(new FormError($e->getMessage()));
                return $this->render('product/edit.html.twig', [
                    'form' => $form,
                ]);
            }
            
            return $this->redirectToRoute('product_list');
        }
        return $this->render('product/edit.html.twig', [
            'form' => $form,
        ]);
    }
    
}
