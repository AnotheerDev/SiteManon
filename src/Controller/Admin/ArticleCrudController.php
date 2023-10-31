<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
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
            // ne pas afficher l'id et la date car si je dois créer un article je veux que l'id et la date soit généré automatiquement
            // IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('content'),
            DateField::new('dateCreation'),
            ImageField::new('photo')->setBasePath('img/')->setUploadDir('public/img/'),
        ];
    }

}
