<?php
namespace Master\Controller;

class SpendingController extends CommonController {

	static private $color = array('#82d0ce', '#6681ad', '#de8396', '#d4c398', '#61c9cf', '#7982bd');
	
	public function save() {
		$errMsg = '';

		if (!IS_AJAX || !isset($_POST['unique']) || !isset($_POST['id']) || $_SESSION['alter_form'] !== $_POST['unique']) {
			$errMsg = '非法操作';
			exit;
		}

		//过滤
		foreach ($_POST as $k => &$val) {
			if ($k == 'id' || $k == 'cost_money') {
				$val = (int) $val;
				continue;
			}
			$val = addslashes($val);
		}

		$model = M('spending');
		$id = $_POST['id'];

		if (!$info = $model->find($id)) {
			$errMsg = '无效ID';
		}


		if ($errMsg) {
			$this->ajaxReturn(array(
				'status' => 'error',
				'errMsg' => $errMsg,
			));
		} else {

			if ($model->data($_POST)->save()) {
				$this->ajaxReturn(array(
					'status' => 'ok',
				));
			} else {
				$this->ajaxReturn(array(
					'status' => 'error',
					'errMsg' => '修改失败',
				));
			}

		}
	}

	//AJAX查询调用
	public function select() 
	{
		$errMsg = '';
		if (!IS_AJAX || !isset($_POST['unique']) || $_POST['unique'] !== $_SESSION['select_form']) {
			$errMsg = '非法操作';
		}
		if (!isset($_POST['date']) || !$date = _checkDate($_POST['date'])) {
			$errMsg = '时间日期有误';
		}

		if ($errMsg) {
			$this->ajaxReturn(array(
				'status' => 'error',
				'errMsg' => $errMsg,
			));
		} else {
			$model = M('spending');
			$prefix = C('SESSION_PREFIX');
			$firstDay = date('Y-m-d', strtotime("first day of $date"));
			$lastDay = date('Y-m-d', strtotime("last day of $date"));
			$dayNum = date('d', strtotime("last day of $date"));
			$map['hospital_id'] = $_SESSION['hospital_id'];
			$map['spending_date'] = array('BETWEEN', array($firstDay, $lastDay));
			$infos = $model->where($map)->select();
			foreach ($infos as $key => $info) {
				$res[$info['patient_tel']]['name'] = $info['patient_name'];
				$res[$info['patient_tel']]['cost'][$key] = $info;
				$res[$info['patient_tel']]['sum_money'] += $info['cost_money'];
			}
			$this->ajaxReturn(array(
				'status' => 'ok',
				'info' => $res,
				'days' => $dayNum,
			));
		}
	}

	//生成次月消费表
	public function addNext()
	{
		
		$patientModel  = M('patient');
		$spendingModel = M('spending');
		$pModel        = M('proportion');

		//比例信息
		$map['hospital_id'] = $_SESSION['hospital_id'];
		$map['is_use']      = '1';
		$percent_info       = $pModel->field('id')->where($map)->find();

		//检测是否存在
		$firstDay      = date('Y-m-d', strtotime('first day of next month'));
		$lastDay       = date('Y-m-d', strtotime('last day of next month'));

		$map['hospital_id']   = $_SESSION['hospital_id'];
 		$map['spending_date'] = array('between', array($firstDay, $lastDay));
		if ($res = $spendingModel->where($map)->select()) {
			$this->error('已存在消费表,请勿重复创建');
			exit;	
		} 
	
		$yearMonth     = date('Y-m', strtotime('last day of next month'));
		$nextDays      = date('d', strtotime('last day of next month'));
		$patients      = $patientModel->where(array('status'=>'0','hospital_id'=>$_SESSION['hospital_id']))->select();

		if (empty($patients)) {
			$this->error('所有病人已出院,无需添加');
			exit;
		}
		for ($i=1; $i<=$nextDays; $i++) {
			$days[] = $yearMonth.'-'.$i;
		}

		foreach ($patients as $k => $patient) {
			unset($patient['id']);
			$patient['percentId']    = $percent_info['id'];
			$patient['patient_tel']  = $patient['tel'];
			$patient['patient_name'] = $patient['name'];
			foreach ($days as $day) {
				$patient['spending_date'] = $day;
				$res[] = $patient;
			}
		}
		

		if( $spendingModel->addAll($res)){
			$this->success('添加成功','/Master/Index/index');
		}

	}

	//生成当月消费
	public function addCurrent()
	{
		$patientModel  = M('patient');
		$spendingModel = M('spending');
		$pModel        = M('proportion');

		//比例信息
		$map['hospital_id'] = $_SESSION['hospital_id'];
		$map['is_use']      = '1';
		$percent_info       = $pModel->field('id')->where($map)->find();

		//检测是否存在
		$firstDay      = date('Y-m-d', strtotime('first day of this month'));
		$lastDay       = date('Y-m-d', strtotime('last day of this month'));
		$map['hospital_id']   = $_SESSION['hospital_id'];
		$map['spending_date'] = array('between', array($firstDay, $lastDay));
		if ($res = $spendingModel->where($map)->select()) {
			$this->error('已存在消费表,请勿重复创建');
			exit;	
		}

		$yearMonth     = date('Y-m', strtotime('last day of this month'));
		$currentDays   = date('d', strtotime('last day of this month'));
		$patients      = $patientModel->where(array('status'=>'0','hospital_id'=>$_SESSION['hospital_id']))->select();

		if (empty($patients)) {
			$this->error('所有病人已出院,无需添加');
			exit;
		}

		for ($i=1; $i<=$currentDays; $i++) {
			$days[] = $yearMonth.'-'.$i;
		}


		foreach ($patients as $k => $patient) {
			unset($patient['id']);
			$patient['percentId']    = $percent_info['id'];
			$patient['patient_tel']  = $patient['tel'];
			$patient['patient_name'] = $patient['name'];
			foreach ($days as $day) {
				$patient['spending_date'] = $day;
				$res[] = $patient;
			}
		}

		if($spendingModel->addAll($res)){
			$this->success('添加成功','/Master/Index/index');
		}
	}

	public function totalYear()
	{

		$spendingModel = M('spending');
		$search_date = isset($_GET['date']) ? I('date') :  date('Y-m-d',time());
		$hospital_name = $_SESSION['hospital_name'];
		$year = date('Y', strtotime($search_date));
		$firstDay = $year.'-01-01';
		$lastDay  = $year.'-12-31';
		$map['hospital_id']   = $_SESSION['hospital_id'];
		$map['spending_date'] = array('between',array($firstDay, $lastDay));
		$list = $spendingModel->where($map)->select();
		$data = array();
		$patient_num = array();
		foreach ($list as $item) {
			$date = date('Y-m', strtotime($item['spending_date']));
			$data['总额']['消费总额']['money'] += $item['cost_money'];
			$data['月份'][$date]['money'] += $item['cost_money'];
			if($item['cost_money'] < 1) continue;
			$patient_num['total'][$item['patient_tel']] = '';
			$patient_num['month'][$date][$item['patient_tel']] = $item['patient_tel']; 
		}
		foreach ($patient_num['month'] as $key => $month_total) {
			$data['月份'][$key]['count'] += count($month_total); 
		}
		$data['总额']['消费总额']['count'] = isset($patient_num['total']) ? count($patient_num['total']) : 0;
		$total = isset($data['总额']['消费总额'])? $data['总额']['消费总额']['money'] : 0 ;
		$this->assign(array(
			'color'			  => self::$color,
			'total'			  => $total,
			'list_data'		  => $data,
			'menu'			  => self::$menu,
			'hospital_name'   => $hospital_name,
			'date'			  => $year,
			));
		$this->display();
	}

	public function totalDay()
	{	
		$search_date = isset($_GET['date']) ? I('date') :  date('Y-m-d',time());
		$model = M('spending');
		$hospital_name = $_SESSION['hospital_name'];
		$map['patient.hospital_id'] = $_SESSION['hospital_id'];
		$map['spending_date'] = date('Y-m-d', strtotime($search_date));
		$list = $model->where($map)->join('patient ON patient.tel = patient_tel')->select();
		$data = array();
		$max = 0;
		$min = 0;
		foreach ($list as $key => $item) {
			$data['总额']['消费总额']['money'] += $item['cost_money'];	
			if ($item['cost_money'] < 1) continue;
			$cost[] = $item['cost_money'];
			$data['总额']['消费总额']['count'] += 1;
			$data['性别'][$item['sex']]['money'] += $item['cost_money'];
			$data['性别'][$item['sex']]['count'] += 1;
			$data['病种'][$item['illness']]['money'] += $item['cost_money'];
			$data['病种'][$item['illness']]['count'] += 1;
		}
		$max = max($cost);
		$min = min($cost);
		$data['峰值']['最大消费']['money'] = $max;
		$data['峰值']['最小消费']['money'] = $min; 
		$total = isset($data['总额']['消费总额'])? $data['总额']['消费总额']['money'] : 0 ;
		$this->assign(array(
			'color'			  => self::$color,
			'menu'			  => self::$menu,
			'list_data'       => $data,
			'total'			  => $total,
			'hospital_name'   => $hospital_name,
			'date'			  => $search_date,
			));
		$this->display();
	}



	public function totalMonth()
	{
		$spendingModel = M('spending');
		$search_date = isset($_GET['date']) ? I('date') :  date('Y-m-d',time());
		$yearMonth = date('Y-m', strtotime($search_date));
		$firstDay  = date('Y-m-d', strtotime("first day of $search_date "));
		$lastDay   = date('Y-m-d', strtotime("last day of $search_date "));
		$map['patient.hospital_id']   = $_SESSION['hospital_id'];
		$map['spending_date'] = array('between', array($firstDay, $lastDay));
		$list =  $spendingModel	-> join('patient ON patient_tel = patient.tel')
								->where($map)
								->select();
		$hospital_name = $_SESSION['hospital_name'];
		$data = array();
		$patient_num = array();
		foreach ($list as $item) {
			$data['总额']['消费总额']['money'] += $item['cost_money'];
				$data['日期'][$item['spending_date']]['money'] += $item['cost_money'];
				$data['病种'][$item['illness']]['money'] += $item['cost_money'];
				if ($item['cost_money'] > 0) {
					$data['病种'][$item['illness']]['count'] += 1;
					$data['日期'][$item['spending_date']]['count'] += 1;
					$patient_num[$item['patient_tel']] = '';
				}
		}
		$total = isset($data['总额']) ? $data['总额']['消费总额']['money'] : 0; 
		$data['总额']['消费总额']['count'] = count($patient_num);
		$this->assign(array(
			'color'			  => self::$color,
			'total'			  => $total,
			'list_data'		  => $data,
			'menu'			  => self::$menu,
			'hospital_name'   => $hospital_name,
			'date'			  => date('Y-m', strtotime($search_date)),
			));
		$this->display();
	}
}
