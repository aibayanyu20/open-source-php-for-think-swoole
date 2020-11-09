<?php
/**
 * @time 2020/11/9 7:52 上午
 * @author aibayanyu
 */

namespace app\common\exception;



class ApiException extends \Exception
{

    private $msg;

    private $errorCode;

    private $state;

    /**
     * ApiException constructor.
     * @param $msg string 错误信息
     * @param $code integer 错误代码
     * @param int $state 错误状态
     */
    public function __construct(string $msg, int $code, $state = 400)
    {
        $this->msg = $msg;
        $this->errorCode = $code;
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return mixed
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @return int|mixed
     */
    public function getState()
    {
        return $this->state;
    }
}