<?php

namespace Skay\Yandex\Translate;

use Curl\Curl;
use Skay\Yandex\Translate\Translation;

/**
 * Api
 */
class Api
{
	const BASE_URL = 'https://translate.yandex.net/api/v%s/tr%s/';
	/** русский (по умолчанию) */
	const LANG_RU = 'ru';
	/**
	 * @var string Версия используемого api
	 */
	protected $_version = '1.5';
	/**
	 * @var array
	 */
	protected $_filters = array();
	/**
	 * @var string
	 */
	protected $_method = false;

	/**
    * @param string $key
	 * @param null|string $version
	 */
	public function __construct($key, $version = null)
	{
		$this->key = $key;
		if (!empty($version)) {
			$this->_version = (string)$version;
		}
		$this->clear();
	}

	/**
	 * Очистка фильтров
	 * @return self
	 */
	public function clear()
	{
		$this->_filters = array();
		$this
		->setKey($this->key)
		->setLang(self::LANG_RU);
		$this->_response = null;
		return $this;
	}

	/**
     * Returns a list of translation directions supported by the service.
     * @link http://api.yandex.com/translate/doc/dg/reference/getLangs.xml
     *
     * @param string $culture If set, the service's response will contain a list of language codes
     *
     * @return array
     */
	public function getLangs()
	{
		$curl = self::initCurl( __METHOD__);
	}

	/**
     * Detects the language of the specified text.
     * @link http://api.yandex.com/translate/doc/dg/reference/detect.xml
     *
     * @param string $text The text to detect the language for.
     *
     * @return string
     */
	public function detect($text)
	{
		$this->_filters['text'] = $text;
		$curl = self::initCurl( __METHOD__);
	}

    /**
     * Translates the text.
     * @link http://api.yandex.com/translate/doc/dg/reference/translate.xml
     *
     * @param string|array $text     The text to be translated.
     * @param string       $language Translation direction (for example, "en-ru" or "ru").
     * @param bool         $html     Text format, if true - html, otherwise plain.
     * @param int          $options  Translation options.
     *
     * @return array
     */
    public function translate($text, $language, $html = false, $options = 1, $callback = false)
    {
    	$this->_filters += array(
    		'text'    => $text,
    		'lang'    => $language,
    		'format'  => $html ? 'html' : 'plain',
    		'options' => $options,
    		'callback' => $callback ? $callback : false,
    	);
    	$curl = self::initCurl( __METHOD__);

    	$res = $this->_response;
        // @TODO: handle source language detecting
    	return new Translation($text, $res->getText(), $res->getLang());
    }

	/**
	 * Предпочитаемый язык описания объектов
	 * @param string $lang
	 * @return self
	 */
	public function setLang($lang)
	{
		$this->_filters['ui'] = (string)$lang;
		return $this;
	}

	public function getXml($value)
	{
		if(!$value){
			return '.json';
		}
		return false;
	}

	/**
	 * Ключ API Яндекс.Переводчик
	 * @see https://translate.yandex.ru/developers/keys
	 * @param string $token
	 * @return self
	 */
	public function setKey($key)
	{
		$this->_filters['key'] = (string)$key;
		return $this;
	}

	/**
	 * @return Response
	 */
	public function getResponse()
	{
		return $this->_response;
	}

	public function initCurl($fullMethod)
	{
		$uri = $this->generateUri($fullMethod);
		$curl = new Curl();
		$curl->get($uri, $this->_filters);
		if($curl->error){
			throw new \Skay\Exception\CurlException($curl);
		}
      $data = json_decode($curl->response, true);
      if (empty($data)) {
         $msg = sprintf('Can\'t load data by url: %s', $apiUrl);
         throw new \Skay\Exception\BaseException($msg);
      }
      if (!empty($data['error'])) {
         throw new \Skay\Exception\ErrorException($data['message'], $data['statusCode']);
      }
		if(empty($this->_filters['callback'])) {
			$this->_response = new \Skay\Yandex\Translate\Response($data);
		}else{
			$data['jsonp'] = $curl->response;
			$this->_response = new \Skay\Yandex\Translate\Response($data);
		}

		return $this;
	}

	public function generateUri($fullMethod)
	{
		$base_uri = sprintf(self::BASE_URL, $this->_version, self::getXml($options['xml']));
		$this->_method = explode('::', $fullMethod)[1];
		$uri = $base_uri.$this->_method;
		return $uri;
	}

}
