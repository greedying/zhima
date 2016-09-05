<?php

/*
 * This file is part of the greedying/zhima.
 *
 * (c) greedying <greeedying@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Greedying\Zhima\Watchlist;

use Greedying\Zhima\Core\AbstractBase;
use Pimple\Container;

class Watchlist extends AbstractBase
{

	//业务参数
	public $biz_attributes = [
		'transaction_id' => '',
		'product_code'	 => 'w1010100100000000022',
		'open_id'		 => '',
	];


	public function __construct(Container $application)
	{
		parent::__construct($application);
		$this->method = 'zhima.credit.watchlistii.get';
	}

	//获取结果，包括biz_no等信息
	public function query($open_id = '' , $transaction_id = '') {
		$this->channel = 'api'; //似乎每一个都可以，有点晕

		if($open_id) {
			$this->open_id = $open_id;
		}

		if ($transaction_id) {
			$this->transaction_id = $transaction_id;
		}

		$result = $this->post();

		return $result;
	}

}
