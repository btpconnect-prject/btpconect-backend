<?php

namespace App\DataFixtures;

use App\Entity\UserEntity;
use App\Entity\WorkSpaceEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Ramsey\Uuid\Uuid;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $userDefault = new UserEntity();
        $userDefault->setEmail('user@example.com');
        $userDefault->setPassword($this->passwordHasher->hashPassword($userDefault, 'password'));
        $userDefault->setName("john");
        $userDefault->setFirstname("doe");
        $userDefault->setRoles(["ROLE_USER"]);

        $workSpace1 = new WorkSpaceEntity();
        $workSpace1->setTitle("projet de construction");
        $userDefault->addWorkSpace($workSpace1);
        $manager->persist($userDefault);


        $userAdmin = new UserEntity();
        $userAdmin->setEmail('admin@example.com');
        $userAdmin->setPassword($this->passwordHasher->hashPassword($userDefault, 'password'));
        $userAdmin->setName("johnAdmin");
        $userAdmin->setFirstname("doeAdmin");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($userAdmin);
        $manager->flush();
    }
}
