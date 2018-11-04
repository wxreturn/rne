<?php
/**********************************************************                                                                  
 *  
 *  FileName:  ListTag.php
 *
 *  Author:  wxreturn
 *
 *  Version:  1.0
 *
 *  DateTime:  2018年11月4日 - 下午8:38:22
 *
 *  Description:  列表
 *
 **********************************************************/

namespace rne;

require_once '.\TagParse.php';

use rne\TagParse;

class ListTag extends TagParse
{
    public function dis()
    {
        echo $this->sqlCommand;
    }
    
    public function parse()
    {
        
    }
}

$lt = new ListTag();
$lt->parse();

