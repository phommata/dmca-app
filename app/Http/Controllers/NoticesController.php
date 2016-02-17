<?php

namespace App\Http\Controllers;

use App\Provider;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PrepareNoticeRequest;

class NoticesController extends Controller
{
    /**
     * Create a new notice controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show all notices.
     *
     * @return string
     */
    public function index()
    {
        return 'all notices';
    }

    /**
     * Show a page to create a new notice.
     *
     * @return \Response
     */
    public function create()
    {
        // get list of providers
        $providers = Provider::lists('name', 'id');

        // load a view to create a new notice
        return view('notices.create', compact('providers'));
    }

    public function confirm(PrepareNoticeRequest $request, Guard $auth)
    {
        $data = $request->all() + [
            'name' => $auth->user()->name,
            'email' => $auth->user()->email,
        ];

        $template = view()->file(app_path('Http/Templates/dmca.blade.php'), $data);

        return view('notices.confirm', compact('template'));
    }
}
