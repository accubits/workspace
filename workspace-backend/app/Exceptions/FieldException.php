<?php

namespace App\Exceptions;

use Exception;

class FieldException extends Exception
{
    protected $_fieldsArr;
    public function __construct($message="", $code=0 , Exception $previous=NULL, $field = array())
    {
        $this->_fieldsArr = $field;
        parent::__construct($message, $code, $previous);
    }
    public function getField()
    {
        return $this->_fieldsArr;
    }
}
