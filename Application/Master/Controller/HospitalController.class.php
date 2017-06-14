<?php
namespace Master\Controller;


class HospitalController extends CommonController
{
    public function index()
    {
        $hospitalModel = M('hospital');
        $spendingModel = M('spending');
        $proportionModel = M('proportion');
        $rechangeModel = M('rechange');
        $hospitalList  = $hospitalModel->order('sort desc')->select();
        $proportionList = $proportionModel->field('hospital_id,percent')->where(array('is_use'=>'1'))->select();
        $rechangeList = $rechangeModel->field('sum(money) as remaining_sum, hospital_id')
                                      ->group('hospital_id')
                                      ->select();
        foreach ($rechangeList as $list) {
            $reg[$list['hospital_id']] = $list['remaining_sum'];
        }

        $cost_info = $spendingModel->query('select sum(s.cost_money) as cost_sum,s.percentId,s.hospital_id,p.percent,p.is_use from spending as s left join proportion as p on s.percentId=p.id group by s.percentId');
        $sum = 0;


        foreach($cost_info as $cost){
            if($cost['is_use'] == '1'){
                $percent[$cost['hospital_id']] = $cost['percent'];
            }
            $res[$cost['hospital_id']] += $cost['percent'] * $cost['cost_sum'];
        }


        foreach ($hospitalList as &$item) {
            $item['cost_sum'] = $res[$item['id']];
            $item['leav']     = $reg[$item['id']] - $res[$item['id']];
            $item['percent']  = $percent[$item['id']];
        }

        

        $this->assign(array(
            'sum_money'    => $res,
            'hospitalList' => $hospitalList,
            'menu' => self::$menu,
            'hospital_name' => $_SESSION['hospital_name'],
            ));
        $this->display();
    }


    public function add()
    {
        $unique = $this->_unique('add_form');
        $this->assign(array(
            'add_form_unique' => $unique,
            'menu' => self::$menu,
            ));
        $this->display();
    }

	public function updateSort()
	{
		 if (!IS_AJAX 
            || !isset($_POST['sort']) 
            || !isset($_POST['id']) 
            ) {
            $this->error('非法操作');
            exit;
        }
		
		$model = M('hospital');
		$model->sort = (int)$_POST['sort'];
		if (!$model->where(array('id'=>(int)$_POST['id']))->save()) return json_encode(['status'=>'error', 'info'=>'修改失败']);
		return json_encode(['status'=>'ok', 'info'=>'修改成功']);
	}
	
	
    public function addHospital()
    {
        $errMsg = '';
        if (!IS_AJAX 
            || !isset($_POST['name']) 
            || !isset($_POST['intro'])
            || !isset($_POST['remaining_sum'])
            || !isset($_POST['percent'])
            || !isset($_POST['unique'])
            || !isset($_POST['verify_money'])
            || $_POST['unique'] !== $_SESSION['add_form']
            ) {
            $this->error('非法操作');
            exit;
        }
        $hospital_name  = htmlspecialchars(addslashes(trim($_POST['name'])));
        $hospital_intro = htmlspecialchars(addslashes(trim($_POST['intro'])));
        $money          = (int)$_POST['remaining_sum'];
        $percent        = (float)($_POST['percent']/100);
        $verify_money   = (int)$_POST['verify_money'];
        $hospitalModel  = M('hospital');
        $proportionModel = M('proportion');
        $rechangeModel  = M('rechange');
        $vRechargeModel  = M('verifyRecharge');
        $hospitalModel->name  = $hospital_name;
        $hospitalModel->intro = $hospital_intro;
        $hospitalModel->add_admin = $_SESSION[C('SESSION_PREFIX')]['name'];
        if ($id = $hospitalModel->add()) {

            //余额充值
           $rechangeModel->money = $money;
           $rechangeModel->hospital_id = $id;
           $rechangeModel->add_admin = $_SESSION[C('SESSION_PREFIX')]['name'];

           if (!$rechangeModel->add()) {
                $errMsg = '金额充值失败';
           }

           //初始比例
           $proportionModel->percent = $percent;
           $proportionModel->hospital_id = $id;
           $proportionModel->add_admin = $_SESSION[C('SESSION_PREFIX')]['name'];
           $proportionModel->begin_date = date('Y-m-d', time());
           $proportionModel->is_use = '1';
           if (!$proportionModel->add()) {
                $errMsg = '初始比例设置失败';
           }   

           $vRechargeModel->money = $verify_money;
           $vRechargeModel->hospital_id = $id;
           $vRechargeModel->add_admin = $_SESSION[C('SESSION_PREFIX')]['name'];

            if (!$vRechargeModel->add()) {
                $errMsg = '验证码余额充值失败';
           }

           if ($errMsg) {
                $this->ajaxReturn(array(
                    'status' => 'error',
                    'errMsg' => $errMsg
                    ));
           } else {
                $this->ajaxReturn(array(
                    'status' => 'ok'
                    ));
           }
        }
    }


    public function info()
    {
        if(!isset($_GET['id'])){
            $this->error('非法操作');
            exit;
        }
        $unique = $this->_unique('alter_form');
        $id = (int)$_GET['id'];
        $hModel = M('hospital');
        $sModel = M('spending');
        $pModel = M('proportion');
        $rModel = M('rechange');
        $aModel = M('hospital_admin');
        $vModel = M('verify_recharge');
        $recordModel = M('verify_record');
        if (!$hospital_info = $hModel->find($id)) {
            $this->error('未找到医院');
            exit;
        }

        $admin_list = $aModel->where(array('hospital_id'=>$id))->select();
        
        //费用 & 比例
        $cost_info = $sModel->query('select sum(s.cost_money) as cost_sum,p.* from spending s right join proportion p on s.percentId=p.id where p.hospital_id='.$id.' group by p.id');
        $sum = 0;


        foreach ($cost_info as  &$cost) {
            if($cost['is_use'] == '1'){
                $cost['end_date'] = '正在使用';
            }
            $cost['extract_money'] = $cost['percent'] * $cost['cost_sum'];
            $cost_list[$cost['is_use']][] = $cost;
            $sum  += $cost['percent'] * $cost['cost_sum'];
        }
        //比例数据 
    
        //这里后面考虑用PHP替代计算
        $sum_info = $rModel->field('sum(money) as money')
                           ->where(array('hospital_id'=>$id))
                           ->find();

        $regList  = $rModel->where(array('hospital_id' => $id))->select();
        $vlist    = $vModel->field('hospital_id,add_admin,money,add_datetime')->where(array('hospital_id' => $id))->select();
        $count    = $recordModel->field('count(id) as count ')->where(array('hospital_id'=>$id))->find();
        foreach($vlist as $verify){
            $verify_recharge_sum += $verify['money'];
        }

        $verify_recharge_sum = $verify_recharge_sum-($count['count'] * C('VERIFY_PRICE'));
        $hospital_info['percent'] = $cost_list['1']['0']['percent'] * 100;
        $hospital_info['cost']  = $sum;
        $hospital_info['leav']  = $sum_info['money'] - $sum;
        $hospital_info['porId'] = $cost_list['1']['0']['id'];
        $hospital_info['verify_recharge_sum'] = $verify_recharge_sum;


        $this->assign(array(
            'hospital' => $hospital_info,
            'menu'   => self::$menu,
            'unique' => $unique,
            'percent_list' => $cost_info,
            'regList' => $regList,
            'admin_list' => $admin_list,
            'empty'  => '<ul class="proportion"><li class="empty">暂无数据</li></ul>',
            'verify_list' => $vlist 
            ));
        $this->display();
    }

    public function update()
    {
        if (!IS_AJAX || !IS_POST 
            || !filter_has_var(INPUT_POST, 'id')
            || !filter_has_var(INPUT_POST, 'unique')
            || !filter_has_var(INPUT_POST, 'name')
            || !filter_has_var(INPUT_POST, 'is_use')
            || $_POST['unique'] !== $_SESSION['alter_form']
            ) {
            $this->error('非法操作');
            exit;
        }   

        $data['id'] = (int)$_POST['id'];
        $data['name'] = addslashes(htmlspecialchars(trim($_POST['name'])));
        $data['intro'] = addslashes(htmlspecialchars(trim($_POST['intro'])));
        $data['is_use'] = (int)$_POST['is_use'];
        $model = M('hospital');
        if ($model->data($data)->save()) {
            $this->ajaxReturn(array(
                'status' => 'ok'
                ));
        }else{
            $this->ajaxReturn(array(
                'status' => 'error'
                ));
        }
    }


    public function updatePor()
    {
        if (!IS_AJAX || !IS_POST 
            || !filter_has_var(INPUT_POST, 'hospital_id')
            || !filter_has_var(INPUT_POST, 'id')
            || !filter_has_var(INPUT_POST, 'unique')
            || !filter_has_var(INPUT_POST, 'percent')
            || $_POST['unique'] !== $_SESSION['alter_form']
            ) {
            $this->error('非法操作');
            exit;
        } 

        $model  = M('proportion');
        $sModel = M('spending'); 
        $data['id'] = (int)$_POST['id'];
        if (!$percent = $model->find($data['id'])) {
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => '原始比例不存在'
                ));
            exit;
        }

        
        

        //修改原比例
        $data['is_use'] = 0;
        $data['end_date']  = date('Y-m-d',time());
            

        //添加新的
        $tmp['begin_date'] = date('Y-m-d',strtotime("{$data['end_date']} +1 day"));
        $tmp['percent'] = (float)($_POST['percent']/100);
        $tmp['hospital_id'] = (int)$_POST['hospital_id'];
        $tmp['add_admin'] = $_SESSION[C('SESSION_PREFIX')]['name'];


        if ($id = $model->data($tmp)->add()) {
            //修改消费表percenId
            $map['spending_date'] = array('EGT', $tmp['begin_date']);
            $map['hospital_id']   = $tmp['hospital_id'];
            if ($model->data($data)->save() && $sModel->where($map)->save(array('percentId'=>$id))) {
                $this->ajaxReturn(array(
                    'status' => 'ok'
                    ));
            }else{
                $this->ajaxReturn(array(
                    'status' => 'error',
                    'errMsg' => '原始比例修改失败',
                    ));
            }

        }else{
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => '新比例生成失败',
                ));
        }

       
    }

    //余额充值
    public function recharge()
    {
        if (!IS_AJAX || !IS_POST 
            || !filter_has_var(INPUT_POST, 'hospital_id')
            || !filter_has_var(INPUT_POST, 'money')
            || !filter_has_var(INPUT_POST, 'unique')
            || !filter_has_var(INPUT_POST, 'verify')
            || $_POST['unique'] !== $_SESSION['alter_form']
            ) {
            $this->error('非法操作');
            exit;
        }  

        $verify = new \Think\Verify();
        if (!$verify->check($_POST['verify'])) {
             $this->ajaxReturn(array(
                'status'  => 'error',
                'errMsg'  => '验证码错误'
                ));
             exit;
        }

        unset($_SESSION['alter_form']);
        $data['money'] = (float)$_POST['money'];
        $data['hospital_id'] = (int)$_POST['hospital_id'];
        $data['add_admin']   = $_SESSION[C('SESSION_PREFIX')]['name'];
        $model = M('rechange');
        if ($model->data($data)->add()) {
            $this->ajaxReturn(array(
                'status'  => 'ok'
                ));
        } else {
            $this->ajaxReturn(array(
                'status'  => 'error',
                'errMsg'  => '充值失败'
                ));
        }
    }

    public function add_admin()
    {
        if (!IS_AJAX || !IS_POST 
            || !filter_has_var(INPUT_POST, 'hospital_id')
            || !filter_has_var(INPUT_POST, 'admin_name')
            || !filter_has_var(INPUT_POST, 'admin_tel')
            || !filter_has_var(INPUT_POST, 'permission')
            || !filter_has_var(INPUT_POST, 'unique')
            || !filter_has_var(INPUT_POST, 'verify')
            || $_POST['unique'] !== $_SESSION['alter_form']
            ) {
            $this->error('非法操作');
            exit;
        }

       $verify = new \Think\Verify();
       if (!$verify->check($_POST['verify'])) {
            $this->ajaxReturn(array(
                'status'  => 'error',
                'errMsg'  => '验证码错误',
                ));
            exit;
       }

       $name = addslashes(htmlspecialchars(trim($_POST['admin_name'])));
       $tel  = addslashes(htmlspecialchars(trim($_POST['admin_tel'])));
       $hospital_id = (int)$_POST['hospital_id'];
       $permission = (int)$_POST['permission'];
       $model = M('hospital_admin');

       if ($info = $model->getByTel($tel)) {
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => "已存在用户【{$info['name']}】的电话为【{$info['tel']}】"
                ));
       }

       $model->name = $name;
       $model->tel  = $tel;
       $model->hospital_id = $hospital_id;
       $model->permission = $permission;
       if ($model->add()) {
            $this->ajaxReturn(array(
                'status' => 'ok',
                ));
       } else {
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => '添加失败，请重试',
                ));
       }
    }


    public function admin_update()
    {
        if (!IS_AJAX || !IS_POST 
            || !filter_has_var(INPUT_POST, 'id')
            || !filter_has_var(INPUT_POST, 'admin_name')
            || !filter_has_var(INPUT_POST, 'admin_tel')
            || !filter_has_var(INPUT_POST, 'unique')
            || $_POST['unique'] !== $_SESSION['alter_form']
            ) {
            $this->error('非法操作');
            exit;
        }

        $model = M('hospital_admin');
        $model->id = (int)$_POST['id'];
        $model->name = addslashes(htmlspecialchars(trim($_POST['admin_name'])));
        $model->tel = addslashes(htmlspecialchars(trim($_POST['admin_tel'])));
        if ($model->save()) {
            $this->ajaxReturn(array(
                'status' => 'ok'
                ));
        } else {
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => '修改失败，请重试',
                ));
        }
    }

    public function update_status()
    {
        if (!IS_AJAX || !IS_POST 
            || !filter_has_var(INPUT_POST, 'id')
            || !filter_has_var(INPUT_POST, 'caozuo')
            || !filter_has_var(INPUT_POST, 'unique')
            || $_POST['unique'] !== $_SESSION['alter_form']
            ) {
            $this->error('非法操作');
            exit;
        }

        switch ($_POST['caozuo']) {
            case 'jiedong':
                $data['is_use'] = '1';
                break;

            case 'dongjie':
                $data['is_use'] = '0';
                break;

            default:
                exit;
                break;
        }

        $data['id'] = (int)$_POST['id'];
        $model = M('hospital_admin');

        if ($model->data($data)->save()) {
            $this->ajaxReturn(array(
                'status' => 'ok'
                ));
        } else {
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => ''
                ));
        }

    }

    public function verifyRecharge()
    {
        if (!IS_AJAX || !IS_POST 
            || !filter_has_var(INPUT_POST, 'hospital_id')
            || !filter_has_var(INPUT_POST, 'money')
            || !filter_has_var(INPUT_POST, 'unique')
            || !filter_has_var(INPUT_POST, 'verify')
            || $_POST['unique'] !== $_SESSION['alter_form']
            ) {
            $this->error('非法操作');
            exit;
        }  

        $verify = new \Think\Verify();
        if (!$verify->check($_POST['verify'])) {
             $this->ajaxReturn(array(
                'status'  => 'error',
                'errMsg'  => '验证码错误'
                ));
             exit;
        }
        $model = M('verify_recharge');
        $model->money = (int)$_POST['money'];
        $model->hospital_id = (int)$_POST['hospital_id'];
        $model->add_admin = $_SESSION[C('SESSION_PREFIX')]['name'];

        if ($model->add()) {
            $this->ajaxReturn(array(
                'status' => 'ok'
                ));
        } else {
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => '充值失败，请重试'
                ));
        }

    }


    public function illness()
    {
        $this->assign(array(
            'add_form_unique' => $this->_unique('add_form'),
            'menu' => self::$menu,
            'hospital_name' => $_SESSION['hospital_name']

            ));
        $this->display();
    }

    public function addIllness()
    {
        if (!IS_AJAX || !IS_POST 
            || !filter_has_var(INPUT_POST, 'name')
            || !filter_has_var(INPUT_POST, 'unique')
            || $_POST['unique'] !== $_SESSION['add_form']
            ) {
            $this->error('非法操作');
            exit;
        }  
        $_POST['illness_name'] =  htmlspecialchars(addslashes(trim($_POST['name'])));
        $_POST['hospital_id']  = $_SESSION['hospital_id'];
        $model = M('illness');
        
        if ($model->data($_POST)->add()) {
            $this->ajaxReturn(array(
                'status' => 'ok'
                ));
        } else {
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => '添加失败'
                ));
        }
    }

}


