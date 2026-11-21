<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\Car;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
    private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    public function index(): Response
    {
      return $this->render('@EasyAdmin/page/content.html.twig');
    } 

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Car Catalog');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkTo(BrandCrudController::class, 'Марки (Brands)', 'fas fa-tags');
        yield MenuItem::linkTo(CarCrudController::class, 'Автомобілі (Cars)', 'fas fa-car');
    }
}
