<?php

namespace Skay\Exception;

use Skay\Exception\BaseException;

/**
 * Class ErrorException
 */
class ErrorException extends BaseException
{
   function __construct(string $msg, int $code = 0, Throwable $previous = null)
   {
      parent::__construct($msg, $code, $previous);
   }

   public function __toString()
   {
      return "[{$this->code}]: $this->message";
   }
}
