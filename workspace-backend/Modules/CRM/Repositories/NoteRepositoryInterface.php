<?php

namespace Modules\CRM\Repositories;

use Illuminate\Http\Request;

interface NoteRepositoryInterface
{
    public function setNote(Request $request);
    
    public function getNotes(Request $request);
}