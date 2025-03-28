<?php

// src/Controller/SalesController.php
namespace App\Controller;

use App\Entity\Sales;
use App\Form\SalesType;
use App\Repository\SalesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalesController extends AbstractController
{
    private $entityManager;

    // Inject the Doctrine EntityManagerInterface
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/sales', name: 'sales_index')]
    public function index(SalesRepository $salesRepository): Response
    {
        $sales = $salesRepository->findAll();

        return $this->render('sales/index.html.twig', [
            'sales' => $sales,
        ]);
    }

    #[Route('/sales/create', name: 'sales_create')]
    public function create(Request $request): Response
    {
        $sales = new Sales();
        $form = $this->createForm(SalesType::class, $sales);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($sales);
            $this->entityManager->flush();

            $this->addFlash('success', 'Sale created successfully!');

            return $this->redirectToRoute('sales_index');
        }

        return $this->render('sales/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/sales/edit/{id}', name: 'sales_edit')]
    public function edit(Request $request, Sales $sales): Response
    {
        $form = $this->createForm(SalesType::class, $sales);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Sale updated successfully!');

            return $this->redirectToRoute('sales_index');
        }

        return $this->render('sales/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/sales/delete/{id}', name: 'sales_delete')]
    public function delete(Sales $sales): Response
    {
        $this->entityManager->remove($sales);
        $this->entityManager->flush();

        $this->addFlash('success', 'Sale deleted successfully!');

        return $this->redirectToRoute('sales_index');
    }
}
