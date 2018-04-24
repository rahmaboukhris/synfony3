<?php
/**
 * Created by PhpStorm.
 * User: mostafa
 * Date: 4/24/18
 * Time: 12:28 PM
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Genus;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;
use Nelmio\Alice\Fixtures\Fixture;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        /*$genus = new Genus();
        $genus->setName('Octopus'.rand(1, 100));
        $genus->setSubFamily('Octodrine');
        $genus->setSpeciesCount(rand(100,99999));

        $manager->persist($genus);
        $manager->flush();*/

        Fixtures::load(
                __DIR__.'/fixtures.yml',
                $manager,
                [
                    'providers' => [$this]
                ]
            );
    }

    public function genus(){
        $genera = [
            'Octopus',
            'Baleana',
            'Peara',
            'Hippocompus',
            'Asterias',
            'Milestone',
            'James',
            'Lolopopo',
            'Dumdum',
            'Alice'
        ];

        $key = array_rand($genera);

        return $genera[$key];
    }
}