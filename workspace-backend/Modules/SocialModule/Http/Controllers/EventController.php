<?php

namespace Modules\SocialModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\SocialModule\Repositories\EventRepositoryInterface;


class EventController extends Controller
{

    private $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('socialmodule::index');
    }

    /**
     * create, update, delete an Event
     * @return Response
     */
    public function setEvent(Request $request)
    {
        $response = $this->eventRepository->createEvent($request);
        return response()->json($response, $response['code']);
    }

    /**
     *  setEventStatus
     * @return Response
     */
    public function setEventStatus(Request $request)
    {
        $response = $this->eventRepository->setEventStatus($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * get an Event
     * @return Response
     */
    public function getEvent(Request $request)
    {
        $response = $this->eventRepository->getEvent($request);
        return response()->json($response, $response['code']);
    }
}
