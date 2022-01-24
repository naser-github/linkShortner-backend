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
//
//        $mobileCount = 0;
//        $desktopCount = 0;
//        $otherCount = 0;
//        $size = sizeof($devices);
//
//        if ($size > 0) {
//            foreach ($devices as $device) {
//                if ($device->client_device == 'phone')
//                    $mobileCount++;
//                elseif ($device->client_device == 'desktop')
//                    $desktopCount++;
//                else
//                    $otherCount++;
//            }
//
//            $mobile = (100 * $mobileCount) / $size;
//            $desktop = (100 * $desktopCount) / $size;
//            $other = (100 * $otherCount) / $size;
//        } else {
//            $mobile = 0;
//            $desktop = 0;
//            $other = 0;
//        }

        $response = [
            'totalLinks' => $totalLinks,
            'linkToday' => $linkToday,
            'visitedToday' => $visitedToday,
        ];

        return response($response, 201);
    }
}
