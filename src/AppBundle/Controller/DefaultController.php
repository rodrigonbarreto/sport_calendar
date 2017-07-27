<?php

namespace AppBundle\Controller;

use AppBundle\Service\ExerciseService;
use AppBundle\Service\SerializerDataService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage", )
     */
    public function indexAction(Request $request)
    {
        $weekAgo = strtotime("-1 week");
        $twoWeeksAgo = strtotime("-2 week");
        $weekAgo = date("Y-m-d", $weekAgo);
        $twoWeeksAgo = date("Y-m-d", $twoWeeksAgo);
        $today = date('Y-m-d');

        /** @var ExerciseService $exerciseService */
        $exerciseService = $this->get('app.exercise_service');
        $exercises = $exerciseService->getExercises($today, $weekAgo, $twoWeeksAgo);

        return $this->render('default/index.html.twig', [
            'exerciseServices' => $exercises,
        ]);
    }

    /**
     * @Route("/exercise/list", name="exercise_api", )
     */
    public function indexApiAction(Request $request)
    {
        $weekAgo = strtotime("-1 week");
        $twoWeeksAgo = strtotime("-2 week");
        $weekAgo = date("Y-m-d", $weekAgo);
        $twoWeeksAgo = date("Y-m-d", $twoWeeksAgo);
        $today = date('Y-m-d');

        /** @var ExerciseService $exerciseService */
        $exerciseService = $this->get('app.exercise_service');
        /** @var SerializerDataService $serializeService */
        $serializeService = $this->get('app.serializer_data_service');
        $exercises = $exerciseService->getExercises($today, $weekAgo, $twoWeeksAgo);
        $exercises = $serializeService->serializeExercise($exercises, 'json');

        return new Response($exercises, Response::HTTP_OK, array(
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Headers' => 'origin, content-type, accept',
            'Access-Control-Allow-Origin' => '*'
        ));

    }
}
