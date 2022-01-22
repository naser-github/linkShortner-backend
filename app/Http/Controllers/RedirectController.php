<?php

namespace App\Http\Controllers;

use App\Models\LinkDetails;
use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;


class RedirectController extends Controller
{
    public function search($id)
    {

        $path = url('/') . "/" . $id;

        $res = ShortUrl::where('short_url', $path)->first();

        if (!$res) {
            return response([
                'message' => 'This url does not exist.'
            ], 401);
        } elseif ($res->link_status == 'inactive')
            return response([
                'message' => 'This url is inactive.'
            ], 401);

        $this->saveUserData($res->id);
        return redirect($res->long_url);
    }

    public function saveUserData($id)
    {
        $agent = new Agent();
        $log = new LinkDetails();
        $log->fk_short_url = $id;
        $log->client_ip = \Request::ip();
        $log->client_os = $agent->platform();
        $log->client_browser = $agent->browser();
        $log->client_device = $agent->deviceType();
        $log->save();
    }
}
