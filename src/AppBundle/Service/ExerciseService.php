<?php

namespace AppBundle\Service;

use AppBundle\Entity\Exercise;
use AppBundle\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;

class ExerciseService
{
    protected $manager;

    /**
     * @var Serializer $serializer
     */
    protected $serializer;
    public function __construct(EntityManager $manager, Serializer $serializer)
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

        $week_ago = date("Y-m-d",$week_ago);
        $two_week_ago = date("Y-m-d",$two_week_ago);


        $repository = $this->manager->getRepository(Exercise::class);
        $exercises = $repository->findBy(array(), array('date'=> 'DESC'));

        $now = date('Y-m-d');
        $today_list = array();
        $week_ago_list = array();
        $two_week_ago_list = array();

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

        switch ($format) {
            case 'json':
                $listExercise =$this->serializer->serialize($listExercise, 'json');
                break;
            case 'xml':
                $listExercise = $this->serializer->serialize($listExercise, 'xml');
                break;
        }

        return $listExercise;


    }

}