<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categories;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $listCategory = array('Vent', 'Electrique', 'Percussion', 'Cuivre', 'Corde');

        foreach ($listCategory as $name)
        {
            
            $category = new Categories();

            $category->setNom($name);
            $category->addInstrument();

            $manager->persist($category);
        }

        $manager->flush();
    }
}
