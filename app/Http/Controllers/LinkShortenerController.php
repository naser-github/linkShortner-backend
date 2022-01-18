<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\Tag;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LinkShortenerController extends Controller
{
    public function shortlink(Request $request)
    {
        return $request->all();

//        request()->validate([
//            'long_url' => 'required',
//        ]);
//
//        $url = $request->input('long_url');
////        $tag = $request->input('tag');
//
//        $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//        $path = url('/');
//        $shortUrl = '';
//
//        for ($i = 0; $i < 5; $i++) {
//            $shortUrl .= $base[rand(0, 61)];
//        }
//
//        while (1) {
//            $duplicate = ShortUrl::where('short_url', $shortUrl)->first();
//
//            if ($duplicate) {
//                $shortUrl = '';
//                for ($i = 0; $i < 7; $i++) {
//                    $shortUrl .= $base[rand(0, 61)];
//                }
//            } else {
//                break;
//            }
//        }
//
//        $short = new ShortUrl();
//        $short->fk_user_id = Auth::user()->id;
//        if ($tag) {
//            $short->fk_tag_id = $tag;
//        }
//        $short->long_url = $url;
//        $short->short_url = $path . "/" . $shortUrl;
//        $short->link_status = 'active';
//        $short->save();
//
//        $response = [
//            'msg' => 'Link has been shortened'
//        ];
//
//        return response($response, 201);
    }

//    public function myURL()
//    {
//        $shortUrl = ShortUrl::where('fk_user_id', Auth::user()->id)->get();
//
//        $response = [
//            'url' => $shortUrl
//        ];
//
//        return response($response, 201);
//    }
//
//    public function showDetails($id)
//    {
//        $link = ShortUrl::where('id', $id)->first();
//
//        $linkDetails = DB::table('link_visit_details')
//            ->where('fk_short_url', '=', $id)
//            ->whereMonth('created_at', Carbon::now()->month)
//            ->whereYear('created_at', Carbon::now()->year)
//            ->orderBy('id')
//            ->get();
//
//        $dailyClickStat = [];
//        $c = $i = $j = $k = 0;
//        $size = sizeof($linkDetails) - 1;
//
//        $dateObj = DateTime::createFromFormat('!m', Carbon::now()->month);
//        $currentMonth = $dateObj->format('F');
//        $currentYear = Carbon::now()->year;
//
//        if (carbon::now()->month == 1 || carbon::now()->month == 3 || carbon::now()->month == 5 || carbon::now()->month == 7 || carbon::now()->month == 8 || carbon::now()->month == 10 || carbon::now()->month == 12)
//            $limit = 31;
//        else
//            $limit = 30;
//
//        while ($i < $limit) {
//            while ($j <= $size) {
//                if (date('d', strtotime($linkDetails[$j]->created_at)) == $i + 1)
//                    $c++;
//                else
//                    break;
//                $j++;
//            }
//            $dailyClickStat[$i] = $c;
//            $c = 0;
//            $i++;
//        }
//
//        //total clicks
//        $totalClicks = DB::table('link_visit_details')
//            ->where('fk_short_url', '=', $id)
//            ->count('fk_short_url');
//
//        //current day click
//        $dailyClicks = DB::table('link_visit_details')
//            ->where('fk_short_url', '=', $id)
//            ->whereDate('created_at', carbon::today()->toDate())
//            ->count('fk_short_url');
//
//        //avg click
//        if (!empty($linkDetails[0])) {
//            $secs = strtotime($linkDetails[$size]->created_at) - strtotime($linkDetails[0]->created_at);
//            $days = $secs / 86400;
//            if ($days == 0)
//                $avg = 1;
//            else
//                $avg = round($totalClicks / $days, 2);
//        } else {
//            $avg = 0;
//        }
//
//        //pie chart
//        $mobileCount = 0;
//        $desktopCount = 0;
//        $otherCount = 0;
//        $size = sizeof($linkDetails);
//
//        if ($size > 0) {
//            foreach ($linkDetails as $device) {
//                if ($device->client_device == 'phone')
//                    $mobileCount++;
//                elseif ($device->client_device == 'desktop')
//                    $desktopCount++;
//                else
//                    $otherCount++;
//            }
//            $mobile = (100 * $mobileCount) / $size;
//            $desktop = (100 * $desktopCount) / $size;
//            $other = (100 * $otherCount) / $size;
//        } else {
//            $mobile = 0;
//            $desktop = 0;
//            $other = 0;
//        }
//
//
//        return view(
//            'pages.operator.myUrl.showDetails',
//            compact('link', 'totalClicks', 'dailyClicks', 'avg', 'dailyClickStat', 'desktop', 'mobile', 'other', 'currentMonth', 'currentYear')
//        );
//
//    }
//
//    public function filterData(Request $request)
//    {
//        $linkDetails = DB::table('link_visit_details')
//            ->where('fk_short_url', '=', $request->link_id)
//            ->whereMonth('created_at', $request->month)
//            ->whereYear('created_at', $request->year)
//            ->orderBy('id')
//            ->get();
//
//        $dailyClickStat = [];
//        $c = $i = $j = $k = 0;
//        $size = sizeof($linkDetails) - 1;
//        $dateObj = DateTime::createFromFormat('!m', $request->month);
//        $currentMonth = $dateObj->format('F');
//        $currentYear = $request->year;
//
//        if ($request->month == '1' || $request->month == '3' || $request->month == '5' || $request->month == '7' || $request->month == '8' || $request->month == '10' || $request->month == '12')
//            $limit = 31;
//        else
//            $limit = 30;
//
//        while ($i < $limit) {
//            while ($j <= $size) {
//                if (date('d', strtotime($linkDetails[$j]->created_at)) == $i + 1)
//                    $c++;
//                else
//                    break;
//                $j++;
//            }
//            $dailyClickStat[$i] = $c;
//            $c = 0;
//            $i++;
//        }
//        return view(
//            'pages.operator.myUrl.filterData',
//            compact('dailyClickStat', 'currentMonth', 'currentYear')
//        );
//
//    }
//
//    public function updateUrl(Request $request, $id)
//    {
//        request()->validate([
//            'url' => 'required',
//            'status' => 'required'
//        ]);
//
//        $url = ShortUrl::where('id', $id)->first();
//
//        if ($url->id) {
//
//            if ($request->input('tag') != null) {
//                $tag = UrlTag::where('id', $request->input('tag'))->first();
//
//                if (!$tag)
//                    return back();
//            }
//            $url->long_url = $request->input('url');
//            $url->fk_tag_id = $request->input('tag');
//            $url->link_status = $request->input('status');
//            $url->save();
//        } else {
//            return back();
//        }
//
//        if (Auth::user()->getRoleNames()[0] == 'operator')
//            return Redirect::route('mylink');
//        else
//            return Redirect::route('url_management');
//
//    }
//
//    public function editModal(Request $request)
//    {
//        if (Auth::user()->getRoleNames()[0] == 'operator')
//            $tags = UrlTag::where('fk_department_id', Auth::user()->profile->fk_user_department)
//                ->where('tag_status', 'active')
//                ->get();
//        else
//            $tags = UrlTag::where('tag_status', 'active')->get();
//        $shortTagId = $request->input('id');
//
//        return view('pages.operator.myUrl.url-tag-edit-modal',
//            compact('tags', 'shortTagId'));
//    }

}
