<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogResource;
use App\Models\Log;
use Exception;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        return LogResource::collection(Log::query()->orderBy('date', 'desc')->get());
    }


    public function destroy(string $id)
    {
        try {
            $log = Log::query()->where('id', $id)->first();

            $log->delete();
        } catch (Exception) {
            return __404("Ce log n'existe pas");
        }
    }
}
