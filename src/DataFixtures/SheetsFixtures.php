<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Sheets;

class SheetsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $sheet = new Sheets();
        $sheet->setName('Asturias d Albeniz');
        $sheet->setPrice(25);
        $manager->persist($sheet);

        $sheet1 = new Sheets();
        $sheet1->setName('Clair de lune de Debussy');
        $sheet1->setPrice(20);
        $manager->persist($sheet1);

        $sheet2 = new Sheets();
        $sheet2->setName('Les variations de Telemann');
        $sheet2->setPrice(20);
        $manager->persist($sheet2);

        $sheet3 = new Sheets();
        $sheet3->setName('AC-DC back in black (batterie)');
        $sheet3->setPrice(26);
        $manager->persist($sheet3);

        $sheet4 = new Sheets();
        $sheet4->setName('Konzertstuck Opus 11 et Opus 12');
        $sheet4->setPrice(15);
        $manager->persist($sheet4);

        $manager->flush();
    }
}
