<?php
namespace Home\Controller;

class PatientController extends CommonController 
{

	public function index() 
	{
		$prefix  = C('SESSION_PREFIX');
		$hospital_id   = $_SESSION[$prefix]['hospital_id'];
		$unique  = $this->_unique('add_form');
		$doctorModel = M('doctor');
		$illModel    = M('illness');
		$doctors = $doctorModel->where(array('hospital_id'=>$hospital_id))->select();
		$illness = $illModel->where(array('hospital_id' => $hospital_id))->select();
		$this->assign(array(
			'add_form_unique' => $unique,
			'menu'			  => self::$menu,
			'hospital_name'   => $_SESSION[$prefix]['hospital_name'],
			'doctors'         => $doctors,
			'illness'         => $illness,
  		));
		$this->display();
	}

	public function info() 
    {

		if (!isset($_GET['tel']) || strlen($_GET['tel']) != '11') {
			header('location:/Home/Index/index');
			exit;
		}
		$prefix = C('SESSION_PREFIX');
		$date = date('Y-m-d');
		$unique = $this->_unique('alter_form');
		$patientModel  = M('patient');
		$spendingModel = D('spending');
		$doctorModel   = M('doctor');
		$illModel      = M('illness');

		$tel = addslashes(trim($_GET['tel']));
		$list = $spendingModel->getWithTel($tel, $date);
		if (!$patient = $patientModel->getByTel($tel)) {
			$this->error('病人信息错误，请检查', 'Index/index', 5);
			exit;
		}

		//医生数据
		$res = $doctorModel->field('id,name')->where(array('hospital_id'=>$_SESSION[C('SESSION_PREFIX')]['hospital_id']))->select();
		foreach ($res as $doctor) {
			$doctor_info[$doctor['name']] = $doctor['name'];
		}
		$doctor_info[$patient['doctor_name']] = $patient['doctor_name'];

		//病种数据
		$illness = $illModel->where(array('hospital_id' => $_SESSION[C('SESSION_PREFIX')]['hospital_id']))->select();
		$ill_list = array();
		foreach ($illness as $ill) {
			$ill_list[$ill['illness_name']] = $ill['illness_name'];
		}
		$ill_list[$patient['illness']] = $patient['illness'];
		$map['patient_tel'] = $tel;
		$sum = $spendingModel->field('sum(cost_money)')->where($map)->find();
		unset($patient['hospital_id']);
		$this->assign(array(
			'patient' => $patient,
			'sum' => $sum['sum(cost_money)'],
			'unique' => $unique,
			'date' => $date,
			'spending_list' => $list,
			'menu' => self::$menu,
			'doctor_info' => $doctor_info,
			'illness' => $ill_list,
			'hospital_name' => $_SESSION[$prefix]['hospital_name'],
		));
		$this->display();
	}

	//添加病人
	public function addPatient() 
    {
		if (!IS_AJAX || empty($_POST) || !isset($_POST['unique']) || $_POST['unique'] !== $_SESSION['add_form']) {
			$this->error('非法操作', 'index', 2);
			exit;
		}



		$errMsg = '';
		$tel = array();
		$hospital_id   = $_SESSION[C('SESSION_PREFIX')]['hospital_id'];
		$patientModel  = M('Patient');
		$spendingModel = D('Spending');
		$pModel        = M('proportion');

		//获取当前比例 
		$map['hospital_id'] = $_SESSION[C('SESSION_PREFIX')]['hospital_id'];
		$map['is_use']      = 1;
		$percent_info       = $pModel->field('id')->where($map)->find();

		//组合数据
		
		foreach ($_POST as &$item) {
			if(!$item) $errMsg = '数据为空';
			$itme = htmlspecialchars(addslashes($item));
		}

		//查询电话是否存在
		$map['tel'] = array('in', $_POST['tel']);
		if ($re = $patientModel->where($map)->find()) {
			$errMsg = "病人[" . $re['name'] . "]已存在相同的电话[" . $re['tel'] . "]";
		}
		
		//插入数据
		if ($errMsg) {
			
			$this->ajaxReturn(array(
				'status' => 'error',
				'errMsg' => $errMsg,
			));

		} else {


			$nextLastDay  = date('Y-m-d',strtotime('last day of next month'));
			$nextFirstDay = date('Y-m-d',strtotime('first day of next month'));

			//拼装 消费表所需要的住院日期
			$nextAllDate[$_POST['name']] = $nextFirstDay; 
			$allDate[$_POST['name']] = $_POST['hospital_date'];
			$info['0']['hospital_id'] = $_SESSION[C('SESSION_PREFIX')]['hospital_id'];
			$info['0']['patient_name'] = $_POST['name'];
			$info['0']['patient_tel'] = $_POST['tel'];
			$info['0']['percentId'] = $percent_info['id'];
			$info['0']['doctor_name'] = $_POST['doctor'];

			$_POST['hospital_id'] = $_SESSION[C('SESSION_PREFIX')]['hospital_id'];
			$_POST['doctor_name'] = $_POST['doctor'];
			
			if ($id = $patientModel->data($_POST)->add()) {

				if ($spendingModel->addSpendings($allDate, $info)) {

					//是否存在下个月消费表  若有  则生成下月病人对应消费表
					$map['spending_date'] = array('between',array($nextFirstDay, $nextLastDay));
					if ($spendingModel->where($map)->find()) {
						$spendingModel->addSpendings($nextAllDate, $info);
					}

					$this->ajaxReturn(array(
						'status' => 'ok',
					));
				}
			} else {
				$this->ajaxReturn(array(
					'status' => 'error',
					'errMsg' => '添加失败',
				));

			}

		}
	}

	public function save() 
    {
		if (!IS_AJAX || empty($_POST) || !isset($_POST['unique']) || $_POST['unique'] !== $_SESSION['alter_form']) {
			header('location:/Home/Index/index');
			exit;
		}
		$patientModel = M('patient');
		//过滤一下
		foreach ($_POST as $field => &$item) {
			if ($field == 'age' || $field == 'id') {
				$item = (int) $item;
				continue;
			}
			$item = addslashes($item);
		}
		if ($patientModel->data($_POST)->save()) {
			$this->ajaxReturn(array(
				'status' => 'ok',
			));
		} else {
			$this->ajaxReturn(array(
				'status' => 'error',
			));
		}
	}

	public function search() 
    {

		$errMsg = '';
		if (!IS_AJAX || !isset($_POST['unique']) || $_POST['unique'] !== $_SESSION['select_form']) {
			$errMsg = '非法操作';
		}

		if(!isset($_POST['name']) || !isset($_POST['date']) || !$date = _checkDate($_POST['date'])){
			$errMsg = '请正确输入数据';
		}

		if ($errMsg) {
			$this->ajaxReturn(array(
				'status' => 'error',
				'errMsg' => $errMsg
				));
		} else {
			$model = M('spending');
			$firstDay 			  = date('Y-m-d', strtotime("first day of $date"));
			$lastDay			  = date('Y-m-d', strtotime("last day of $date"));
			$dayNum				  = date('d', strtotime("last day of $date"));
            if(!isset($_POST['null'])){
                $map['patient_name']  = ':name';
                $bind[':name']        = array(I('name'),\PDO::PARAM_STR);
            }
            $map['hospital_id']   = $_SESSION[C('SESSION_PREFIX')]['hospital_id'];
			$map['spending_date'] = array('between',array(":firstDay", ":lastDay"));
            $bind[':firstDay']    = array($firstDay,\PDO::PARAM_STR);
			$bind[':lastDay']     = array($lastDay,\PDO::PARAM_STR);
			$list				  = $model->where($map)->bind($bind)->select();
            foreach($list as $key => $info){
                $res[$info['patient_tel']]['name'] = $info['patient_name'];
                $res[$info['patient_tel']]['cost'][$key] = $info;
                $res[$info['patient_tel']]['sum_money'] += $info['cost_money'];
            }

            $this->ajaxReturn(array(
                'status' => 'ok',
                'info'   => $res,
				'days'   => $dayNum
                ));
		}
	}

	//单个查询
	public function searchOne()
	{
		$errMsg = '';
		if (!IS_AJAX || !isset($_POST['unique']) || $_POST['unique'] !== $_SESSION['alter_form']) {
			$errMsg = '非法操作';
		}
		if (!isset($_POST['patient_tel']) || !isset($_POST['date']) || !$date = _checkDate($_POST['date'])) {
			$errMsg = '数据异常';
		}

		if ($errMsg) {
			$this->ajaxReturn(array(
				'status'  => 'error',
				'errMsg'  => $errMsg
				));
		}else{
			$moedl    = M('spending');
			$firstDay = date('Y-m-d', strtotime("first day of $date"));
			$lastDay  = date('Y-m-d', strtotime("last day of $date"));
			$map['hospital_id']   = $_SESSION[C('SESSION_PREFIX')]['hospital_id'];
			$map['patient_tel']   = ':tel';
			$map['spending_date'] = array('between', array($firstDay, $lastDay));
			$bind[':tel']   	  = array(I('patient_tel'),\PDO::PARAM_STR);
			$list 				  = $moedl->where($map)->bind($bind)->select();
			if(empty($list)) $list = null;
 			$this->ajaxReturn(array(
				'status' => 'ok',
				'info'	 => $list
				));
 		}

	}

    
}