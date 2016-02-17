<?php

namespace App\Http\Controllers;

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

        // load a view to create a new notice
        return view('notices.create');
    }

    public function confirm(PrepareNoticeRequest $request)
    {

    }
}
