<?php

//检查函数 返回格式化后的日期
function _checkDate($date, $format='Y-m-d')
{
    if (strtotime(date('Y-m-d',strtotime($date))) !== strtotime($date)) {
        return false;
    }
    return date($format,strtotime($date));
}


//生成柱状图
function makeBar($x = array(0), $y=array(0),$title='消费统计表')
{
        $path = VENDOR_PATH.'jpGraph/';
        require_once ($path.'jpgraph.php');
        require_once ($path.'jpgraph_bar.php');
        $data2y = $y;
        $graph = new \Graph(1000,500);
        $graph->SetScale("textlin");
        $graph->SetShadow();
        $graph->img->SetMargin(40,30,20,40);
        $b2plot = new \BarPlot($data2y);
        $b2plot->SetFillColor("blue");
        $b2plot->value->Show();
        $gbplot = new \AccBarPlot(array($b2plot));
        $graph->Add($b2plot);
        $graph->title->Set(iconv("UTF-8","GB2312//IGNORE",$title));
        $graph->xaxis->title->Set(iconv("UTF-8","GB2312//IGNORE","日期"));
        $graph->yaxis->title->Set(iconv("UTF-8","GB2312//IGNORE","消费额度"));
        $graph->xaxis->SetTickLabels($x); 
        $graph->title->SetFont(FF_SIMSUN,FS_BOLD);
        $graph->yaxis->title->SetFont(FF_SIMSUN,FS_BOLD);
        $graph->xaxis->title->SetFont(FF_SIMSUN,FS_BOLD);
        $graph->Stroke();
}


//检查验证码

function checkVerify($code, $id='')
{
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}