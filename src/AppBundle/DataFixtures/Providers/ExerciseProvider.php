<?php

namespace AppBundle\DataFixtures\Providers;

class ExerciseProvider
{
    /**
     * @return string
     */
    public function exercises()
    {
        $genera = [
            'Crunch',
            'Resisted Crunch',
            'Inclined Crunch with Feet Attached',
            'Crunch with Leg Curl'
        ];
        $key = array_rand($genera);
        return $genera[$key];
    }
}