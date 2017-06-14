<?php
namespace Home\Controller;

// use Think\Controller;

class IndexController extends CommonController {

	public function index() 
	{
		$unique = $this->_unique('select_form');
		$prefix = C('SESSION_PREFIX');
		$hospital_id = $_SESSION[$prefix]['hospital_id'];
		$hospitalModel = M('hospital');
		$spendingModel = M('spending');
		$proportionModel = M('proportion');
		$rechangeModel = M('rechange');
		$rechange_sum = $rechangeModel->field('sum(money) as sum')->getByHospitalId($hospital_id);
		$proportion = $proportionModel->query('select p.percent,p.is_use,p.id,sum(s.cost_money) as cost_sum from proportion p join spending s on p.id=s.percentId where p.hospital_id='.$hospital_id.' group by p.id;');
		$hospital_info = $hospitalModel->getById($hospital_id);
		foreach ($proportion as $item) {
			if($item['is_use'] == '1'){
				$hospital_info['percent'] = $item['percent'];
			}
			$sum += ($item['percent'] * $item['cost_sum']);
		}

		
	
		$_SESSION[$prefix]['hospital_name'] = $hospital_info['name'];
		//获取日期 第一天和最后一天
		$firstDay = date('Y-m-d', strtotime('first day of this month'));
		$lastDay = date('Y-m-d', strtotime('last day of this month'));
		$map['hospital_id'] = array('EQ', $hospital_id);
		$map['spending_date'] = array('BETWEEN', array($firstDay, $lastDay));
		$list = $spendingModel->where($map)->select();

		//拼装消费记录
		foreach ($list as $key => $info) {
			$res[$info['patient_tel']]['name'] = $info['patient_name'];
			$res[$info['patient_tel']]['cost'][$key] = $info;
			$res[$info['patient_tel']]['sum_money'] += $info['cost_money'];
		}
		$leav = $rechange_sum['sum']-$sum;
		//获取当月天数
		$lastDay = date('d', strtotime('last day of this month'));
		$days = array();
		$days = array_pad($days, $lastDay, '');
	
	
	
		$this->assign(array(
			'hospital_info' => $hospital_info,
			'leav' => $leav,
			'spending_list' => $res,
			'days' => $days,
			'menu' => self::$menu,
			'unique' => $unique,
			'part_id' => $_SESSION[$prefix]['aprt_id']
		));
		$this->display();
	}

}