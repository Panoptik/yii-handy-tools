<?php

/**
 * Created by PhpStorm.
 * User: agolovko
 * Date: 25.11.15
 * Time: 10:39
 */

/**
 * Class Debug
 * used for show debug info in different interfaces such a browser console (now implemented only this)
 * todo add debug info into CLI interface
 */
class Debug
{
    const TRACE_LEVEL = 5;

    /**
     * alias to browserConsole method
     */
    public static function bc()
    {
        return call_user_func_array(__CLASS__ . '::browserConsole', func_get_args());
    }

    /**
     * debug info about specified arguments
     * and output it into browser console
     */
    public static function browserConsole()
    {
        // skip if from CLI called
        if(!isset($_SERVER['REMOTE_ADDR']))
            return;
        static $cnt = 0;
        $cnt++;
        $args = func_get_args();

        $script = '';
        $traceInfo = self::getTraceInfo();

        foreach($traceInfo as $info) {
            $script .=
                'console.log(' . PHP_EOL .
                '   "%c ' . $info . '",' . PHP_EOL .
                '   "color: #00aa00; font-weight:bold;"' . PHP_EOL .
                ');' . PHP_EOL;
        }
        $script .=
            'console.log(' . PHP_EOL .
            '   "%c " + JSON.stringify('. CJSON::encode($args) .', null, 2),' . PHP_EOL .
            '   "color: #2222aa; font-weight:bold;"' . PHP_EOL .
            ');' . PHP_EOL;
        Yii::app()->getClientScript()->registerJs($script, CClientScript::POS_END, 'jsDebug_' . $cnt);
    }

    /**
     * get stack trace files
     * @return array
     */
    private static function getTraceInfo()
    {
        $trace = debug_backtrace();
        $traceInfo = [];
        for($i = self::TRACE_LEVEL + 3; $i >= 0; $i--) {
            // skip self Debug trace info
            if($i < 3) continue;
            // if stack size is too small skip this step
            if (!isset($trace[$i])) continue;
            // sometimes debug_backtrace not return file or line keys
            if (!isset($trace[$i]['file']) || !isset($trace[$i]['line'])) {
                continue;
            }
            // remove absolute path from filePath
            $filePath = str_replace(Yii::getPathOfAlias('webroot'), '', $trace[$i]['file']);
            $filePath = addslashes($filePath);
            $traceInfo[] = $filePath . ': ' . $trace[$i]['line'];
        }
        return $traceInfo;
    }

    /**
     * get stack trace as string
     * @return string
     */
    public static function getTraceAsString()
    {
        $traceInfo = self::getTraceInfo();
        return implode(PHP_EOL, $traceInfo);
    }
}
