<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
class PingController extends Controller
{
    public function pong()
    {
        return response()->json(['message' => 'pong from PingController']);
    }
}