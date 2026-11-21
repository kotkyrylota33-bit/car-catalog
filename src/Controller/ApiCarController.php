<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\BrandRepository;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/cars')]
class ApiCarController extends AbstractController
{
    private function prettyJson(mixed $data, int $status = 200): JsonResponse
    {
        $response = new JsonResponse($data, $status);
        $response->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $response;
    }

    #[Route('', name: 'api_car_index', methods: ['GET'])]
    public function index(Request $request, CarRepository $carRepository): JsonResponse
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

        $data = array_map(fn(Car $car) => [
            'id' => $car->getId(),
            'brand' => $car->getBrand()?->getName(),
            'model' => $car->getModel(),
            'year' => $car->getYear(),
            'engine' => $car->getEngine(),
            'price' => $car->getPrice(),
        ], $cars);

        return $this->prettyJson($data);
    }

    #[Route('/{id}', name: 'api_car_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Car $car): JsonResponse
    {
        return $this->prettyJson([
            'id' => $car->getId(),
            'brand' => $car->getBrand()?->getName(),
            'country' => $car->getBrand()?->getCountry(),
            'model' => $car->getModel(),
            'year' => $car->getYear(),
            'engine' => $car->getEngine(),
            'price' => $car->getPrice(),
            'description' => $car->getDescription(),
        ]);
    }

    #[Route('', name: 'api_car_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(
        Request $request,
        BrandRepository $brandRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $payload = json_decode($request->getContent(), true);

        $brand = $brandRepository->find($payload['brand_id'] ?? 0);
        if (!$brand) {
            return $this->prettyJson(['error' => 'Brand not found'], 400);
        }

        $car = new Car();
        $car->setBrand($brand);
        $car->setModel($payload['model'] ?? 'Unknown');
        $car->setYear((int)($payload['year'] ?? 2020));
        $car->setEngine($payload['engine'] ?? '2.0');
        $car->setPrice((int)($payload['price'] ?? 10000));
        $car->setDescription($payload['description'] ?? '');

        $em->persist($car);
        $em->flush();

        return $this->prettyJson(['status' => 'created', 'id' => $car->getId()], 201);
    }
}