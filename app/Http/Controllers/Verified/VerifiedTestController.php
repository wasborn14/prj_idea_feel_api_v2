<?php

namespace App\Http\Controllers\Verified;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;

class VerifiedTestController extends Controller
{
    public function test()
    {
        return response()->json('access success', Response::HTTP_OK);
    }
}
