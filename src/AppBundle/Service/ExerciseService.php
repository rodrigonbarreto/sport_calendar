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
        $week_ago = strtotime("-1 week");
        $two_week_ago = strtotime("-2 week");

        $week_ago = date("Y-m-d", $week_ago);
        $two_week_ago = date("Y-m-d", $two_week_ago);


        $repository = $this->manager->getRepository(Exercise::class);
        //todo: $repository->findBy(['date' => [$today, $weekAgo, $twoWeeksAgo]]);
        $exercises = $repository->findBy([], ['date'=> 'DESC']);

        $now = date('Y-m-d');
        $today_list = [];
        $week_ago_list = [];
        $two_week_ago_list = [];

        foreach ($exercises as $e) {
            /** @var Exercise $e */
            if ($e->getDate()->format('Y-m-d') == $now) {
                $today_list[] = $e;
            }

            if ($e->getDate()->format('Y-m-d') == $week_ago  ) {
                $week_ago_list[] = $e;
            }

            if ($e->getDate()->format('Y-m-d') == $two_week_ago ) {
                $two_week_ago_list[] = $e;
            }
        }

        $listExercise = [
            'today' => $today_list,
            'week_ago' => $week_ago_list,
            'two_week_ago' => $two_week_ago_list,
        ];

        //todo: remove serialization from here - it should simplify your test a lot
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
