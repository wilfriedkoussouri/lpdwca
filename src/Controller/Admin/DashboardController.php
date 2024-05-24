<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\CommentCrudController;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\CategoryCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use App\Controller\Admin\ReportedCommentCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator,
        private CommentRepository $commentRepository
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(CategoryCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sp Lpdwca');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoUrl('Home', 'fa fa-home', $this->generateUrl('app_home'));

        yield MenuItem::section('Users');
        yield MenuItem::subMenu('Actions', "fas fa-bars")->setSubItems(
            [
                MenuItem::linkToCrud('Show Users', "fas fa-eye", User::class),
            ]
        );

        yield MenuItem::section('Categories');
        yield MenuItem::subMenu('Actions', "fas fa-bars")->setSubItems(
            [
                MenuItem::linkToCrud('Show Categoies', "fas fa-eye", Category::class),
                MenuItem::linkToCrud('Add Categoy', "fas fa-plus", Category::class)->setAction(Crud::PAGE_NEW),
            ]
        );

        yield MenuItem::section('Articles');
        yield MenuItem::subMenu('Actions', "fas fa-bars")->setSubItems(
            [
                MenuItem::linkToCrud('Show Articles', "fas fa-eye", Article::class),
                MenuItem::linkToCrud('Add Article', "fas fa-plus", Article::class)->setAction(Crud::PAGE_NEW)
            ]
        );

        $unvalidatedCount = $this->commentRepository->countUnvalidatedComments();
        yield MenuItem::section('Comments');
        yield MenuItem::subMenu('Actions', "fas fa-bars")->setSubItems(
            [
                MenuItem::linkToCrud('Show Comments', "fas fa-eye", Comment::class)->setController(CommentCrudController::class),
                MenuItem::linkToCrud('Reported', 'fas fa-eye', Comment::class)
                    ->setController(ReportedCommentCrudController::class)
                    ->setBadge($unvalidatedCount)
            ]
        );
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
