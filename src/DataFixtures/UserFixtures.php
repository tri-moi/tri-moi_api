<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserBadge;
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

        $badges = file_get_contents("./src/data/badge.json");
        $badges = json_decode($badges, true);
        $level = file_get_contents("./src/data/level.json");
        $level = json_decode($level, true);
        var_dump($badges);
        var_dump($level);

        $this->extracted($badges, $level, $user, $manager);

        $user = new User();
        $user->setEmail("user@user.fr");
        $user->setPassword($this->hasher->hashPassword($user, "User123*"));
        $user->setRoles(["ROLE_USER"]);
        $user->setFirstName("Pierre");
        $user->setLastName("Martinet");
        $user->setProfilPic("https://www.fakepersongenerator.com/Face/male/male1085416855.jpg");
        $user->setBirthday(new \DateTime("2000-01-01"));
        $user->setCreatedAt(new \DateTimeImmutable("now"));

        $this->extracted($badges, $level, $user, $manager);


        $manager->flush();
        $manager->clear();


        // creation des badges par rapport à l'user

    }

    /**
     * @param mixed $badges
     * @param mixed $level
     * @param User $user
     * @param ObjectManager $manager
     * @return array
     */
    public function extracted(mixed $badges, mixed $level, User $user, ObjectManager $manager): array
    {
        foreach ($badges as $item) {
            foreach ($level as $value) {
                $badge = new UserBadge();
                $badge->setBadge(json_encode($item));
                $badge->setLevel(json_encode($value));
                $badge->setIdUser($user);
                $badge->setNmbreScan(0);
                $manager->persist($badge);
            }
        }

        $manager->persist($user);
        return array($item, $value, $badge);
    }
}
