<?php

namespace AppBundle\Service;

use AppBundle\Entity\Exercise;
use AppBundle\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManager;

class ExerciseService
{
    protected $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function getExercises()
    {

        $week_ago = strtotime("-1 week");
        $two_week_ago = strtotime("-2 week");
        $tree_week_ago = strtotime("-3 week");


        $week_ago = date("Y-m-d",$week_ago);
        $two_week_ago = date("Y-m-d",$two_week_ago);
        $tree_week_ago = date("Y-m-d",$tree_week_ago);




        $repository = $this->manager->getRepository(Exercise::class);
        $now = date('Y-m-d');

        $exercises = $repository->findBy(array(), array('date'=> 'DESC'));

        $today_list = array();
        $week_ago_list = array();
        $two_week_ago_list = array();

        foreach ($exercises as $e) {
            /** @var Exercise $e */
            if ($e->getDate()->format('Y-m-d') == $now) {
                $today_list[] = $e;
            }

            if ($e->getDate()->format('Y-m-d') <= $week_ago && $e->getDate()->format('Y-m-d') >= $two_week_ago ) {
                $week_ago_list[] = $e;
            }

            if ($e->getDate()->format('Y-m-d') < $two_week_ago && $e->getDate()->format('Y-m-d') >= $tree_week_ago ) {
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