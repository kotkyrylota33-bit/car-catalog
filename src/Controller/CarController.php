<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarController extends AbstractController
{
    #[Route('/', name: 'app_car_index')]
    public function index(Request $request, CarRepository $carRepository): Response
    {
        $q = trim((string) $request->query->get('q', ''));

        $qb = $carRepository->createQueryBuilder('c')
            ->leftJoin('c.brand', 'b')
            ->addSelect('b');

        if ($q !== '') {
            $qb->andWhere('c.model LIKE :q OR b.name LIKE :q OR c.engine LIKE :q')
               ->setParameter('q', '%' . $q . '%');
        }

        $cars = $qb->orderBy('c.id', 'DESC')->getQuery()->getResult();

        return $this->render('car/index.html.twig', [
            'cars' => $cars,
            'q' => $q,
        ]);
    }

    #[Route('/car/{id}', name: 'app_car_show', requirements: ['id' => '\d+'])]
    public function show(Car $car): Response
    {
        return $this->render('car/show.html.twig', [
            'car' => $car,
        ]);
    }
}