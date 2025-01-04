<?php

namespace App\DataFixtures;

use App\Entity\NiveauEtude;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NiveauEtudeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $niveaux = ["Bac","Bac + 2", "Bac + 3", "Master", "Master + 2","Doctorat"];
        foreach($niveaux as $niveau){
            $niveauEtude = new NiveauEtude();
            $niveauEtude->setLibelle($niveau);
            $manager->persist($niveauEtude);
        }
        $manager->flush();
    }
}
