<?php

namespace App\Http\Controllers;

use App\Provider;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PrepareNoticeRequest;
use App\Notice;
use Illuminate\Support\Facades\Mail;

class NoticesController extends Controller
{
    /**
     * Create a new notice controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');

        parent::__construct();
    }

    /**
     * Show all notices.
     *
     * @return string
     */
    public function index()
    {
        return $this->user->notices;
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

    /**
     * Ask the user to confirm the DMCA that will be delivered.
     *
     * @param PrepareNoticeRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirm(PrepareNoticeRequest $request)
    {
        $template = $this->compileDmcaTemplate($request);

        session()->flash('dmca', $request->all());
        return view('notices.confirm', compact('template'));
    }

    /**
     * Compile the DMCA template from the form data.
     *
     * @param PrepareNoticeRequest $request
     * @param Guard $auth
     * @return \Illuminate\Contracts\View\View
     */
    protected function compileDmcaTemplate(PrepareNoticeRequest $request)
    {
        $data = $request->all() + [
                'name' => $this->user->name,
                'email' => $this->user->email,
            ];

        return view()->file(app_path('Http/Templates/dmca.blade.php'), $data);
    }

    /**
     * Store a new DMCA notice.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // Form data is flashed. Get with session()->get('dmca')
        // Template is in request. Request::input('template')
        // So build up a Notice object (create table too)
        // persist it with this data.

        $notice = $this->createNotice($request);
//        Auth::user()->notices()->create(array); // Works the same

        // And then fire off the email.
        Mail::queue('emails.dmca', compact('notice'), function($message) use ($notice) {
            $message->from($notice->getOwnerEmail())
                    ->to($notice->getRecipientEmail())
                    ->subject('DMCA Notice');
        });

//        return Notice::first();
        return redirect('notices');
    }

    /**
     * Create and persist a new DMCA notice.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createNotice(Request $request)
    {
        $notice = session()->get('dmca') + ['template' => $request->input('template')];

        $notice = $this->user->notices()->create($notice);

        return $notice;
    }
}
