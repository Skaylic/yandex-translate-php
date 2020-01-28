<?php

namespace Skay\Exception;

/**
 * BaseException
 */
class BaseException extends \Exception
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
