<?php

namespace app\components;

/**
 * Class ContentHelper
 * @package app\components
 */
class ContentHelper
{
    public static function home($resource)
    {
        $content = '';
        if ($stream = fopen($resource, 'r')) {
            $step = 1024;
            $offset = $step;
            while (!feof($stream)) {
                $content .= stream_get_contents($stream, -1, $offset);
                $offset += $step;
            }
            fclose($stream);
        }

        return $content;
    }

    /**
     * второй вариант
     * @param $resource
     * @return string
     */
    public static function secondOption($resource)
    {
        $content = '';
        $handle = fopen($resource, "r");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $content .= $buffer;
            }
            fclose($handle);
        }
        return $content;
    }

    /**
     * Первый вариант
     * @param $resource
     * @return false|string
     */
    public static function firstOption($resource)
    {
        $handle = fopen($resource, 'r');
        $file = fread($handle, filesize($resource));
        fclose($handle);
        return $file;
    }
}
