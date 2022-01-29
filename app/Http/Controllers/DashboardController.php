<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalLinks = ShortUrl::where('fk_user_id', Auth::user()->id)->count();

        $linkToday = DB::table('short_urls')
            ->where('fk_user_id', Auth::user()->id)
            ->whereDate('created_at', carbon::today()->toDate())
            ->count();

        $visitedToday = DB::table('link_details')
            ->leftJoin('short_urls', 'link_details.fk_short_url', '=', 'short_urls.id')
            ->whereDate('link_details.created_at', carbon::today()->toDate())
            ->where('short_urls.fk_user_id', '=', Auth::user()->id)
            ->count();

//        $devices = DB::table('link_visit_details')
//            ->leftJoin('short_urls', 'link_visit_details.fk_short_url', '=', 'short_urls.id')
//            ->where('short_urls.fk_user_id', '=', Auth::user()->id)
//            ->select('link_visit_details.client_device')
//            ->get();

        $deviceTypes = ShortUrl::where('short_urls.fk_user_id', '=', Auth::user()->id)
            ->leftJoin('link_details', 'link_details.fk_short_url', '=', 'short_urls.id')
            ->select(DB::raw('link_details.client_device, COUNT(link_details.client_device) as total_client_device'))
            ->groupBy('link_details.client_device')
            ->get();

        $response = [
            'totalLinks' => $totalLinks,
            'linkToday' => $linkToday,
            'visitedToday' => $visitedToday,
            'deviceTypes' => $deviceTypes,
        ];

        return response($response, 201);
    }
}
