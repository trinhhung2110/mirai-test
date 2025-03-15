<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowSerialPasoRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    const APP_ENV = [
        '0' => 'AWS',
        '1' => 'K5',
        '2' => 'T2',
    ];

    const CONTRACT_SERVER = [
        '0' => 'app1',
        '1' => 'app2',
    ];

    /**
     * Show the serialpaso.
     *
     * @param  \Illuminate\Http\ShowSerialPasoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function showSerialpaso(ShowSerialPasoRequest $request)
    {
        try {
            $file = $request->input('file');
            $appEnv = $request->input('app_env');
            $contractApp = $request->input('contract_server');

            $path = 'imprints_html_file/' . self::APP_ENV[$appEnv] . '/' . self::CONTRACT_SERVER[$contractApp] . '/';
            $fullPath = $path . $file . '.html';

            if (Storage::exists($fullPath)) {
                $content = Storage::get($fullPath);
                $base64Content = base64_encode($content);
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'filename' => $file . '.html',
                    'content' => $base64Content,
                    'message' => 'Seal Info response successfully'
                ]);
            }

        } catch (Exception $e) {
            Log::error('Error showing serialpaso: ' . $e->getMessage());
        }
        return response()->json([
            'status' => 'error',
            'code' => 500,
            'message' => 'Seal Info response false'
        ], 500);
    }

    /**
     * Count the number of students in each class.
     *
     * @return \Illuminate\Http\Response
     */
    public function countStudents()
    {
        $classes = [
            ['count' => 5, 'students' => 35],
            ['count' => 6, 'students' => 45],
            ['count' => 10, 'students' => 30],
            ['count' => 4, 'students' => 40],
        ];

        $totalStudents = array_reduce($classes, function ($carry, $class) {
            return $carry + ($class['count'] * $class['students']);
        }, 0);

        $averageAgeMonths = 20 * 12 + 8;

        $upperAge = $averageAgeMonths + 6;
        $lowerAge = $averageAgeMonths - 6;

        function generateAges($numStudents) {
            $ages = [];
            for ($i = 0; $i < $numStudents; $i++) {
                $ages[] = rand(18 * 12, 25 * 12);
            }
            return $ages;
        }

        $results = [];
        foreach ($classes as $class) {
            $classStudents = $class['count'] * $class['students'];
            $ages = generateAges($classStudents);

            $aboveAge = count(array_filter($ages, function ($age) use ($upperAge) {
                return $age > $upperAge;
            }));

            $belowAge = count(array_filter($ages, function ($age) use ($lowerAge) {
                return $age < $lowerAge;
            }));

            $results[] = [
                'count' => $class['count'],
                'class_students' => $classStudents,
                'above_age' => $aboveAge,
                'below_age' => $belowAge,
            ];
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => [
                'total_students' => $totalStudents,
                'average_age_months' => $averageAgeMonths,
                'class_statistics' => array_map(function ($result) {
                    return [
                        'count' => $result['count'],
                        'class_size' => $result['class_students'],
                        'statistics' => [
                            'above_age' => $result['above_age'],
                            'below_age' => $result['below_age']
                        ]
                    ];
                }, $results)
            ]
        ]);
    }


    /**
     * find Top 20 Percent Age Difference.
     *
     * @return \Illuminate\Http\Response
     */
    public function findTop20Percent($numPeople)
    {
        $data = [];
        for ($i = 0; $i < $numPeople; $i++) {
            $data[] = [
                'person' => 'person' . $i,
                'age' => rand(1, 100),
            ];
        }

        $ageDifferences = [];
        $numPeople = count($data);

        for ($i = 0; $i < $numPeople; $i++) {
            $totalDifference = 0;
            for ($j = 0; $j < $numPeople; $j++) {
                if ($i !== $j) {
                    $totalDifference += abs($data[$i]['age'] - $data[$j]['age']);
                }
            }
            $ageDifferences[$i] = $totalDifference;
        }


        arsort($ageDifferences);

        $top20Percent = (int) ($numPeople * 0.2);
        $top20PercentAges = array_slice(array_keys($ageDifferences), 0, $top20Percent);

        $result = [];
        foreach($top20PercentAges as $index){
            $result[] = [
                'person' => $data[$index]['person'],
                'ageDifferences' => $ageDifferences[$index],
            ];
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $result
        ]);
    }




    /**
     * find Furthest People.
     *
     * @return \Illuminate\Http\Response
     */
    public function findFurthestPeople()
    {
        $positions = [];
        for ($i = 0; $i < 100; $i++) {
            $positions[] = [rand(-10, 10), rand(-10, 10)];
        }

        $numPeople = count($positions);
        $distances = [];

        function calculateDistance($pos1, $pos2) {
            return sqrt(pow($pos1[0] - $pos2[0], 2) + pow($pos1[1] - $pos2[1], 2));
        }

        for ($i = 0; $i < $numPeople; $i++) {
            $totalDistance = 0;
            for ($j = 0; $j < $numPeople; $j++) {
                if ($i !== $j) {
                    $totalDistance += calculateDistance($positions[$i], $positions[$j]);
                }
            }
            $distances[$i] = $totalDistance;
        }

        arsort($distances);

        $top10Percent = (int) ($numPeople * 0.1);
        $furthestIndices = array_slice(array_keys($distances), 0, $top10Percent);

        $result = [];
        foreach ($furthestIndices as $index) {
            $result[] = $positions[$index];
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $result
        ]);
    }
}
