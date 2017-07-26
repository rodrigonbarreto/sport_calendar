<?php

namespace AppBundle\Service;

use AppBundle\Entity\Exercise;
use Doctrine\ORM\EntityManagerInterface;

class ExerciseService
{
    protected $manager;

    /**
     * ExerciseService constructor.
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
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
}
