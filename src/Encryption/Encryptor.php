<?php

/*
 * This file is part of the greedying/zhima.
 *
 * (c) greedying <greedying@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Greedying\Zhima\Encryption;

use Greedying\Zhima\Core\Exceptions\InvalidConfigException;
use Greedying\Zhima\Core\Exceptions\RuntimeException;
use Greedying\Zhima\Support\XML;
use Pimple\Container;
use Exception as BaseException;

/**
 * Class Encryptor.
 */
class Encryptor
{
    /**
     * private key.
     * @var string
     */
    protected $private_key;

    /**
     * Zhima Public key
     *
     * @var string
     */
    protected $zhima_public_key;


    /**
     * Constructor.
     *
     * @param string $appId
     * @param string $token
     * @param string $AESKey
     *
     * @throws RuntimeException
     */
    public function __construct(Container $application)
    {
        if (!extension_loaded('openssl')) {
            throw new RuntimeException("The ext 'openssl' is required.");
        }
        $this->private_key = file_get_contents($application['config']['private_key_file']);
        $this->zhima_public_key = file_get_contents($application['config']['zhima_public_key_file']);
    }

    /**
     * 签名，使用用于私钥签名
     * @param $data 要签名的数据
     * @param $data 私钥，默认是配置文件中的私钥
     * @return string 签名
     */
    public function sign($data, $private_key = null)
    {
        $private_key = $private_key ?: $this->private_key;
        $res = openssl_get_privatekey($private_key);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * 验签
     * @param $data 用来加签的数据
     * @param $sign 加签后的结果
     * @param $public_key 公钥，默认是配置文件中的芝麻信用公钥
     * @return bool 验签是否成功
     */
    public function verify($data, $sign, $public_key = null)
    {
        $public_key = $public_key ?: $this->zhima_public_key;

        //转换为openssl格式密钥
        $res = openssl_get_publickey($public_key);

        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);

        //释放资源
        openssl_free_key($res);

        return $result;
    }
    

    /**
     * rsa加密
     * @param $data 要加密的数据
     * @param $pubKeyFilePath 公钥文件路径
     * @return string 加密后的密文
     */
    public function rsaEncrypt($data, $public_key = null)
    {
        $public_key = $public_key ?: $this->zhima_public_key;

        //转换为openssl格式密钥
        $res = openssl_get_publickey($public_key);

        $maxlength = $this->getMaxEncryptBlockSize($res);

        $output='';
        while ($data) {
            $input= substr($data, 0, $maxlength);
            $data=substr($data, $maxlength);
            openssl_public_encrypt($input, $encrypted, $public_key);
            $output.= $encrypted;
        }
        $encryptedData =  base64_encode($output);
        return $encryptedData;
    }

    /**
     * 解密
     * @param $data 要解密的数据
     * @return string 解密后的明文
     */
    public function rsaDecrypt($data, $private_key = null)
    {
        $private_key = $private_key ?: $this->private_key;

        //转换为openssl格式密钥
        $res = openssl_get_privatekey($private_key);
        $data = base64_decode($data);
        $maxlength = $this->getMaxDecryptBlockSize($res);
        $output='';
        while ($data) {
            $input = substr($data, 0, $maxlength);
            $data = substr($data, $maxlength);
            openssl_private_decrypt($input, $out, $res);
            $output .= $out;
        }
        return $output;
    }

    /**
     *根据key的内容获取最大加密lock的大小，兼容各种长度的rsa keysize（比如1024,2048）
     * 对于1024长度的RSA Key，返回值为117
     * @param $keyRes
     * @return float
     */
    public static function getMaxEncryptBlockSize($keyRes)
    {
        $keyDetail = openssl_pkey_get_details($keyRes);
        $modulusSize = $keyDetail['bits'];
        return $modulusSize/8 - 11;
    }

    /**
     * 根据key的内容获取最大解密block的大小，兼容各种长度的rsa keysize（比如1024,2048）
     * 对于1024长度的RSA Key，返回值为128
     * @param $keyRes
     * @return float
     */
    public static function getMaxDecryptBlockSize($keyRes)
    {
        $keyDetail = openssl_pkey_get_details($keyRes);
        $modulusSize = $keyDetail['bits'];
        return $modulusSize/8;
    }
}
