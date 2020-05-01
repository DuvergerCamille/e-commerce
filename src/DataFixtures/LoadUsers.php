<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class LoadUsers extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User;

        $user->setUsername('user');
        $user->setPassword('userpass');
        $user->setSalt('');
        $user->setRoles(array('ROLE_USER'));

        $manager->persist($user);

        $user1 = new User;

        $user1->setUsername('admin');
        $user1->setPassword('adminpass');
        $user1->setSalt('');
        $user1->setRoles(array('ROLE_ADMIN'));

        $manager->persist($user1);

        $manager->flush();
    }
}
