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

        $linkDetails = LinkDetails::where('fk_short_url', $id)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->orderBy('id')
            ->get();

        //total clicks
        $totalClicks = LinkDetails::where('fk_short_url', $id)->count('fk_short_url');

        //current day click
        $dailyClicks = LinkDetails::where('fk_short_url', $id)->whereDate('created_at', carbon::today()->toDate())->count('fk_short_url');

        //avg click
        if (count($linkDetails) > 0) {
            $inSec = strtotime($linkDetails->last()->created_at) - strtotime($linkDetails->first()->created_at);
            if ($inSec > 0) {
                $days = ceil($inSec / 86400);
                $avgClicks = round($totalClicks / $days, 2);
            } else
                $avgClicks = 1;
        } else {
            $avgClicks = 0;
        }

//        $dailyClickStat = [];
//        $c = $i = $j = $k = 0;
//
//        $dateObj = DateTime::createFromFormat('!m', Carbon::now()->month);
//        $currentMonth = $dateObj->format('F');
//        $currentYear = Carbon::now()->year;

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


        //pie chart
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

        $response = [
            'link' => $link,
            'totalClicks' => $totalClicks,
            'dailyClicks' => $dailyClicks,
            'avgClicks' => $avgClicks,

        ];

        return response($response, 201);

    }

    public function filterData(Request $request)
    {
        $linkDetails = DB::table('link_visit_details')
            ->where('fk_short_url', '=', $request->link_id)
            ->whereMonth('created_at', $request->month)
            ->whereYear('created_at', $request->year)
            ->orderBy('id')
            ->get();

        $dailyClickStat = [];
        $c = $i = $j = $k = 0;
        $size = sizeof($linkDetails) - 1;
        $dateObj = DateTime::createFromFormat('!m', $request->month);
        $currentMonth = $dateObj->format('F');
        $currentYear = $request->year;

        if ($request->month == '1' || $request->month == '3' || $request->month == '5' || $request->month == '7' || $request->month == '8' || $request->month == '10' || $request->month == '12')
            $limit = 31;
        else
            $limit = 30;

        while ($i < $limit) {
            while ($j <= $size) {
                if (date('d', strtotime($linkDetails[$j]->created_at)) == $i + 1)
                    $c++;
                else
                    break;
                $j++;
            }
            $dailyClickStat[$i] = $c;
            $c = 0;
            $i++;
        }
        return view(
            'pages.operator.myUrl.filterData',
            compact('dailyClickStat', 'currentMonth', 'currentYear')
        );

    }

    public function updateUrl(Request $request, $id)
    {
        request()->validate([
            'url' => 'required',
            'status' => 'required'
        ]);

        $url = ShortUrl::where('id', $id)->first();

        if ($url->id) {

            if ($request->input('tag') != null) {
                $tag = UrlTag::where('id', $request->input('tag'))->first();

                if (!$tag)
                    return back();
            }
            $url->long_url = $request->input('url');
            $url->fk_tag_id = $request->input('tag');
            $url->link_status = $request->input('status');
            $url->save();
        } else {
            return back();
        }

        if (Auth::user()->getRoleNames()[0] == 'operator')
            return Redirect::route('mylink');
        else
            return Redirect::route('url_management');

    }

    public function editModal(Request $request)
    {
        if (Auth::user()->getRoleNames()[0] == 'operator')
            $tags = UrlTag::where('fk_department_id', Auth::user()->profile->fk_user_department)
                ->where('tag_status', 'active')
                ->get();
        else
            $tags = UrlTag::where('tag_status', 'active')->get();
        $shortTagId = $request->input('id');

        return view('pages.operator.myUrl.url-tag-edit-modal',
            compact('tags', 'shortTagId'));
    }

}
