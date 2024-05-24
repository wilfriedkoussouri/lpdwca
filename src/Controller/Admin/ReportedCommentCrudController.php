<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReportedCommentCrudController extends AbstractCrudController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Reported Comment')
            ->setEntityLabelInPlural('Reported Comments')
            ->setPageTitle(Crud::PAGE_INDEX, 'Reported Comments')
            ->setDefaultSort(['publicationDate' => 'DESC'])
            ->setPaginatorPageSize(10);
    }

    public function configureActions(Actions $actions): Actions
    {
        $validateAction = Action::new('validateReport', 'Valider', 'fa fa-check')
            ->linkToCrudAction('validateComment')
            ->addCssClass('btn btn-success');

        $moderateAction = Action::new('moderateReport', 'Refuser', 'fa fa-times')
            ->linkToCrudAction('moderateComment')
            ->addCssClass('btn btn-warning');

        return $actions
            ->add(Crud::PAGE_INDEX, $validateAction)
            ->add(Crud::PAGE_INDEX, $moderateAction)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('user')->setCrudController(UserCrudController::class);
        yield TextEditorField::new('content');
        yield DateField::new('publicationDate');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.hasBeenValidated IS NULL');
        $response->andWhere('entity.reportCount > 0');

        return $response;
    }

    public function validateComment(AdminContext $context, EntityManagerInterface $entityManager): Response
    {
        $comment = $context->getEntity()->getInstance();
        if ($comment instanceof Comment) {
            $comment->setHasBeenValidated(true);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Comment validated successfully.');
        }
        return $this->redirect($this->adminUrlGenerator->setController(static::class)->generateUrl());
    }

    public function moderateComment(AdminContext $context, EntityManagerInterface $entityManager): Response
    {
        $comment = $context->getEntity()->getInstance();
        if ($comment instanceof Comment) {
            $comment->setHasBeenValidated(false);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('warning', 'Comment moderated successfully.');
        }
        return $this->redirect($this->adminUrlGenerator->setController(static::class)->generateUrl());
    }
}
