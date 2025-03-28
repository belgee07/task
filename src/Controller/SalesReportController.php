<?php

// src/Controller/SalesReportController.php
namespace App\Controller;

use App\Repository\SalesRepository;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalesReportController extends AbstractController
{
    #[Route('/sales/report', name: 'sales_report')]
    public function index(SalesRepository $salesRepository, DepartmentRepository $departmentRepository): Response
    {
        $departments = $departmentRepository->findAll();
        
        // Get the current date and previous month
        $currentDate = new \DateTime();
        $previousMonth = (new \DateTime())->modify('-1 month');

        // Get the first and last day of the current month
        $firstDayCurrentMonth = new \DateTime('first day of this month');
        $lastDayCurrentMonth = new \DateTime('last day of this month');

        // Get the first and last day of the previous month
        $firstDayPreviousMonth = new \DateTime('first day of last month');
        $lastDayPreviousMonth = new \DateTime('last day of last month');
        
        // Prepare an array with sales for current and previous month for each department
        $salesComparison = [];

        foreach ($departments as $department) {
            // Get current month sales
            $currentMonthSales = $salesRepository->createQueryBuilder('s')
                ->where('s.department = :department')
                ->andWhere('s.date >= :firstDayCurrentMonth')
                ->andWhere('s.date <= :lastDayCurrentMonth')
                ->setParameter('department', $department)
                ->setParameter('firstDayCurrentMonth', $firstDayCurrentMonth)
                ->setParameter('lastDayCurrentMonth', $lastDayCurrentMonth)
                ->select('SUM(s.amount)')
                ->getQuery()
                ->getSingleScalarResult();

            // Get previous month sales
            $previousMonthSales = $salesRepository->createQueryBuilder('s')
                ->where('s.department = :department')
                ->andWhere('s.date >= :firstDayPreviousMonth')
                ->andWhere('s.date <= :lastDayPreviousMonth')
                ->setParameter('department', $department)
                ->setParameter('firstDayPreviousMonth', $firstDayPreviousMonth)
                ->setParameter('lastDayPreviousMonth', $lastDayPreviousMonth)
                ->select('SUM(s.amount)')
                ->getQuery()
                ->getSingleScalarResult();

            // Set values if no sales found
            $currentMonthSales = $currentMonthSales ?: 0;
            $previousMonthSales = $previousMonthSales ?: 0;

            // Store the sales data for each department
            $salesComparison[$department->getId()] = [
                'department' => $department,
                'currentMonthSales' => $currentMonthSales,
                'previousMonthSales' => $previousMonthSales,
                'difference' => $currentMonthSales - $previousMonthSales
            ];
        }

        return $this->render('sales_report/report.html.twig', [
            'salesComparison' => $salesComparison,
        ]);
    }
}
