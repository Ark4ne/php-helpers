<?php

namespace Ark4ne\Helpers;

class URL
{
	const URL_REGEX = "/((\\w+)?:\\/\\/)?([\\w\\d-]+[\\w\\d.-]*\\.[\\w]+\\/?)?([\\w\\d\\/._-]+)?(\\?[^#]*)?(#[\\w\\d_-]+)?/";

	/**
	 * @var bool $secure
	 */
	private $secure;

	/**
	 * @var string $protocol
	 */
	private $protocol;

	/**
	 * @var string $domain
	 */
	private $domain;

	/**
	 * @var string $page
	 */
	private $page;

	/**
	 * @var string $params
	 */
	private $params;

	/**
	 * @var string $anchor
	 */
	private $anchor;

	public function __construct($url = '')
	{
		if ($url) {
			preg_match(static::URL_REGEX, $url, $raw_url);
			$this->init(@$raw_url[2], @$raw_url[3], @$raw_url[4], @$raw_url[5], @$raw_url[6]);
		}
		else {
			$this->init();
		}
	}

	private function init($protocol = null, $domain = null, $page = null, $query = null, $anchor = null)
	{
		$this->setSecureString($protocol);
		$this->setProtocolString($protocol);
		$this->setDomainString($domain);
		$this->setPageString($page);
		$this->setParamsString($query);
		$this->setAnchorString($anchor);
	}

	/**
	 * @param string $protocol
	 */
	public function setSecureString($protocol)
	{
		$protocol = trim($protocol);
		$secure   = false;
		if (ends_with($protocol, 's')) {
			$secure = true;
		}

		$this->setSecure($secure);
	}

	/**
	 * @param string $protocol
	 */
	public function setProtocolString($protocol)
	{
		$protocol = trim($protocol);

		if (ends_with($protocol, 's')) {
			$protocol = substr($protocol, 0, strlen($protocol) - 1);
		}

		$this->setProtocol($protocol);
	}

	/**
	 * @param string $domain
	 */
	public function setDomainString($domain)
	{
		$domain = trim($domain);
		if (ends_with($domain, '/')) {
			$domain = substr($domain, 0, strlen($domain) - 1);
		}
		$this->setDomain($domain);
	}

	/**
	 * @param string $page
	 */
	public function setPageString($page)
	{
		$page = trim($page);
		if (starts_with($page, '/')) {
			$page = substr($page, 1, strlen($page));
		}

		$this->setPage($page);
	}

	/**
	 * @param string $params
	 */
	public function setParamsString($params)
	{
		$params    = trim($params);
		$raw_query = [];
		if ($params) {
			if (starts_with($params, '?')) {
				$params = substr($params, 1, strlen($params) - 1);
			}

			$params = str_replace('&amp;', '&', $params);

			if (str_contains($params, '&')) {
				$data = explode('&', $params);
			}
			else {
				$data = [$params];
			}

			$raw_data = [];

			foreach ($data as $q) {
				if (str_contains($q, '=')) {
					$q = explode('=', $q);
				}
				else {
					$q = [$q];
				}
				$raw_data[] = object(['key' => $q[0], 'value' => @$q[1]]);
			}

			foreach ($raw_data as $data) {
				if (ends_with($data->key, '[]')) {
					$key = str_replace('[]', '', $data->key);
					if (!array_key_exists($key, $raw_query)) {
						$raw_query[$key] = [];
					}
					if (!in_array($data->value, $raw_query[$key])) {
						$raw_query[$key][] = $data->value;
					}
				}
				else {
					$raw_query[$data->key] = $data->value;
				}
			}
		}
		$this->setParams($raw_query);
	}

	/**
	 * @param string $anchor
	 */
	public function setAnchorString($anchor)
	{
		$anchor = trim($anchor);
		if (starts_with($anchor, '#')) {
			$anchor = substr($anchor, 1, strlen($anchor));
		}
		$this->setAnchor($anchor);
	}

	public function addParam($key, $value)
	{
		$query = $this->getParams();

		if (array_key_exists($key, $query)) {
			$keyValue = $query[$key];
			if (is_array($keyValue) && !in_array($value, $keyValue, true)) {
				$query[$key][] = $value;
			}
			elseif (!is_array($keyValue)) {
				$query[$key] = $value;
			}
		}
		else {
			$query[$key] = $value;
		}

		$this->setParams($query);
	}

	/**
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @param array $params
	 */
	public function setParams($params)
	{
		$this->params = $params;
	}

	public function __toString()
	{
		return $this->format();
	}

	public function format($secure = null)
	{
		if ($secure === null) {
			$secure = $this->isSecure();
		}
		$_secure = $this->isSecure();
		$this->setSecure($secure);
		$protocol = $this->getProtocolString();
		$domain   = $this->getDomainString();
		$page     = $this->getPage();
		$query    = $this->getParamsString();
		$anchor   = $this->getAnchorString();
		$this->setSecure($_secure);

		if (!$domain && !$protocol) {
			$page = '/' . $page;
		}
		elseif ($domain && !$protocol) {
			$protocol = 'http' . ($secure ? 's' : '') . '://';
		}

		return $protocol . $domain . $page . $query . $anchor;
	}

	/**
	 * @return bool
	 */
	public function isSecure()
	{
		return $this->secure;
	}

	/**
	 * @param bool $secure
	 */
	public function setSecure($secure)
	{
		$this->secure = $secure;
	}

	/**
	 * @return string
	 */
	public function getProtocolString()
	{
		$protocol = $this->getProtocol();

		return $protocol . ($protocol ? ($this->isSecure() ? 's' : '') . '://' : '');
	}

	/**
	 * @return string
	 */
	public function getProtocol()
	{
		return $this->protocol;
	}

	/**
	 * @param string $protocol
	 */
	public function setProtocol($protocol)
	{
		$this->protocol = $protocol;
	}

	/**
	 * @return string
	 */
	public function getDomainString()
	{
		$domain = $this->getDomain();

		return $domain . ($domain ? '/' : '');
	}

	/**
	 * @return string
	 */
	public function getDomain()
	{
		return $this->domain;
	}

	/**
	 * @param string $domain
	 */
	public function setDomain($domain)
	{
		$this->domain = $domain;
	}

	/**
	 * @return string
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * @param string $page
	 */
	public function setPage($page)
	{
		$this->page = $page;
	}

	/**
	 * @return string
	 */
	public function getParamsString()
	{
		$params    = $this->getParams();
		$raw_query = [];
		foreach ($params as $key => $value) {
			if (is_array($value)) {
				$_q = [];
				foreach ($value as $v) {
					$_q[] = $key . '[]=' . $v;
				}
				$raw_query[] = implode('&', $_q);
			}
			else {
				$raw_query[] = $key . '=' . $value;
			}
		}

		return count($raw_query) ? '?' . implode('&', $raw_query) : '';
	}

	/**
	 * @return string
	 */
	public function getAnchorString()
	{
		$anchor = $this->getAnchor();

		return ($anchor ? '#' . $anchor : '');
	}

	/**
	 * @return string
	 */
	public function getAnchor()
	{
		return $this->anchor;
	}

	/**
	 * @param string $anchor
	 */
	public function setAnchor($anchor)
	{
		$this->anchor = $anchor;
	}
}