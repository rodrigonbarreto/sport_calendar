<?php
namespace Tests\AppBundle\Service;

use AppBundle\Entity\Exercise;
use AppBundle\Service\ExerciseService;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ExerciseServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ExerciseService $service */
    protected $service;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | EntityManager
     */
    protected $managerMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject | EntityRepository */
    protected $repositoryMock;

    protected function setUp()
    {
        $this->repositoryMock = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $this->managerMock = $this->getMock(EntityManagerInterface::class);
        $this->service = new ExerciseService($this->managerMock);
    }

    protected function tearDown()
    {
        $this->repositoryMock = null;
        $this->repositoryMock = null;
        $this->managerMock = null;
        $this->service = null;
    }

    /**
     * @param array $repositoryResponse
     * @param array $expectedResponse
     * @dataProvider testGetExercisesDataProvider
     */
    public function testGetExercises(array $repositoryResponse, array $expectedResponse)
    {
        $this->managerMock
            ->expects($this->once())
            ->method('getRepository')
            ->with(Exercise::class)
            ->willReturn($this->repositoryMock)
        ;

        $exercises = $this->createExercises($repositoryResponse['date']);
        $weekAgo = strtotime("-1 week");
        $twoWeeksAgo = strtotime("-2 week");
        $weekAgo = date("Y-m-d", $weekAgo);
        $twoWeeksAgo = date("Y-m-d", $twoWeeksAgo);
        $today = date('Y-m-d');

        $this->repositoryMock->expects($this->once())
            ->method('findBy')
            ->with(['date' => [$today, $weekAgo, $twoWeeksAgo]], ['date' => Criteria::DESC ])
            ->willReturn($exercises)
        ;

        $result = $this->service->getExercises();
        $exerciseToday = new Exercise();
        $exerciseToday->setDate($expectedResponse['today']['date']);
        $exerciseWeekAgo = new Exercise();
        $exerciseWeekAgo->setDate($expectedResponse['week_ago']['date']);
        $exerciseTwoWeekAgo = new Exercise();
        $exerciseTwoWeekAgo->setDate($expectedResponse['two_week_ago']['date']);

        $this->assertEquals(
            $result['today'][0]->getDate()->format('Y-m-d'),
            $exerciseToday->getDate()->format('Y-m-d')
        );
        $this->assertEquals(
            $result['week_ago'][0]->getDate()->format('Y-m-d'),
            $exerciseWeekAgo->getDate()->format('Y-m-d')
        );
        $this->assertEquals($result['two_week_ago'][0]->getDate()->format('Y-m-d'),
            $exerciseTwoWeekAgo->getDate()->format('Y-m-d')
        );
    }

    /**
     * @return array
     */
    public function testGetExercisesDataProvider()
    {
        return [
                'check Exercise Repository' => [
                    'repositoryResponse' => [
                        'date' => [
                            'Day 1' => [
                                'date' => new \DateTime(),
                                'description' => 'Description',
                            ],
                            'week ago' => [
                                'date' => (new \DateTime())->sub(new \DateInterval('P7D')),
                                'description' => 'Description',
                            ],
                            'two_week ago' => [
                                'date' => (new \DateTime())->sub(new \DateInterval('P14D')),
                                'description' => 'Description',
                            ],
                    ],
                ],
                'expectedResponse' => [
                            'today' => [
                                'date' => new \DateTime(),
                            ],
                            'week_ago' => [
                                'date' => (new \DateTime())->sub(new \DateInterval('P7D')),
                            ],
                            'two_week_ago' => [
                                'date' => (new \DateTime())->sub(new \DateInterval('P14D')),
                            ],
                ],
            ]
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
            $exercises[] = $exercise;
        }

        return $exercises;
    }
}
