<h1>芝麻信用PHP SDK</h1>


## Requirement

1. PHP >= 5.5.9
2. **[composer](https://getcomposer.org/)**
3. openssl 拓展

> SDK 对所使用的框架并无特别要求

## Installation

可以使用composer引用

```bash
"require": {
	"greedying/zhima" : "dev-master"
},
```


## Usage

基本使用示例:

0. 配置与初始化

```php
<?php
use Greedying\Zhima\Foundation\Application;

$options = [
	'app_id'    => '123456',
	'scene'     => 'yourscene',
	'private_key_file' => "/dir/to/your/rsa_private_key.pem",
	'zhima_public_key_file' => "/dir/to/your/zhima_public_key.pem",
];

$zhima = new Application($options);

$open_id = '123456';
transaction_id = '234567';

?>

````


1.授权

```php
<?php

$auth = $zhima->auth;
$auth->identity_type = '2';
$auth->identity_param = json_encode([
	'certNo'    => '身份证号',
	'certType'  => 'IDENTITY_CARD',
	'name'      => '名字',
]);

$auth->state = 'your state string'; //自定义字符串

$url = $auth->getH5Url();//H5授权链接
//$url = $auth->getPcUrl();//Pc授权链接

//其他暂时没有实现

$this->redirect($url); //访问授权链接，进入芝麻页面

//回调页面，传入callback函数即可，notify为已经解密的数据， successful为是否授权成功
$auth->handleNotify(callback function ($notify, $successful) {
		//your code
});

//查询是否授权
$auth->identity_type = '2';
$auth->identity_param = json_encode([
	'certNo'    => '身份证号',
	'certType'  => 'IDENTITY_CARD',
	'name'      => '名字',
]);

$auth->state = 'your state string'; //自定义字符串
$result = $auth->query(); //true or false
?>
`````

2.查询芝麻分

```php
<?php

//只查询分数
$score = $zhima->score->score($open_id, $transaction_id); 

//查询分数信息
$score = $zhima->score->query($open_id, $transaction_id); 

?>
`````

3.查询行业关注名单

```php
<?php

$score = $zhima->watchlist->query($open_id, $transaction_id); 


?>
`````

4.查询反欺诈信息

```php
<?php
$info = [
	'transaction_id'    => '',
	'open_id'           => '',
	'cert_no'           => '',
	'cert_type'         => '100',
	'name'              => '',
	'mobile'            => '',
	'email'             => '',
	'bank_card'         => '',
	'address'           => '',
	'ip'                => '',
	'mac'               => '',
	'wifimac'           => '',
	'imei'              => '',
	'imsi'              => '',
];


//只查询得分
$score = $zhima->ivs->score($open_id, $transaction_id); 

//查询相信信息
$score = $zhima->isv->query($open_id, $transaction_id); 


?>
`````


## 说明

1. 时间问题，只实现了部分我能用到的接口，其他接口如需要，欢迎pr.
2. 仍然是时间问题，目前没有实现日志、异常处理、测试代码，同样欢迎pr.
3. 使用本SDK前，你应该尽量看懂芝麻的技术文档，本文不是一个从零开始的教程
4. 恩，也别太信芝麻的文档；里面的错误比芝麻还密集...


## License
MIT
