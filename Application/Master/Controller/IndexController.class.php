<?php
namespace Master\Controller;

// use Think\Controller;

class IndexController extends CommonController {

	public function index() 
	{
		$adminModel    = M('admin');
		$info 		   = $adminModel->find($_SESSION[C('SESSION_PREFIX')]['id']);

		if ($info['username'] == '' || $info['password'] == '') {
			header('location:/Master/Admin/update');
		}
		$unique = $this->_unique('select_form');
		$hospitalModel = M('hospital');
		$spendingModel = M('spending');
		$hospital_list = $hospitalModel->where(array('is_use' => '1'))->order('sort desc')->select();
		
		
		
		if(!$_SESSION['hospital_id'])    $_SESSION['hospital_id']   = $hospital_list[0]['id'];;
		if(!$_SESSION['hospital_name'])  $_SESSION['hospital_name'] = $hospital_list[0]['name'];
		$hospital_info = $hospitalModel->getById($_SESSION['hospital_id']);
	
		//获取日期 第一天和最后一天
		$firstDay = date('Y-m-d', strtotime('first day of this month'));
		$lastDay = date('Y-m-d', strtotime('last day of this month'));
		$map['hospital_id'] = array('EQ', $_SESSION['hospital_id']);
		$map['spending_date'] = array('BETWEEN', array($firstDay, $lastDay));
		$list = $spendingModel->where($map)->select();
		//拼装消费记录
		foreach ($list as $key => $info) {
			$res[$info['patient_tel']]['name'] = $info['patient_name'];
			$res[$info['patient_tel']]['cost'][$key] = $info;
			$res[$info['patient_tel']]['sum_money'] += $info['cost_money'];
		}
		

	
		$sum = $spendingModel->query('select sum(cost_money) from spending where hospital_id="' . $_SESSION['hospital_id'] . '"');
		$sum = $sum[0]['sum(cost_money)'];
		$leav = $sum * $hospital_info['percent'];
		$leav = $hospital_info['remaining_sum'] - $leav;

		//医生
		$doctorModel = M('doctor');
		$doctors = $doctorModel->where(array('hospital_id' => $_SESSION['hospital_id']))->select();
		//获取当月天数
		$lastDay = date('d', strtotime('last day of this month'));
		$days = array();
		$days = array_pad($days, $lastDay, '');
		$this->assign(array(
			'spending_list' => $res,
			'days' => $days,
			'menu' => self::$menu,
			'unique' => $unique,
			'hospital_list' => $hospital_list,
			'doctors' => $doctors, 
			
		));
		
			
		$this->display();
	}




	public function changeHospital()
	{
		$errMsg = '';
		if (!filter_has_var(INPUT_POST, 'hospital_id')||
			!filter_has_var(INPUT_POST, 'hospital_name')||
			!filter_has_var(INPUT_POST, 'unique')||
			$_POST['unique'] !== $_SESSION['select_form']
			){
			$this->error('非法操作');
			exit;
		}
		$filter =  array(
			'hospital_id'   => FILTER_SANITIZE_NUMBER_INT,
			'hospital_name' => FILTER_SANITIZE_STRING,
			);
		$model = M('hospital'); 
		$res   =  filter_var_array($_POST,$filter);
		if ($info = $model->find($res['hospital_id'])) {
			if($info['name'] == $res['hospital_name'] ){
				$_SESSION['hospital_id']   = $info['id'];
				$_SESSION['hospital_name'] = $info['name'];
				$this->ajaxReturn(array(
				'status' => 'ok'
				));
			}
		}

		$this->ajaxReturn(array(
			'status'  => 'error',
			'errMsg'  => '非法操作'
			));
	}


	public function searchPro()
	{
		if (!isset($_POST['unique']) || $_POST['unique'] != $_SESSION['select_form'] || !isset($_POST['date']) || !$date = _checkDate($_POST['date'])) {
			$this->error('非法操作');
			exit;
		}

		$firstDay   = date('Y-m-01', strtotime($date));
		$lastDay = date("Y-m-d", strtotime("last day of $date"));
		$where   = array();
		$sModel  = M('spending');
		$pModel  = M('patient');
		$cost_list = array();
		if (isset($_POST['doctor']) && $_POST['doctor'] !== '') {
			$where['doctor'] = filter_var($_POST['doctor'], FILTER_SANITIZE_STRING);
		}

		if (isset($_POST['min']) && $_POST['min'] !== '') {
			$where['cost']['min'] = filter_var($_POST['min'], FILTER_SANITIZE_STRING);
		}

		if (isset($_POST['max']) && $_POST['max'] !== '') {
			$where['cost']['max'] = filter_var($_POST['max'], FILTER_SANITIZE_STRING);
		}

		$spendings = $sModel->field('cost_money,patient_tel,patient_name')->where(array(
									'hospital_id' => $_SESSION['hospital_id'],
									'spending_date' => array('between', array($firstDay, $lastDay))
									))->select();

		if (isset($where['doctor'])) {
			$map['doctor_name'] = $where['doctor'];
		}
		$map['hospital_id'] = $_SESSION['hospital_id'];
		$patients = $pModel->field('tel')->where($map)->select();	

		//数据拼装
		foreach ($spendings as $spending) {
			$res[$spending['patient_tel']]['cost_sum']  += $spending['cost_money'] ;
			$res[$spending['patient_tel']]['name']  = $spending['patient_name'] ;
			$res[$spending['patient_tel']]['cost_list'][] = $spending;
		}

		foreach ($patients as $patient) {
			if (isset($res[$patient['tel']])) {
				if (isset($where['cost'])) {

					if ($where['cost']['min'] <= $res[$patient['tel']]['cost_sum'] && $res[$patient['tel']]['cost_sum'] <= $where['cost']['max']) {
						$cost_list[$patient['tel']] = $res[$patient['tel']];
					}

				} else {

					$cost_list[$patient['tel']] = $res[$patient['tel']];

				}
			}
		}

		if ($where) {
			$this->ajaxReturn(array(
				'info' => $cost_list,
			));
		}else{
			$this->ajaxReturn(array(
				'info' => '',
			));
		}


		
	}

}