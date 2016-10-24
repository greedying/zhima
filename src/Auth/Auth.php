<?php

/*
 * This file is part of the greedying/zhima.
 *
 * (c) greedying <greeedying@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Greedying\Zhima\Auth;

use Greedying\Zhima\Core\AbstractBase;
use Pimple\Container;

class Auth extends AbstractBase
{

	//业务参数
	public $biz_attributes = [
		'identity_type' => '',
		'identity_param' => '',
		'biz_params'	=> '',
	];

    public function __construct(Container $application)
    {
		parent::__construct($application);
    }

	public function getH5Url($params = null) {
		$this->method = 'zhima.auth.info.authorize';
		$this->channel = 'app';
		$this->auth_code = 'M_H5';
		return $this->getUrl();
	}

	public function getPcUrl($params = null) {
		$this->method = 'zhima.auth.info.authorize';
		$this->channel = 'apppc';
		$this->auth_code = 'M_APPPC_CERT';
		return $this->getUrl();
	}

	//获取sms方式的url，未调通。。。。。
	public function getSmsUrl() {
		$this->method = 'zhima.auth.engine.smsauth';
		$this->channel = 'api';
		$this->auth_code = 'M_DEFAULT';
		return $this->getUrl();
	}

	//调用接口，发送短信
	public function smsAuth() {
	}

	/**
	 * 查询是否授权
	 * return bool true | false
	**/
	public function query() {
		$this->method = 'zhima.auth.info.authquery';
		$data = $this->get();
		return $data['authorized'];
	}

	/**
	 * Return Notify instance.
	 *
	 * @return \Greedying\Auth\Notify
	 *                     */
	public function getNotify()
	{
		return new Notify($this->application->encryptor);
	}

	public function handleNotify(callable $callback)
	{
		$notify = $this->getNotify();

		if (!$notify->isValid()) {
			throw new \Exception('Invalid request');
		} 

		$notify = $notify->getNotify();

		$successful = $notify->get('success') === 'true';

		$handleResult = call_user_func_array($callback, [$notify, $successful]);
	}

	function getCertNo($certNo) {
		$identity_param = $this->identity_param ? json_decode($this->identity_param, true) : [];
		return isset($identity_param['certNo']) ? $identity_param['certNo'] : '';
	}

	function setCertNo($certNo) {
		$identity_param = $this->identity_param ? json_decode($this->identity_param, true) : [];
		$identity_param['certNo'] = $certNo;
		$this->identity_param = json_encode($identity_param);
		return $this;
	}

	function getName($name) {
		$identity_param = $this->identity_param ? json_decode($this->identity_param, true) : [];
		return isset($identity_param['name']) ? $identity_param['name'] : '';
	}

	function setName($name) {
		$identity_param = $this->identity_param ? json_decode($this->identity_param, true) : [];
		$identity_param['name'] = $name;
		$this->identity_param = json_encode($identity_param);
		return $this;
	}

	function getMobileNo($mobileNo) {
		$identity_param = $this->identity_param ? json_decode($this->identity_param, true) : [];
		return isset($identity_param['mobileNo']) ? $identity_param['mobileNo'] : '';
	}

	function setMobileNo($mobileNo) {
		$identity_param = $this->identity_param ? json_decode($this->identity_param, true) : [];
		$identity_param['mobileNo'] = $mobileNo;
		$this->identity_param = json_encode($identity_param);
		return $this;
	}

	function getCertType($certType) {
		$identity_param = $this->identity_param ? json_decode($this->identity_param, true) : [];
		return isset($identity_param['certType']) ? $identity_param['certType'] : '';
	}

	function setCertType($certType) {
		$identity_param = $this->identity_param ? json_decode($this->identity_param, true) : [];
		$identity_param['certType'] = $certType;
		$this->identity_param = json_encode($identity_param);
		return $this;
	}

	function getAuth_code($auth_code) {
		$biz_params = $this->biz_params ? json_decode($this->biz_params, true) : [];
		return isset($biz_params['auth_code']) ? $biz_params['auth_code'] : '';
	}

	function getOpenId($openid) {
		$identity_param = $this->identity_param ? json_decode($this->identity_param, true) : [];
		return isset($identity_param['openId']) ? $identity_param['openId'] : '';
	}

	function setOpenId($openid) {
		$identity_param = $this->identity_param ? json_decode($this->identity_param, true) : [];
		$identity_param['openId'] = $openId;
		$this->identity_param = json_encode($identity_param);
		return $this;
	}

	function setAuth_code($auth_code) {
		$biz_params = $this->biz_params ? json_decode($this->biz_params, true) : [];
		$biz_params['auth_code'] = $auth_code;
		$this->biz_params = json_encode($biz_params);
		return $this;
	}

	function getState($state) {
		$biz_params = $this->biz_params ? json_decode($this->biz_params, true) : [];
		return isset($biz_params['state']) ? $biz_params['state'] : '';
	}

	function setState($state) {
		$biz_params = $this->biz_params ? json_decode($this->biz_params, true) : [];
		$biz_params['state'] = $state;
		$this->biz_params = json_encode($biz_params);
		return $this;
	}

}
