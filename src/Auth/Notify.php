<?php

/*
 * This file is part of the greedying/zhima.
 *
 * (c) greedying <greedying@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Greedying\Zhima\Auth;

use Greedying\Zhima\Encryption\Encryptor;
use Greedying\Zhima\Support\Collection;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Notify.
 */
class Notify
{
    /**
     * Encryptor instance.
     *
	 * @var Greedying\Zhima\Encryption\Encryptor;
     */
    protected $encryptor;

    /**
     * Request instance.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * Payment notify (extract from XML).
     *
     * @var Collection
     */
    protected $notify;

	//GET参数中的params参数
	protected $params = '';

	//GET参数中的sign参数
	protected $sign = '';

	//解密后的结果字符串
	protected $result = '';

    /**
     * Constructor.
     *
     * @param Merchant $merchant
     * @param Request  $request
     */
    public function __construct(Encryptor $encryptor, Request $request = null)
    {
        $this->encryptor = $encryptor;
        $this->request = $request ?: Request::createFromGlobals();
    }

	public function getParams() {
		if ($this->params == '') {
			$this->params = $this->request->get('params');
		}
		return $this->params;
	}

	public function getSign() {
		if ($this->sign == '') {
			$this->sign = $this->request->get('sign');
		}
		return $this->sign;
	}

	public function getResult() {
		if ($this->result == '') {
			$this->result = $this->encryptor->rsaDecrypt($this->getParams());
		}
		return $this->result;
	}

    /**
     * Validate the request params.
     *
     * @return bool
     */
    public function isValid()
    {
		return $this->encryptor->verify($this->getResult(), $this->getSign());
    }

    /**
     * Return the notify body from request.
     *
     * @return \EasyWeChat\Support\Collection
     *
     * @throws \EasyWeChat\Core\Exceptions\FaultException
     */
    public function getNotify()
    {
        if (!empty($this->notify)) {
            return $this->notify;
        }

		parse_str($this->getResult(), $data);

        return $this->notify = new Collection($data);
    }
}
