<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\Product;
use App\Entity\Type;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ProductCrudController::class);

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tea market');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Back To Market', 'fas fa-home', 'app_home');

        yield MenuItem::linkToCrud('Users', 'fas fa-list', User::class)
            ->setPermission('ROLE_SUPER_ADMIN');

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('Orders', 'fas fa-list', Order::class);
            yield MenuItem::linkToCrud('Payments', 'fas fa-list', Payment::class);
        }

        if ($this->isGranted('ROLE_MODER')) {
            yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
            yield MenuItem::linkToCrud('Products', 'fas fa-list', Product::class);

            yield MenuItem::linkToCrud('Types of tea', 'fas fa-list', Type::class);
            yield MenuItem::linkToCrud('Brands', 'fas fa-list', Brand::class);
            yield MenuItem::linkToCrud('Countries', 'fas fa-list', Country::class);
        }
    }
}
