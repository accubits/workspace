<?php

namespace Modules\CRM\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\CRM\Repositories\LeadRepositoryInterface;
use Modules\CRM\Repositories\NoteRepositoryInterface;

class LeadController extends Controller
{
    
    private $noteRepository;
    private $leadRepository;

    public function __construct(
        LeadRepositoryInterface $leadRepository,
        NoteRepositoryInterface $noteRepository
    )
    {
        $this->leadRepository = $leadRepository;
        $this->noteRepository = $noteRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('crm::index');
    }

    public function setLead(Request $request)
    {
        $response = $this->leadRepository->setLead($request);
        return response()->json($response, $response['code']);        
    }
    
    public function setLeadStatus(Request $request)
    {
        $response = $this->leadRepository->setLeadStatus($request);
        return response()->json($response, $response['code']);        
    }
    
    public function getLeads(Request $request)
    {
        $response = $this->leadRepository->getLeads($request);
        return response()->json($response, $response['code']);        
    }

    public function getLead(Request $request)
    {
        $response = $this->leadRepository->getLeadDetails($request);
        return response()->json($response, $response['code']);        
    }
    
    public function getCustomers(Request $request)
    {
        $response = $this->leadRepository->getCustomers($request);
        return response()->json($response, $response['code']);        
    }
    
    public function setNote(Request $request)
    {
        $response = $this->noteRepository->setNote($request);
        return response()->json($response, $response['code']);        
    }
    
    public function getNotes(Request $request)
    {
        $response = $this->noteRepository->getNotes($request);
        return response()->json($response, $response['code']);
    }

}
