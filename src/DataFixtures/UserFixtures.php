<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $ph)
    {
        $this->passwordHasher = $ph;
    }

    public function load(ObjectManager $manager): void
    {
        // Admin user
        // ----------------------------------------------------------------------------------------
        $admin = new User();
        $admin->setEmail("admin@fixture.com");
        $admin->setRoles(["ROLE_ADMIN"]);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            "123456"
        );
        $admin->setPassword($hashedPassword);

        $manager->persist($admin);

        // Regular user
        // ----------------------------------------------------------------------------------------
        $user = new User();
        $user->setEmail("user@fixture.com");

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            "654321"
        );
        $user->setPassword($hashedPassword);

        $manager->persist($user);

        $link1 = $this->getReference("link_1");
        $link2 = $this->getReference("link_2");

        $user->addLink($link1);
        $user->addLink($link2);

        // Add to database
        $manager->flush();
    }
}
