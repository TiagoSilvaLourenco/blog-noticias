<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Models\Position;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::where('is_active', true);

        if ($pos = $request->query('position')) {
            $query->whereHas('positions', fn($q) => $q->where('code', $pos));
        }

        $ads = $query->with('positions')->get();

        return response()->json([
            'data' => AdResource::collection($ads),
        ]);
    }

    public function positions()
    {
        return Position::all();
    }
}
