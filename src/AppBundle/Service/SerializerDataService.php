<?php

namespace AppBundle\Service;

use JMS\Serializer\Serializer;

/**
 * Class SerializerDataService
 * @package AppBundle\Service
 */
class SerializerDataService
{
    /**
     * @var Serializer $serializer
     */
    protected $serializer;

    /**
     * ExerciseService constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param mixed  $listExercise
     * @param string $format
     *
     * @return mixed
     */
    public function serializeExercise($listExercise, $format = 'default') : string
    {
        switch ($format) {
            case 'json':
                return $this->serializer->serialize($listExercise, 'json');
            case 'xml':
                return $this->serializer->serialize($listExercise, 'xml');
            default:
                return $listExercise;
        }
    }
}
