<?php

namespace Modules\SocialModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\SocialModule\Repositories\CommentsRepositoryInterface;
use Modules\SocialModule\Repositories\AppreciationCommentsRepositoryInterface;
use Modules\SocialModule\Repositories\EventCommentsRepositoryInterface;
use Modules\SocialModule\Repositories\PollCommentsRepositoryInterface;

class CommentsController extends Controller
{
    
    protected $commentRespository;
    protected $aprCommentRespository;
    protected $eventCommentsRepository;
    protected $pollCommentsRepository;
    
    public function __construct(CommentsRepositoryInterface $comment,
            AppreciationCommentsRepositoryInterface $aprCommentRespository,
            EventCommentsRepositoryInterface $eventCommentsRepository,
            PollCommentsRepositoryInterface $pollCommentsRepository
            )
    {
        $this->commentRespository = $comment;
        $this->aprCommentRespository = $aprCommentRespository;
        $this->eventCommentsRepository = $eventCommentsRepository;
        $this->pollCommentsRepository = $pollCommentsRepository;
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
     * create, update, delete comment of a message
     * @param  Request $request
     * @return Response
     */
    public function setMessageComment(Request $request)
    {
        $response = $this->commentRespository->setMessageComment($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * create, update, delete comment response of a message 
     * @param  Request $request
     * @return Response
     */
    public function setMessageCommentResponse(Request $request)
    {
        $response = $this->commentRespository->setMessageCommentResponse($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * create, update, delete response(like) of a message 
     * @param  Request $request
     * @return Response
     */
    public function setMessageResponse(Request $request)
    {
        $response = $this->commentRespository->setMessageResponse($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * get messageComments, messageResponses, messageCommentResponses 
     * @param  Request $request
     * @return Response
     */
    public function getMessageCommentsAndResponses(Request $request)
    {
        $response = $this->commentRespository->getMessageCommentsAndResponses($request);
        return response()->json($response, $response['code']);
    }

    ///////////////////////////////////////////////////////////////////////////////////
    /**
     * create, update, delete comment of a announcement
     * @param  Request $request
     * @return Response
     */
    public function setAnnouncementComment(Request $request)
    {
        $response = $this->commentRespository->setAnnouncementComment($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * create, update, delete response(like) of a announcement
     * @param  Request $request
     * @return Response
     */
    public function setAnnouncementResponse(Request $request)
    {
        $response = $this->commentRespository->setAnnouncementResponse($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * create, update, delete comment response of a Announcement 
     * @param  Request $request
     * @return Response
     */
    public function setAnnouncementCommentResponse(Request $request)
    {
        $response = $this->commentRespository->setAnnouncementCommentResponse($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * get AnnouncementComments, AnnouncementResponses, AnnouncementCommentResponses 
     * @param  Request $request
     * @return Response
     */
    public function getAnnouncementCommentsAndResponses(Request $request)
    {
        $response = $this->commentRespository->getAnnouncementCommentsAndResponses($request);
        return response()->json($response, $response['code']);
    }
    
    ///////////////////////////////////////////////////////////////////
    
    /////////////////Appreciation//////////////////////////////////////
    /**
     * create, update, delete comment of a appreciation
     * @param  Request $request
     * @return Response
     */
    public function setAppreciationComment(Request $request)
    {
        $response = $this->aprCommentRespository->setAppreciationComment($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * create, update, delete response(like) of a appreciation
     * @param  Request $request
     * @return Response
     */
    public function setAppreciationResponse(Request $request)
    {
        $response = $this->aprCommentRespository->setAppreciationResponse($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * create, update, delete comment response of a Appreciation 
     * @param  Request $request
     * @return Response
     */
    public function setAppreciationCommentResponse(Request $request)
    {
        $response = $this->aprCommentRespository->setAppreciationCommentResponse($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * get AppreciationComments, AppreciationResponses, AppreciationCommentResponses 
     * @param  Request $request
     * @return Response
     */
    public function getAppreciationCommentsAndResponses(Request $request)
    {
        $response = $this->aprCommentRespository->getAppreciationCommentsAndResponses($request);
        return response()->json($response, $response['code']);
    }
    
    ///////////////////////////////////////////////////////////////////

    /////////////////Event//////////////////////////////////////
    /**
     * create, update, delete comment of a Event
     * @param  Request $request
     * @return Response
     */
    public function setEventComment(Request $request)
    {
        $response = $this->eventCommentsRepository->setEventComment($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * create, update, delete response(like) of a Event
     * @param  Request $request
     * @return Response
     */
    public function setEventResponse(Request $request)
    {
        $response = $this->eventCommentsRepository->setEventResponse($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * create, update, delete comment response of a Event 
     * @param  Request $request
     * @return Response
     */
    public function setEventCommentResponse(Request $request)
    {
        $response = $this->eventCommentsRepository->setEventCommentResponse($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * get EventComments, EventResponses, EventCommentResponses 
     * @param  Request $request
     * @return Response
     */
    public function getEventCommentsAndResponses(Request $request)
    {
        $response = $this->eventCommentsRepository->getEventCommentsAndResponses($request);
        return response()->json($response, $response['code']);
    }
    
    ///////////////////////////////////////////////////////////////////
    
    /////////////////Poll//////////////////////////////////////
    /**
     * create, update, delete comment of a Poll
     * @param  Request $request
     * @return Response
     */
    public function setPollComment(Request $request)
    {
        $response = $this->pollCommentsRepository->setPollComment($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * create, update, delete response(like) of a Poll
     * @param  Request $request
     * @return Response
     */
    public function setPollResponse(Request $request)
    {
        $response = $this->pollCommentsRepository->setPollResponse($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * create, update, delete comment response of a Poll 
     * @param  Request $request
     * @return Response
     */
    public function setPollCommentResponse(Request $request)
    {
        $response = $this->pollCommentsRepository->setPollCommentResponse($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * get PollComments, PollResponses, PollCommentResponses 
     * @param  Request $request
     * @return Response
     */
    public function getPollCommentsAndResponses(Request $request)
    {
        $response = $this->pollCommentsRepository->getPollCommentsAndResponses($request);
        return response()->json($response, $response['code']);
    }
    
    ///////////////////////////////////////////////////////////////////
}
