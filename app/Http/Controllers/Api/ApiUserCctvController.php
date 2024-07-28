<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\UserCctvService;
use Illuminate\Http\Request;

class ApiUserCctvController extends Controller
{
    protected $userCctvService;

    public function __construct(UserCctvService $userCctvService)
    {
        $this->userCctvService = $userCctvService;
    }

    // HANDLER API
    public function dataTable(Request $request)
    {
        return $this->userCctvService->dataTable($request);
    }

    public function create(Request $request)
    {
        return $this->userCctvService->create($request);
    }

    public function destroy(Request $request)
    {
        return $this->userCctvService->destroy($request);
    }
}
