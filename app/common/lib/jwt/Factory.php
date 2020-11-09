<?php
/**
 * @time 2020/11/9 8:24 下午
 * @author aibayanyu
 */

namespace app\common\lib\jwt;


use Firebase\JWT\JWT;
use think\Exception;

class Factory
{
    // 签发者
    private $iss = "aibayanyu-php";
    // 接收者
    private $aud = "aibayanyu-vue";
    // 签发时间
    private $iat;
    // 生效时间
    private $nbf;
    // 过期时间
    private $exp;
    // 自定义内容区域
    private $data;
    // 定义secret
    private $secret;
    // 签发方式
    private $signType = ['HS256'];
    /**
     * 初始化数据信息
     * Factory constructor.
     * @param $data
     * @param int $weekly
     * @param array $config
     * @throws Exception
     */
    public function __construct( $weekly = 0, $config = [])
    {
        // 定义数据
        $this->iat = time();
        $this->nbf = time() + $weekly;
        $time = env("jwt.expire","1d");
        $this->exp = $this->getTime($time);
        $this->secret = env("jwt.secret","aibayanyu");
        if (array_key_exists("iss",$config)) $this->iss = $config['iss'];
        if (array_key_exists("aud",$config)) $this->aud = $config['aud'];
    }

    /**
     * 获取过期时间,返回的是秒
     * @time 2020/11/9 9:16 下午
     * @author aibayanyu
     */
    public function getExpire(){
        return $this->exp - time();
    }

    /**
     * 生成对应的时间戳的格式
     * @time 2020/11/9 8:57 下午
     * @param $time
     * @return false|int|string
     * @throws Exception
     * @author aibayanyu
     */
    private function getTime($time){
        // 判断是否为数字
        if (is_numeric($time)) return time() + $time;
        // 判断当前是天，小时，还是周
        $isDay = '/(.*?)d/';
        $res = preg_match_all($isDay,$time,$result);
        if ($res){
            return strtotime("+{$result[1][0]} days");
        }
        $isHours = '/(.*?)h/';
        $res = preg_match_all($isHours,$time,$result);
        if ($res){
            return strtotime("+{$result[1][0]} hours");
        }
        $isHours = '/(.*?)w/';
        $res = preg_match_all($isHours,$time,$result);
        if ($res){
            return strtotime("+{$result[1][0]} week");
        }
        throw new Exception("时间参数传入有误");
    }

    /**
     * 生成payload
     * @time 2020/11/9 8:59 下午
     * @return array
     * @author aibayanyu
     */
    private function getPayload(){
        return [
            'iss' => $this->iss, //签发者 可选
            'aud' => $this->aud, //接收该JWT的一方，可选
            'iat' => $this->iat, //签发时间
            'nbf' => $this->nbf , //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
            'exp' => $this->exp, //过期时间,这里设置2个小时
            'data' =>$this->data
        ];
    }

    public function setData($data){
        $this->data = $data;
    }

    public function createToken(){
        if (empty($this->data)) throw new Exception("定义的数据不能为空");
        return JWT::encode($this->getPayload(),$this->secret);
    }

    public function getData($token){
        return JWT::decode($token,$this->secret,$this->signType);
    }
}