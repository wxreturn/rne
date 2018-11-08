<?php
/**********************************************************                                                                  
 *  
 *  FileName:  common.php
 *
 *  Author:  wxreturn
 *
 *  Version:  1.0
 *
 *  DateTime:  2018年11月6日 - 上午1:08:41
 *
 *  Description:  公共函数文件
 *
 **********************************************************/

namespace rne;


/**
 * 获取汉字拼音拼写
 * 
 * @param string $chinese 需要返回拼音的字符串
 * @param array $dict 汉字拼音对照表数组
 * @param bool $all 是否全拼
 * @return string 拼音
 */
function getSepll($chinese, $dict, $all=true)
{
    $size = mb_strlen($chinese);
    $res = '';
    for($i=0; $i<$size; $i++)
    {
        $key = mb_substr($chinese, $i, 1);
        if(array_key_exists($key, $dict))
        {
            if($all == true)
            {
                $res .= $dict[$key];
            }
            else 
            {
                $res .= $dict[$key]{0};
            }
            
        }
        else
        {
            $res .= $key;
        }
    }
    return $res;
}