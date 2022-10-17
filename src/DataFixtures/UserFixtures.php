<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Config\Security\PasswordHasherConfig;

class UserFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->hasher = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("admin@admin.fr");
        $user->setPassword($this->hasher->hashPassword($user, "Admin123*"));
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setFirstName("Philippe");
        $user->setLastName("Admin");
        $user->setProfilPic("https://www.fakepersongenerator.com/Face/female/female1022491733642.jpg");
        $user->setBirthday(new \DateTime("2000-01-01"));
        $user->setCreatedAt(new \DateTimeImmutable("now"));

        // $manager->persist($user);

        $user = new User();
        $user->setEmail("user@user.fr");
        $user->setPassword($this->hasher->hashPassword($user, "User123*"));
        $user->setRoles(["ROLE_USER"]);
        $user->setFirstName("Pierre");
        $user->setLastName("Martinet");
        $user->setProfilPic("https://www.fakepersongenerator.com/Face/male/male1085416855.jpg");
        $user->setBirthday(new \DateTime("2000-01-01"));
        $user->setCreatedAt(new \DateTimeImmutable("now"));

        //  $manager->persist($user);

        $manager->flush();
    }
}
