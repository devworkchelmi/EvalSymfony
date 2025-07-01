<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            [
                'name' => 'Insider',
                'description' => 'Accès réservé aux employés internes de l\'entreprise.',
                'allowedRoles' => ['ROLE_INSIDER'],
            ],
            [
                'name' => 'Collaborateur',
                'description' => 'Pour les partenaires et intervenants externes.',
                'allowedRoles' => ['ROLE_COLLAB'],
            ],
            [
                'name' => 'Externe',
                'description' => 'Accès limité aux utilisateurs publics ou invités.',
                'allowedRoles' => ['ROLE_USER'],
            ],
        ];

        foreach ($categories as $data) {
            $category = new Category();
            $category->setName($data['name']);
            $category->setDescription($data['description']);
            $category->setAllowedRoles($data['allowedRoles']);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
