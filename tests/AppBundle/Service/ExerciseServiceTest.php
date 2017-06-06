<?php
namespace Tests\AppBundle\Service;

use AppBundle\Entity\Exercise;
use AppBundle\Service\ExerciseService;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use JMS\Serializer\Serializer;

class ExerciseServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ExerciseService $service */
    protected $service;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | EntityManager
     */
    protected $managerMock;

    /**
     * @var Serializer | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $serializerMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject | EntityRepository */
    protected $repositoryMock;

    protected function setUp()
    {
        $this->serializerMock = $this->getMockBuilder(Serializer::class)->disableOriginalConstructor()->getMock();
        $this->repositoryMock = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $this->managerMock = $this->getMock(EntityManagerInterface::class);

        $this->managerMock
            ->expects($this->once())
            ->method('getRepository')
            ->with(Exercise::class)
            ->willReturn($this->repositoryMock)
        ;

        $this->service = new ExerciseService($this->managerMock, $this->serializerMock);
    }

    protected function tearDown()
    {
        $this->serializerMock = null;
        $this->repositoryMock = null;
        $this->repositoryMock = null;
        $this->managerMock = null;
        $this->service = null;
    }

    public function testGetExercises()
    {
        $data = [
            'first exercise' => [
                'date' => new \DateTime(),
                'description' => 'Description',
            ],
            'second exercise' => [
                'date' => (new \DateTime())->sub(new \DateInterval('P7D')),
                'description' => 'Description',
            ],
        ];

        $exercises = $this->createExercises($data);

        $this->repositoryMock->expects($this->once())
            ->method('findBy')
            ->with([], ['date' => Criteria::DESC])
            ->willReturn($exercises)
        ;

        $result = $this->service->getExercises();

        $this->checkExercisesResponse($result, $this->filterExercises($exercises));
    }

    public function testGetExercisesSerialized()
    {
        $format = 'json';
        $expectedResponse = 'expected json string';

        $data = [
            'first exercise' => [
                'date' => new \DateTime(),
                'description' => 'Description',
            ],
            'second exercise' => [
                'date' => (new \DateTime())->sub(new \DateInterval('P7D')),
                'description' => 'Description',
            ],
        ];

        $exercises = $this->createExercises($data);

        $this->repositoryMock->expects($this->once())
            ->method('findBy')
            ->with([], ['date' => Criteria::DESC])
            ->willReturn($exercises)
        ;

        $this->serializerMock->expects($this->once())
            ->method('serialize')
            ->with($this->filterExercises($exercises), $format)
            ->willReturn($expectedResponse)
        ;

        $result = $this->service->getExercises($format);

        $this->assertEquals($result, $expectedResponse);
    }

    /**
     * @param array $exercises
     *
     * @return array
     */
    private function filterExercises(array $exercises)
    {
        $week_ago = strtotime("-1 week");
        $two_week_ago = strtotime("-2 week");

        $week_ago = date("Y-m-d", $week_ago);
        $two_week_ago = date("Y-m-d", $two_week_ago);

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


        return [
            'today' => $today_list,
            'week_ago' => $week_ago_list,
            'two_week_ago' => $two_week_ago_list,
        ];
    }

    /**
     * @param array $data
     *
     * @return Exercise[]
     */
    private function createExercises(array $data)
    {
        $exercises = [];

        foreach ($data as $key => $item) {
            $exercise = new Exercise();
            $exercise->setDate($item['date']);
            $exercise->setDescription($item['description']);

            $exercises[] = $exercise;
        }

        return $exercises;
    }

    private function checkExercisesResponse(array $response, array $expectedResponse)
    {
        $keys = array_keys($expectedResponse);

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $response);
            $this->assertEquals($response[$key], $expectedResponse[$key]);
        }
    }

    // TODO:
    // 1. Move stuff to data provider
    // 2. Add maybe more data
}
