<?php
/**
 * Created by PhpStorm.
 * User: dengpeng.zdp
 * Date: 2015/9/28
 * Time: 19:25
 */
namespace Greedying\Zhima\Support;

class WebUtil
{

    /**
     * 将传入的参数组织成key1=value1&key2=value2形式的字符串
     * @param $params
     * @return string
     */
    public static function buildQueryWithoutEncode($params)
    {
        return WebUtil::buildQuery($params, false);
    }

    public static function buildQueryWithEncode($params)
    {
        return WebUtil::buildQuery($params, true);
    }

    public static function buildQuery($params, $needEncode)
    {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === WebUtil::checkEmpty($v)) {
                if ($needEncode) {
                    $v = urlencode($v);
                }

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset($k, $v);
        return $stringToBeSigned;
    }

    public static function trim($params)
    {
        return array_filter($params, function ($k, $v) {
            return $v;
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     *  校验$value是否非空
     *  if not set ,return true;
     *  if is null , return true;
     * @param $value
     * @return bool
     */
    public static function checkEmpty($value)
    {
        if (!isset($value)) {
            return true;
        }
        if ($value === null) {
            return true;
        }
        if (trim($value) === "") {
            return true;
        }

        return false;
    }
}
