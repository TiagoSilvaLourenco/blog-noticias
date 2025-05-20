<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Position;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::where('is_active', true);

        if ($positionCode = $request->query('position')) {
            $query->whereHas('positions', fn ($q) =>
                $q->where('code', $positionCode)
            );
        }

        return $query->with('positions')->get();
    }

    public function positions()
    {
        return Position::all();
    }
}
