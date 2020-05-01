<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Instruments;

class InstrumentsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $instru = new Instruments();
        $instru->setName('Guitare');
        $instru->setPrice(250);
        $manager->persist($instru);

        $instru1 = new Instruments();
        $instru1->setName('Piano');
        $instru1->setPrice(550);
        $manager->persist($instru1);

        $instru2 = new Instruments();
        $instru2->setName('Flute');
        $instru2->setPrice(150);
        $manager->persist($instru2);

        $instru3 = new Instruments();
        $instru3->setName('Batterie');
        $instru3->setPrice(350);
        $manager->persist($instru3);

        $instru4 = new Instruments();
        $instru4->setName('Trompettes');
        $instru4->setPrice(100);
        $manager->persist($instru4);

        $manager->flush();
    }
}
