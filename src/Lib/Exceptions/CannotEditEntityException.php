<?php

namespace BeeJeeMVC\Lib\Exceptions;

class CannotEditEntityException extends \Exception
{
    protected $message = 'Cannot edit the completed entity!';
}
