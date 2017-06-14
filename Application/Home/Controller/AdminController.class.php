<?php
namespace Home\Controller;

class AdminController extends CommonController
{
	public function index()
	{
		$unique = $this->_unique('add_form');
		$this->assign(array(
			'add_form_unique' => $unique,
			'menu' => self::$menu,
			'hospital_name' => $_SESSION[C('SESSION_PREFIX')]['hospital_name'],
			));
		
		$this->display();
	}

	public function addAdmin()
	{
		if (!IS_AJAX || !IS_POST || !filter_has_var(INPUT_POST, 'unique')
			|| !filter_has_var(INPUT_POST, 'tel')
			|| !filter_has_var(INPUT_POST,'name')
			|| $_POST['unique'] !== $_SESSION['add_form']
			) {
			$this->error('非法操作');
			exit;
		}
		$adminModel = M('hospital_admin');
		$adminModel->tel  = I('tel');
		$adminModel->name = I('name');
		$adminModel->permission = (int)I('permission');
		$adminModel->hospital_id = $_SESSION[C('SESSION_PREFIX')]['hospital_id'];
		if ($adminModel->add()) {
			$this->ajaxReturn(array(
				'status' => 'ok'
				));
		} else {
			$this->ajaxReturn(array(
				'status' => 'error',
				'errMsg' => '服务器错误，添加失败',
				));
			exit;
		}
	}


	public function adminList()
	{
		$model = M('hospital_admin');
		$list = $model->where(array('hospital_id'=>$_SESSION[C('SESSION_PREFIX')]['hospital_id']))->select();
		$this->assign(array(
			'admin_list' => $list,
			'unique' => $this->_unique('alter_form'),
			'menu' => self::$menu,
			));
		$this->display();
	}



	/**
	 * 冻结   解冻账户
	 * @return [type] [description]
	 */
	public function access()
    {
        if (!isset($_GET['id']) || !isset($_GET['do']) || !isset($_GET['unique']) || $_GET['unique'] !== $_SESSION['alter_form'] ) {
            $this->error('非法操作');
            exit;
        }
        $model = M('hospital_admin');
        $model->id = (int)$_GET['id'];
        switch ($_GET['do']) {

            case 'dongjie':
                $is_use = '0';
                break;

            case 'jiedong':
                $is_use = '1';
                break;

            default:
               
                break;
        }
        $model->is_use = $is_use;
        if($model->save()){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }
    }

    /**
     * 重置密码
     * @return [null] [description]
     */
    public function resetPass()
    {
        if (!isset($_GET['id']) ||  !isset($_GET['unique']) || $_GET['unique'] !== $_SESSION['alter_form'] ) {
            $this->error('非法操作');
            exit;
        }
        $model = M('hospital_admin');

        if(!$info = $model->field('tel')->find($_GET['id'])){
            $this->error('非法操作');
            exit;
        }
        $tel = $info['tel'];
        $pass = mt_rand(10000,99999); 
        $model->id = (int)$_GET['id'];
        $model->password = substr(sha1($pass), 20,20);

        if ($model->save()) {
            //发送改过后的密码
        } else {
            $this->error('修改失败');
        }
    }


}