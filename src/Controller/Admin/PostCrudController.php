<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Actualité')
            ->setEntityLabelInPlural('Actualités');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')->setLabel('Titre'),
            SlugField::new('slug')->setLabel('URL')->setTargetFieldName('title')->onlyOnForms(),
            TextEditorField::new('content')->setLabel('Contenu'),
            ImageField::new('image')->setLabel('Image')->setUploadDir('/public/uploads/'),
            ChoiceField::new('status')->setLabel('Etat')->setChoices([
                'Publié' => 'published',
                'Non publié' => 'unpublished'
            ]),
            AssociationField::new('category')->setLabel('Catégorie'),
            AssociationField::new('author')->setLabel('Auteur'),
            DateTimeField::new('publishedAt', 'Date de publication')
        ];
    }


    
}
