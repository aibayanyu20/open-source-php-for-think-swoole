<?php
// 应用公共文件
use app\common\exception\ApiException;

// 成功消息提示
function apiSuccess($msg, $data=[], $code=200, $state=200){
    // 成功的回传消息类型
    $arr = ['msg'=>$msg,'code'=>$code];
    if (!empty($data)){
        $arr['data'] = $data;
    }
    return json($arr)->code($state);
}

// 成功并返回带数据的格式
function apiDataSuccess($data,$msg='ok'){
    return apiSuccess($msg,$data);
}

// 错误消息提示
function apiError($msg,$code=400,$state=400){
    throw new ApiException($msg,$code,$state);
}

/**
 * 检查一个目录是否存在，不存在则创建这个目录
 * @time 2020/11/9 8:17 上午
 * @param $path string 路径信息
 * @param bool $publicPath 是否为public下面的目录路径
 * @param false $completePath 是否返回完整的目录路径
 * @return string
 * @author aibayanyu
 */
function checkDir(string $path, $publicPath=true, $completePath=false){
    // 判断当前是否从public中获取路径
    if ($publicPath){
        $myPath = public_path().$path;
    }else{
        $myPath = root_path().$path;
    }
    if (!is_dir($myPath)){
        // 当前的文件夹不存在即创建当前的文件夹
        mkdir($myPath,0777,true);
    }
    if ($completePath){
        return $myPath;
    }
    return $path;
}

/**
　　*
　　*
　　*/
/**
 * 下划线转驼峰
 * 思路:
 * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
 * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
 * @time 2020/11/20 7:48 上午
 * @param $uncamelized_words
 * @param string $separator
 * @return string
 * @author aibayanyu
 */
function camelize($uncamelized_words,$separator='_')
{
    $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
    return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
}

/**
 * 驼峰命名转下划线命名
 * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
 * @time 2020/11/20 7:48 上午
 * @param $camelCaps
 * @param string $separator
 * @return string
 * @author aibayanyu
 */
function uncamelize($camelCaps,$separator='_')
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}