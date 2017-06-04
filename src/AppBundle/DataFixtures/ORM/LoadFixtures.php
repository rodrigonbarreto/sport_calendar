<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\DataFixtures\Providers\ExerciseProvider;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements FixtureInterface
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        Fixtures::load(__DIR__.'/fixtures/data.yml', $manager, [
            'providers' =>
                [$this, ExerciseProvider::class],
        ]);

        $manager->flush();
    }
}