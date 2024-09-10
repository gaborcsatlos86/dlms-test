<?php

declare(strict_types=1);

namespace App\Controller;


use App\Entity\{Product, ProductStoreActivity};
use App\Form\{ProductStoreActivityCreateType};
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Form\FormError;

#[Route('/product-store-activity/', name: 'product_store_activity_')]
class ProductStoreActivityController extends AbstractController
{
    #[Route('{product}/create', name: 'create')]
    public function create(Product $product, Request $request, EntityManagerInterface $entityManager): Response
    {
        $productStoreActivity = (new ProductStoreActivity())
            ->setProduct($product)
        ;
        $form = $this->createForm(ProductStoreActivityCreateType::class, $productStoreActivity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ProductStoreActivity $productStoreActivity */
            $productStoreActivity = $form->getData();
            
            try {
                $entityManager->persist($productStoreActivity);
                $entityManager->flush();
            } catch (UnprocessableEntityHttpException $e) {
                $form->addError(new FormError($e->getMessage()));
                return $this->render('product_store_activity/create.html.twig', [
                    'form' => $form,
                ]);
            }
            
            return $this->redirectToRoute('product_list');
        }
        return $this->render('product_store_activity/create.html.twig', [
            'form' => $form,
        ]);
    }
}
