<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Trash;


class TrashFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = file_get_contents("./public/data_poubelle.json");
        $data = json_decode($data, true);
        // sert à remplir la table type avec les types de poubelles du fichier json
        $types = [];
        foreach ($data as $item) {
            $type = new Type();
            $typeName = $item["fields"]["pavtyp"];
            if (!in_array($typeName, $types)) {
                $types[] = $typeName;
                $type->setName($typeName);
               $manager->persist($type);
            }
        }
       $manager->flush();

        $manager->clear();
        // sert à remplir la table trash avec les poubelles du fichier json
        $types = $manager->getRepository(Type::class)->findAll();
        foreach ($data as $item) {

            $trash = new Trash();
            $trash->setAdresse(empty($item["fields"]["adresse"]) ? null : $item["fields"]["adresse"]);
            $trash->setCommune($item["fields"]["commune"]);
            foreach ($types as $type) {
                if ($type->getName() === $item["fields"]["pavtyp"]) {
                    $trash->setIdType($type);
                }
            }
            $trash->setLatitude($item["fields"]["geo_point_2d"][0]);
            $trash->setLongitude($item["fields"]["geo_point_2d"][1]);
           $manager->persist($trash);
        }

        $manager->flush();
    }
}
