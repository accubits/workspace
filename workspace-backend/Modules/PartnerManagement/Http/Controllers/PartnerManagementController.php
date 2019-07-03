<?php

namespace Modules\PartnerManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PartnerManagement\Repositories\PartnerRepositoryInterface;

class PartnerManagementController extends Controller
{

    protected $partner;
    public function __construct(PartnerRepositoryInterface $partner)
    {
        $this->partner = $partner;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function fetchAllPartners()
    {
        $response = $this->partner->fetchAllPartners();
        return response()->json($response, $response['code']);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('partnermanagement::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('partnermanagement::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('partnermanagement::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
