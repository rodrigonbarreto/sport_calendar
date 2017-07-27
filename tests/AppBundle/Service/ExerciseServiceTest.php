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

        $exerciseToday = new Exercise();
        $exerciseToday->setDate($expectedResponse['dates']['dayOne']);
        $exerciseWeekAgo = new Exercise();
        $exerciseWeekAgo->setDate($expectedResponse['dates']['weekAgo']);
        $exerciseTwoWeekAgo = new Exercise();
        $exerciseTwoWeekAgo->setDate($expectedResponse['dates']['twoWeekAgo']);

        $exercises =
        [
            $exerciseToday,
            $exerciseWeekAgo,
            $exerciseTwoWeekAgo,
        ];

        $this->repositoryMock->expects($this->once())
            ->method('findBy')
            ->with(['date' =>
                [
                    $repositoryResponse['dates']['dayOne'],
                    $repositoryResponse['dates']['weekAgo'],
                    $repositoryResponse['dates']['twoWeekAgo']]
                ],
                    ['date' => Criteria::DESC ]
                )
            ->willReturn($exercises)
        ;

        $result = $this->service->getExercises($repositoryResponse['dates']['dayOne']);

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
                        'dates' => [
                                'dayOne' => "2017-07-27",
                                'weekAgo' => "2017-07-20",
                                'twoWeekAgo' => "2017-07-13",
                         ],
                ],
                    'expectedResponse' => [
                        'dates' => [
                            'dayOne' => new \DateTime("2017-07-27"),
                            'weekAgo' => new \DateTime("2017-07-20"),
                            'twoWeekAgo' => new \DateTime("2017-07-13"),
                        ],
                    ],
            ]
        ];

    }
}
