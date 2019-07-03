<?php

namespace Modules\SocialModule\Http\Controllers;

use Illuminate\Http\Request;
use Modules\SocialModule\Http\Requests\MessageRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\SocialModule\Entities\SocialMessage;
use Modules\Common\Utilities\Utilities;
use Illuminate\Support\Facades\Auth;
use Modules\SocialModule\Repositories\MessageRepositoryInterface;
use Modules\SocialModule\Repositories\AnnouncementRepositoryInterface;

class MessageController extends Controller
{

    private $message;
    private $announcement;

    public function __construct(MessageRepositoryInterface $message, AnnouncementRepositoryInterface $announcement)
    {
        $this->message = $message;
        $this->announcement = $announcement;
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
     * create, update, delete message
     * @param  Request $request
     * @return Response
     */
    public function setMessage(Request $request)
    {
        $response = $this->message->createMessage($request);

        return response()->json($response, $response['code']);
    } 

    /**
     * create, update, delete Announcement
     * @param  Request $request
     * @return Response
     */
    public function setAnnouncement(Request $request)
    {
        $response = $this->announcement->createAnnouncement($request);

        return response()->json($response, $response['code']);
    }
    
    /**
     * set Announcement ReadStatus
     * @param  Request $request
     * @return Response
     */
    public function setAnnouncementReadStatus(Request $request)
    {
        $response = $this->announcement->setAnnouncementReadStatus($request);

        return response()->json($response, $response['code']);
    }

}
