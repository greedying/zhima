<?php

/*
 * This file is part of the greedying/zhima.
 *
 * (c) greedying <greeedying@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Greedying\Zhima\Ivs;

use Greedying\Zhima\Core\AbstractBase;
use Pimple\Container;

class Ivs extends AbstractBase
{

	//业务参数
	public $biz_attributes = [
		'product_code'		=> 'w1010100000000000103',
		'transaction_id'	=> '',
		'open_id'			=> '',
		'cert_no'			=> '',
		'cert_type'			=> '',
		'name'				=> '',
		'mobile'			=> '',
		'email'				=> '',
		'bank_card'			=> '',
		'address'			=> '',
		'ip'				=> '',
		'mac'				=> '',
		'wifimac'			=> '',
		'imei'				=> '',
		'imsi'				=> '',
	];


	public function __construct(Container $application)
	{
		parent::__construct($application);
		$this->method = 'zhima.credit.ivs.detail.get';
		$this->channel = 'api';
	}

	//只获取评分
	public function score(array $info = []) {
		$result = $this->query($info);
		return $result['ivs_score'];
	}

	//获取结果，包括biz_no等信息
	public function query(array $info = []) {

		foreach($info as $k => $v) {
			if (isset($this->biz_attributes[$k])) {
				$this->$k = $v;
			}
		}

		$result = $this->post();
		return $result;
	}

}
