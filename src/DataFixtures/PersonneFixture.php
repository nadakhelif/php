<?php

namespace App\DataFixtures;
use App\Entity\Personne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonneFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
       
            
            for ($i=0; $i < 20; $i++) {
            $personne = new Personne();
            $personne->setFirstname("firstName$i");
            $personne->setLastName("LastName$i");
            $personne->setAge(($i*6)%60);

            $manager->persist($personne);
        }
        $manager->flush();

        $manager->flush();
    }
}  

