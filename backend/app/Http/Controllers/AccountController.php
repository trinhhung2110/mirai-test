<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountStoreRequest;
use App\Http\Requests\AccountUpdateRequest;
use App\Repositories\Account\AccountRepository;
use Exception;
use Illuminate\Support\Facades\Log;

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
}
