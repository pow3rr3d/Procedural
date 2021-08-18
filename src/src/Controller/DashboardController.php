<?php


namespace App\Controller;

use App\Entity\Company;
use App\Entity\CompanyProcess;
use App\Entity\CompanyProcessStep;
use App\Entity\Process;
use App\Entity\State;
use App\Entity\Step;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractController
{
    private $charts;

    private $colors;

    public function __construct()
    {
        $this->charts = [];
        $this->colors = [
            '#4ecdc4',
            '#1a535c',
            '#ff6b6b',
            '#ffe66d',
            '#FF8552',
            '#C84630',
            '#D4A0A7',
            '#A28F9D',
            '#8F8389',
            '#986C6A',
            '#784F41',
            '#C46D5E',
            '#F56960',
            '#A97C73',
            '#AF3E4D',
            '#C492B1',
            '#AF3B6E'
        ];
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(ChartBuilderInterface $chartBuilder)
    {
        $this->CompanyProcessChart($chartBuilder);
        $this->ProcessStateChart($chartBuilder);
        $this->InlineStats($chartBuilder);
        $this->ByUserStats($chartBuilder);
        $this->AverageTime($chartBuilder);

        return $this->render("dashboard/index.html.twig", [
            'charts' => $this->charts,
        ]);
    }

    public function CompanyProcessChart(ChartBuilderInterface $chartBuilder)
    {
        $companyProcesses = $this->getDoctrine()->getRepository(CompanyProcess::class)->findBy(['IsFinished' => true]);

        $data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,];
        $currentYears = (new \DateTimeImmutable())->format('Y');

        foreach ($companyProcesses as $companyProcess) {
            if ($companyProcess->getUpdatedAt()->format('Y') === $currentYears) {
                $data[$companyProcess->getUpdatedAt()->format('m') - 1] += 1;
            }
        }
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => [
                'January ' . $currentYears,
                'February ' . $currentYears,
                'March ' . $currentYears,
                'April ' . $currentYears,
                'May ' . $currentYears,
                'June ' . $currentYears,
                'July ' . $currentYears,
                'August ' . $currentYears,
                'September ' . $currentYears,
                'October ' . $currentYears,
                'November ' . $currentYears,
                'December ' . $currentYears
            ],
            'datasets' => [
                [
                    'label' => false,
                    'backgroundColor' => 'RGBA(255,255,255,0)',
                    'borderColor' => '#4ecdc4',
                    'data' => $data,
                ],
            ],
        ]);

        $max = $data ? max($data) + 2 : 1;

        $chart->setOptions([
            'responsive' => true,
            'legend' => ['display' => false],
            'title' => [
                'display' => true,
                'text' => 'Company-Process Finished for ' . $currentYears,
                'fontSize' => 16
            ],
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'min' => 0,
                            'max' => $max
                        ],
                    ]
                ],
            ],
        ]);

        array_push($this->charts, $chart);
    }

    public function ProcessStateChart(ChartBuilderInterface $chartBuilder)
    {
        $companyProcesses = $this->getDoctrine()->getRepository(CompanyProcess::class)->findAll();
        $AllStates = $this->getDoctrine()->getRepository(State::class)->findAll();
        $states = [];
        $data = [];


        foreach ($AllStates as $allState) {
            $states[$allState->getId()] = $allState->getName();
            $data[$allState->getId()] = 0;
        }
        foreach ($companyProcesses as $companyProcess) {
            $data[$companyProcess->getState()->getId()] += 1;

        }

        $states = array_values($states);
        $data = array_values($data);

        $chart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chart->setData([
            'labels' => $states,
            'datasets' => [
                [
                    'label' => 'Company-Process By State',
                    'backgroundColor' => $this->colors,
                    'borderColor' => $this->colors,
                    'data' => $data,
                ],
            ],
        ]);

        $chart->setOptions([
            'responsive' => true,
            'legend' => [
                'display' => true,
                'position' => 'right',
            ],
            'title' => [
                'display' => true,
                'text' => "Company-Process by State",
                'fontSize' => 16
            ],
        ]);

        array_push($this->charts, $chart);
    }


    public function InlineStats(ChartBuilderInterface $chartBuilder)
    {
        $companies = count($this->getDoctrine()->getRepository(Company::class)->findAll());
        $processes = count($this->getDoctrine()->getRepository(Process::class)->findAll());
        $companyProcesses = count($this->getDoctrine()->getRepository(CompanyProcess::class)->findAll());

        $states = [
            'Companies',
            'Processes',
            'Company-Processes'
        ];
        $data = [
            $companies,
            $processes,
            $companyProcesses
        ];


        $chart = $chartBuilder->createChart(Chart::TYPE_BAR_HORIZONTAL);
        $chart->setData([
            'labels' => $states,
            'datasets' => [
                [
                    'label' => false,
                    'backgroundColor' => 'rgba(78, 205, 196,0.7)',
                    'borderColor' => '#4ecdc4',
                    'data' => $data,
                ],
            ],
        ]);

        $max = $data ? max($data) + 2 : 1;

        $chart->setOptions([
            'responsive' => true,
            'legend' => [
                'display' => false,
                'position' => 'right',
            ],
            'title' => [
                'display' => true,
                'text' => "App Data",
                'fontSize' => 16
            ],
            'scales' => [
                'xAxes' => [
                    [
                        'ticks' => [
                            'min' => 0,
                            'max' => $max,
                            'display' => false
                        ],
                    ]
                ],
            ],
        ]);

        array_push($this->charts, $chart);
    }

    public function ByUserStats(ChartBuilderInterface $chartBuilder)
    {

        $allUsers = $this->getDoctrine()->getRepository(User::class)->findAll();
        $steps = $this->getDoctrine()->getRepository(CompanyProcessStep::class)->findAll();
        $users = [];
        $data = [];


        foreach ($allUsers as $user) {
            $users[$user->getId()] = $user->getName();
            $data[$user->getId()] = 0;
        }
        foreach ($steps as $step) {
            $data[$step->getValidatedBy()->getId()] += 1;
        }

        $users = array_values($users);
        $data = array_values($data);

        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $users,
            'datasets' => [
                [
                    'label' => false,
                    'backgroundColor' => 'rgba(78, 205, 196,0.7)',
                    'borderColor' => '#4ecdc4',
                    'data' => $data,
                ],
            ],
        ]);

        $max = $data ? max($data) + 2 : 1;

        $chart->setOptions([
            'responsive' => true,
            'legend' => [
                'display' => false,
                'position' => 'right',
            ],
            'title' => [
                'display' => true,
                'text' => "Validated Steps by User",
                'fontSize' => 16
            ],
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'min' => 0,
                            'max' => $max,
                            'display' => true
                        ],
                    ]
                ],
            ],
        ]);

        array_push($this->charts, $chart);
    }

    public function AverageTime(ChartBuilderInterface $chartBuilder)
    {

        $Allprocesses = $this->getDoctrine()->getRepository(CompanyProcess::class)->findBy(["IsFinished" => true]);
        $processes = [];
        $data = [];
        $times = [];
        $modulo = [];


        foreach ($Allprocesses as $process) {
            $data[$process->getProcess()->getId()] = 0;
            $times[$process->getProcess()->getId()] = 0;
            $modulo[$process->getProcess()->getId()] = 0;
        }
        foreach ($Allprocesses as $process) {
            $processes[$process->getProcess()->getId()] = $process->getProcess()->getName();
            $interval = new \DateInterval('PT1H');
            $diff = iterator_count($periods = new \DatePeriod($process->getCreatedAt(), $interval, $process->getUpdatedAt()));
            $modulo[$process->getProcess()->getId()] += 1;
            $times[$process->getProcess()->getId()] += $diff;
            $data[$process->getProcess()->getId()] = $times[$process->getProcess()->getId()] / $modulo[$process->getProcess()->getId()];
        }

        $processes = array_values($processes);
        $data = array_values($data);

        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $processes,
            'datasets' => [
                [
                    'label' => 'Hours',
                    'backgroundColor' => 'rgba(78, 205, 196,0.7)',
                    'borderColor' => '#4ecdc4',
                    'data' => $data,
                ],
            ],
        ]);

        $max = $data ? max($data) + 2 : 1;

        $chart->setOptions([
            'responsive' => true,
            'legend' => [
                'display' => false,
                'position' => 'right',
            ],
            'title' => [
                'display' => true,
                'text' => "Exection Time Average by Process",
                'fontSize' => 16
            ],
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'min' => 0,
                            'max' => $max,
                            'display' => true
                        ],
                    ]
                ],
            ],
        ]);

        array_push($this->charts, $chart);
    }
}

