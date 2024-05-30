<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')->setLabel('Titre')->setHelp('Titre de votre article')->setRequired(true),
            SlugField::new('slug')->setTargetFieldName('title')->setRequired(true),
            TextEditorField::new('content')->setRequired(true),
            ImageField::new('image')->setLabel('Image')->setHelp("Image d'illustration de l'article")->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')->setBasePath('/uploads')->setUploadDir('/public/uploads')->setRequired(true),
            AssociationField::new('category', "Catégorie associée")->setRequired(true),
            DateTimeField::new('publicationDate')->setLabel('Date de publication')->setFormat('dd/MM/YY')->hideOnForm(),
            DateTimeField::new('modificationDate')->setLabel('Date de modification')->setFormat('dd/MM/YY')->hideOnForm(),
            TextField::new('author')->setLabel('Auteur')->hideOnForm(),

        ];
    }
}
