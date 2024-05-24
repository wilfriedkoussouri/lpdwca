<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
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
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit')->setCssClass('btn btn-primary');
            })
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('user')->setCrudController(UserCrudController::class);
        yield TextEditorField::new('content');
        yield DateField::new('modification_date')->hideOnForm();
        yield NumberField::new('report_count')->hideOnForm();
        yield NumberField::new('hasBeenValidated', 'Status')
            ->formatValue(function ($value) {
                if ($value === true) {
                    return '<span style="color: green;">Validé</span>';
                } elseif ($value === false) {
                    return '<span style="color: orange;">Refusé</span>';
                } elseif ($value === null) {
                    return '<span style="color: gray;">Non modéré</span>';
                }
                return 'Inconnu';
            })
            ->hideOnForm();
    }
}
