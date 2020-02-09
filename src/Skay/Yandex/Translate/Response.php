<?php

namespace Skay\Yandex\Translate;

/**
 * Class Response
 * @package Yandex\Translate
 * @license The MIT License (MIT)
 */
class Response
{
   /**
    * @var array
    */
   protected $_data;

   function __construct(array $data)
   {
      $this->_data = $data;
   }

   /**
    * Исходные данные
    * @return array
    */
   public function getData()
   {
      return $this->_data;
   }

   /**
    * @return \Yandex\Code
    */
   public function getCode()
   {
      $result = $this->_data['code'];
      return $result;
   }

   /**
    * @return \Yandex\Lang
    */
   public function getLang()
   {
      $result = $this->_data['lang'];
      return $result;
   }

   /**
    * @return \Yandex\Lang
    */
   public function getDetected()
   {
      $result = $this->_data['detected'];
      return $result;
   }

   /**
    * @return \Yandex\Lang
    */
   public function getText()
   {
      $result = $this->_data['text'];
      return $result;
   }

   /**
    * @return \Yandex\Lang
    */
   public function getJsonp()
   {
      $result = $this->_data['jsonp'];
      return $result;
   }
}
