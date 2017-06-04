<?php

namespace AppBundle\Controller;

use AppBundle\Service\ExerciseService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/{_locale}/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need



        /** @var ExerciseService $exerciseService */


        $exerciseService = $this->get('app.exercise_service');
        $exerciseServices = $exerciseService->getExercises();

        return $this->render('default/index.html.twig', [
            'exerciseServices' => $exerciseServices,
        ]);
    }
}
