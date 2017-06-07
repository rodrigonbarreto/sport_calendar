<?php

namespace AppBundle\Service;

use AppBundle\Entity\Exercise;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Serializer;

class ExerciseService
{
    protected $manager;

    /**
     * @var Serializer $serializer
     */
    protected $serializer;

    /**
     * ExerciseService constructor.
     *
     * @param EntityManagerInterface $manager
     * @param Serializer $serializer
     */
    public function __construct(EntityManagerInterface $manager, Serializer $serializer)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function getExercises($format = 'Default')
    {
        $weekAgo = strtotime("-1 week");
        $twoWeeksAgo = strtotime("-2 week");

        $weekAgo = date("Y-m-d", $weekAgo);
        $twoWeeksAgo = date("Y-m-d", $twoWeeksAgo);
        $today = date('Y-m-d');

        $repository = $this->manager->getRepository(Exercise::class);
        $exercises = $repository->findBy(['date' => [$today, $weekAgo, $twoWeeksAgo]], ['date'=> 'DESC']);

        $today_list = [];
        $week_ago_list = [];
        $two_week_ago_list = [];

        foreach ($exercises as $e) {
            /** @var Exercise $e */
            if ($e->getDate()->format('Y-m-d') == $today) {
                $today_list[] = $e;
            }

            if ($e->getDate()->format('Y-m-d') == $weekAgo  ) {
                $week_ago_list[] = $e;
            }

            if ($e->getDate()->format('Y-m-d') == $twoWeeksAgo ) {
                $two_week_ago_list[] = $e;
            }
        }

        $listExercise = [
            'today' => $today_list,
            'week_ago' => $week_ago_list,
            'two_week_ago' => $two_week_ago_list,
        ];
        return $listExercise;
    }

    /**
     * @param mixed  $listExercise
     * @param string $format
     *
     * @return mixed|string
     */
    public function serializeExercise ($listExercise, $format = 'default') {
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