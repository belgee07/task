<?php

// src/Controller/DepartmentController.php
namespace App\Controller;

use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\DepartmentType;

class DepartmentController extends AbstractController
{
    #[Route('/department', name: 'department_list')]
    public function index(DepartmentRepository $departmentRepository): Response
    {
        $departments = $departmentRepository->findAll();
        return $this->render('department/index.html.twig', [
            'departments' => $departments,
        ]);
    }
    

    
    #[Route('/department/create', name: 'department_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($department);
            $entityManager->flush();
    
            $this->addFlash('success', 'Department created successfully!');
    
            return $this->redirectToRoute('department_list');
        }
    
        return $this->render('department/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/department/{id}/edit', name: 'department_edit')]
    public function edit(Request $request, Department $department, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            $this->addFlash('success', 'Department updated successfully!');
    
            return $this->redirectToRoute('department_list');
        }
    
        return $this->render('department/edit.html.twig', [
            'form' => $form->createView(),
            'department' => $department,
        ]);
    }
    

    #[Route('/department/{id}/delete', name: 'department_delete', methods: ['POST'])]
    public function delete(Request $request, Department $department, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$department->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }
    
        $entityManager->remove($department);
        $entityManager->flush();
    
        $this->addFlash('success', 'Department deleted successfully!');
        
        return $this->redirectToRoute('department_list');
    }
}
