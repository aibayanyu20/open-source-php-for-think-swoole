<?php
/**
 * @time 2020/11/9 9:36 下午
 * @author aibayanyu
 */

namespace app\middleware;


use app\Request;

class LogRecord
{
    /**
     * 处理请求
     *
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        // 判断是否需要记录日志,未配置nginx之前建议开启
        $log = env("log.log_debug",true);
        $startTime = date("Y-m-d H:i:s");
        $response = $next($request);
        if ($log){
            $date = date("Y-m-d H:i:s");
            $userAgent = $request->header("user-agent");
            $method = $request->method();
            $baseUrl = $request->baseUrl();
            $params = json_encode($request->param(),256);
            $txt = <<<Log
$startTime \t $date \t $userAgent \t $method \t $baseUrl \t $params \n
Log;
            $path = "runtime/swoole/log/".date("Ymd");
            $filePath = checkDir($path,false,true);
            $filename = "/".date("H").".log";
            if (file_exists($filePath.$filename)){
                // 已存在就追加写入
                file_put_contents($filePath.$filename,$txt,FILE_APPEND);
            }else{
                file_put_contents($filePath.$filename,$txt);
            }
        }
        return $response;
    }
}