<?php

namespace AppBundle\Service;

use AppBundle\Entity\Exercise;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Provider\DateTime;

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
     * @param string $today
     * @return array
     */
    public function getExercises(string $today)
    {

        $weekAgo = strtotime(date("Y-m-d", strtotime($today)) . " -1 week");
        $weekAgo = date("Y-m-d", $weekAgo);
        $twoWeeksAgo = strtotime(date("Y-m-d", strtotime($today)) . " -2 week");
        $twoWeeksAgo = date("Y-m-d", $twoWeeksAgo);

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
