<?php

namespace App\Http\Controllers;

use App\Models\LinkDetails;
use App\Models\ShortUrl;
use App\Models\Tag;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LinkShortenerController extends Controller
{
    public function shortlink(Request $request)
    {
        request()->validate([
            'long_url' => 'required',
        ]);

        $shortUrl = $this->encodeThisUrl();

        $Url = new ShortUrl();
        $Url->fk_user_id = Auth::user()->id;
        if ($request->tag) $Url->fk_tag_id = $request->tag;
        $Url->long_url = $request->long_url;
        $Url->short_url = url('/') . "/" . $shortUrl;
        $Url->link_status = 'active';
        $Url->save();

        $response = [
            'msg' => 'Link has been shortened',
            'url' => $Url->id
        ];

        return response($response, 201);
    }

    public function encodeThisUrl()
    {
        $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shortUrl = '';

        for ($i = 0; $i < 5; $i++) {
            $shortUrl .= $base[rand(0, 61)];
        }

        while (1) {
            $duplicate = ShortUrl::where('short_url', $shortUrl)->first();

            if ($duplicate) {
                $shortUrl = '';
                for ($i = 0; $i < 7; $i++) {
                    $shortUrl .= $base[rand(0, 61)];
                }
            } else {
                break;
            }
        }
        return $shortUrl;
    }

    public function myURL()
    {
        $urlList = ShortUrl::where('fk_user_id', Auth::user()->id)->get();

        $response = [
            'urlList' => $urlList
        ];

        return response($response, 201);
    }

    public function urlDetails($id)
    {
        $link = ShortUrl::where('id', $id)->first();

        //total clicks
        $totalClicks = LinkDetails::where('fk_short_url', $id)->count('fk_short_url');

        //current day click
        $dailyClicks = LinkDetails::where('fk_short_url', $id)->whereDate('created_at', carbon::today()->toDate())->count('fk_short_url');

        //avg click
        $averageClicks = LinkDetails::where('fk_short_url', $id)
            ->selectRaw('COUNT(DATE(created_at))')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()->avg('COUNT(DATE(created_at))');

//        pie chart
        $deviceTypes = LinkDetails::where('fk_short_url', $id)
            ->select(DB::raw('client_device, COUNT(client_device) as total_client_device'))
            ->groupBy('client_device')
            ->get();

//        $dailyClickStat = [];
//        $c = $i = $j = $k = 0;
//
//        $dateObj = DateTime::createFromFormat('!m', Carbon::now()->month);
//        $currentMonth = $dateObj->format('F');
//        $currentYear = Carbon::now()->year;
//
//        $daysInMonth = Carbon::now()->daysInMonth;
//        $c = $i = $j = $k = 0;
//
//        while ($i < $daysInMonth) {
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

        $response = [
            'link' => $link,
            'deviceTypes' => $deviceTypes,
            'totalClicks' => $totalClicks,
            'dailyClicks' => $dailyClicks,
            'averageClicks' => $averageClicks,
        ];

        return response($response, 201);

    }

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

    public function updateUrl(Request $request, $id)
    {
        request()->validate([
            'longUrl' => 'required',
            'shortUrl' => 'required',
            'status' => 'required',
        ]);

        $url = ShortUrl::where('id', $id)->first();

        if ($url) {
            $url->long_url = $request->input('longUrl');
            $url->short_url = $request->input('shortUrl');
            $url->link_status = $request->input('status');
            $url->save();
        } else {
            $response = [
                'msg' => "URL Doesn't exist",
            ];
            return response($response, 404);
        }

        $response = [
            'msg' => "URL has been successfully updated",
        ];
        return response($response, 201);
    }

}
