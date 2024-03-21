<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasherPassword)
    {
        $this->hasher = $hasherPassword;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setName('Admin')
            ->setLastname('Super')
            ->setEmail('iortega@hexer.dev')
            ->setRoles(["ROLE_SUPER_ADMIN"])
        ;

        $passowordHashed = $this->hasher->hashPassword(
            $user,
            '123'
        );
        
        $user->setPassword($passowordHashed);

        $manager->persist($user);
        $manager->flush();

        $this->setReference('super-admin-user', $user);

        $user2 = new User();
        $user2
            ->setName('Admin')
            ->setLastname('General')
            ->setEmail('test@test.com')
            ->setRoles(["ROLE_ADMIN"])
        ;

        $passowordHashed = $this->hasher->hashPassword(
            $user2,
            '123'
        );
        
        $user2->setPassword($passowordHashed);

        $manager->persist($user2);
        $manager->flush();

        $this->setReference('admin-user', $user2);
    }
}
