<?php

namespace Modules\CRM\Repositories;

use Illuminate\Http\Request;

interface LeadRepositoryInterface
{
    public function setLead(Request $request);
    public function setLeadStatus(Request $request);
    
    public function getLeadDetails(Request $request);
    public function getLeads(Request $request);
    public function getCustomers(Request $request);

}