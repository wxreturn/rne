<?php
/**********************************************************                                                                  
 *  
 *  FileName:  index.php
 *
 *  Author:  wxreturn
 *
 *  Version:  1.0
 *
 *  DateTime:  2018年11月4日 - 下午4:24:15
 *
 *  Description:  rne测试入口文件
 *
 **********************************************************/
namespace rne;

require_once '.\return_engine.php';


use rne\ReturnEngine;

$re = new ReturnEngine('.\template.html');
echo $re->parseHTML();






