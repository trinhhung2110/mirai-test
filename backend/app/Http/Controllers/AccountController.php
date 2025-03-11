<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountStoreRequest;
use App\Http\Requests\AccountUpdateRequest;
use App\Http\Requests\ShowSerialPasoRequest;
use App\Repositories\Account\AccountRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
   private $accountRepository;

   public function __construct(AccountRepository $accountRepository)
   {
        $this->accountRepository = $accountRepository;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $perPage = request('per_page', 10);
            $accounts = $this->accountRepository->getAllPaginate($perPage);
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $accounts
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching accounts: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to fetch accounts'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $account = $this->accountRepository->getAccountId($id);
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $account
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching accounts: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to get account'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountStoreRequest $request)
    {
        try {
            $account = $this->accountRepository->create($request->all());
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $account
            ]);
        } catch (Exception $e) {
            Log::error('Error creating account: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to create account'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\AccountUpdateRequest  $request
     * @return \Illuminate\Http\Response
     * @param  $id
     */
    public function update(AccountUpdateRequest $request, $id)
    {
        try {
            $account = $this->accountRepository->update($id, $request->all());
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $account
            ]);
        } catch (Exception $e) {
            Log::error('Error updating account: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to update account'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->accountRepository->delete($id);
            Log::info('Account deleted successfully: ' . $id);
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Account deleted successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Error deleting account: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to delete account'
            ], 500);
        }
    }

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
