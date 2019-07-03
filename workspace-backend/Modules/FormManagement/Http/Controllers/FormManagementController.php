<?php

namespace Modules\FormManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\FormManagement\Repositories\FormCreatorRepositoryInterface;
use Modules\FormManagement\Repositories\FormFetcherRepositoryInterface;
use Modules\FormManagement\Repositories\FormSubmissionRepositoryInterface;
use Modules\FormManagement\Repositories\FormSubmissionResponseRepositoryInterface;

class FormManagementController extends Controller
{
    
    private $formcreator;
    private $formfetcher;
    private $formsubmission;
    private $formsubmitResponse;

    public function __construct(
        FormCreatorRepositoryInterface $formcreator,
        FormFetcherRepositoryInterface $formfetcher,
        FormSubmissionRepositoryInterface $formsubmission,
        FormSubmissionResponseRepositoryInterface $formsubmitResponse
    )
    {
        $this->formcreator = $formcreator;
        $this->formfetcher = $formfetcher;
        $this->formsubmission = $formsubmission;
        $this->formsubmitResponse = $formsubmitResponse;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('formmanagement::index');
    }

    /**
     * create a form
     * @return Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'max:250',
            'formAccessType' => 'required',
            'formStatus' => 'required'
           ]);       
        $response = $this->formcreator->create($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * create a form
     * @return Response
     */
    public function updateFormStatus(Request $request)
    {
        $request->validate([
            'formSlug' => 'required',
            'formStatus' => 'required'
           ]);       
        $response = $this->formcreator->updateFormStatus($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * share a form
     * @return Response
     */
    public function share(Request $request)
    {
        $request->validate([
            'formSlug' => 'required'
           ]);       
        $response = $this->formcreator->share($request);
        return response()->json($response, $response['code']);
    }

    /**
     * get form share list
     * @return Response
     */
    public function getShareList(Request $request)
    {
        $request->validate([
            'formSlug' => 'required'
           ]);       
        $response = $this->formfetcher->getShareList($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * send a form for submission
     * @return Response
     */
    public function sendForm(Request $request)
    {
        $request->validate([
            'formSlug' => 'required'
           ]);       
        $response = $this->formcreator->formSend($request);
        return response()->json($response, $response['code']);
    }

    /**
     * get form send list
     * @return Response
     */
    public function getSendFormList(Request $request)
    {
        $request->validate([
            'formSlug' => 'required'
           ]);       
        $response = $this->formfetcher->getFormSendList($request);
        return response()->json($response, $response['code']);
    }
    
    
    /**
     * fetch a form
     * @param  String $form_slug
     * @return Response
     */
    public function fetch($form_slug)
    {
        $response = $this->formfetcher->fetch($form_slug);
        return response()->json($response, $response['code']);
    }

    /*
     * get form components types
     * @return Response
     */
    public function componentTypes()
    {
        $response = $this->formfetcher->componentTypes();
        return response()->json($response, $response['code']);
    }
    
    
    /**
     * fetch a all form
     * 
     * @return Response
     */
    public function getAllForms(Request $request)
    {
        $response = $this->formfetcher->getAllForms($request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * fetch a client form
     * @param  String $form_slug
     * @return Response
     */
    public function fetchClientForm($form_slug)
    {
        $response = $this->formfetcher->fetchClientForm($form_slug);
        return response()->json($response, $response['code']);
    }

    /**
     * submit a client form
     * @param  String formResponse (JSON)
     * @return Response
     */
    public function clientFormSubmission(Request $request, $form_slug)
    {
        $response = $this->formsubmission->save($form_slug, $request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * fetch all client form response
     * @param  String formResponse (JSON)
     * @return Response
     */
    public function fetchAllClientFormResponses(Request $request, $form_slug)
    {
        $response = $this->formsubmitResponse->getAllAnswersheet($form_slug, $request);
        return response()->json($response, $response['code']);
    }
    
    /**
     * fetch a single client form response
     * @param  String formResponse (JSON)
     * @return Response
     */
    public function fetchClientFormResponse(Request $request, $form_slug)
    {
        $response = $this->formsubmitResponse->getAnswersheet($form_slug, $request);
        return response()->json($response, $response['code']);
    }

    /**
     * fetch a single getNonPaginatedAnswersheet client form response
     * @param  String formResponse (JSON)
     * @return Response
     */
    public function fetchNonPaginatedClientFormResponse(Request $request, $form_slug)
    {
        $response = $this->formsubmitResponse->getNonPaginatedAnswersheet($form_slug, $request);
        return response()->json($response, $response['code']);
    }
    /**
     * Bulk delete forms.
     * @return Response
     */
    public function deleteForms(Request $request)
    {
        $response = $this->formcreator->deleteForms($request);
        return response()->json($response, $response['code']);
    }
}
