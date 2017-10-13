<?php

namespace AppBundle\Controller;

use AppBundle\Service\ExerciseService;
use AppBundle\Service\SerializerDataService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage", )
     *
     * @param ExerciseService $exerciseService
     * @return Response
     */
    public function indexAction(ExerciseService $exerciseService)
    {
        $today = date('Y-m-d');
        /** @var ExerciseService $exerciseService */
        $exercises = $exerciseService->getExercises($today);

        return $this->render('default/index.html.twig', [
            'exerciseServices' => $exercises,
        ]);
    }

    /**
     * @Route("/exercise/list", name="exercise_api", )
     *
     * @param ExerciseService $exerciseService
     * @param SerializerDataService $serializeService
     * @return Response
     */
    public function indexApiAction(ExerciseService $exerciseService, SerializerDataService $serializeService )
    {
        $today = date('Y-m-d');
        $exercises = $exerciseService->getExercises($today);
        $exercises = $serializeService->serializeExercise($exercises, 'json');

        return new Response($exercises, Response::HTTP_OK, array(
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Headers' => 'origin, content-type, accept',
            'Access-Control-Allow-Origin' => '*'
        ));
    }
}
