<?php
namespace Master\Model;

use Think\Model;

class SpendingModel extends Model
{
    /**
     * [addSpendings description]
     * @param [array] $date  键为病人姓名,值为日期的数组
     * @param [array] $info 人员信息
     */
    public function addSpendings($date, $info)
    {

        foreach ($date as $k => $day) {
            $date_pad[$k]['lastDay'] = date('d',strtotime("last day of $day"));
            if (!$date_pad[$k]['date'] = _checkDate($day,'Y-m')) {
                return false;
            }
        }

        //生成日期数组 格式Y-m-d 组合数据
        foreach ($info as $k => $v) {
            
            foreach($date_pad as $name => $day){
                if ($v['patient_name'] == $name) {
                    for ($i=1; $i<=$day['lastDay']; $i++) {
                        $v['spending_date'] = $day['date'].'-'.$i;
                        $res[]              = $v;
                    }
                }
            }
        }
        return $this->addAll($res); 
    }

    //根据电话获取消费信息
    public function getWithTel($tel, $date)
    {
        if (!$date = _checkDate($date)) {
            return false;
        }
        $tel                   = addslashes(trim($tel));
        $firstDay              = date('Y-m-d', strtotime("first day of $date"));
        $lastDay               = date('Y-m-d', strtotime("last day of $date"));
        $map['patient_tel']    = $tel;
        $map['spending_date']  = array('BETWEEN',array($firstDay,$lastDay));
        
        return $this->where($map)->select();
    }

}