<?php
namespace Master\Controller;

class AdminController extends CommonController
{

    public function index()
    {
        if ($_SESSION[C('SESSION_PREFIX')]['admin_part'] < 1) {
            $this->error('权限不够');
            exit;
        }
        $model = M('admin');
        $admin_list = $model->select();
        $admin_part_list = array(
            '0' => '普通',
            '1' => '管理员',
            '2' => '超级管理员'
            );
        $this->assign(array(
            'admin_status' => $_SESSION[C('SESSION_PREFIX')]['admin_part'],
            'admin_list' => $admin_list,
            'menu' => self::$menu,
            'admin_part_list' => $admin_part_list,
            'unique' => $this->_unique('alter_form'),
            ));
        $this->display();
    }


    public function addAdmin()
    {
        if ($_SESSION[C('SESSION_PREFIX')]['admin_part'] < 2) {
            $this->error('权限不够');
            exit;
        }
        $this->assign(array(
            'menu' => self::$menu,
            'add_form_unique' => $this->_unique('add_form'),
            ));
        $this->display();
    }

    public function addAdminHandle()
    {
        if (!IS_AJAX || empty($_POST) || !isset($_POST['unique']) || $_POST['unique'] !== $_SESSION['add_form']) {
            $this->error('非法操作','Index/index',1);
            exit;
        }
        $errMsg = '';
        $adminModel = M('admin');
        foreach ($_POST as $field => $v) {
            foreach ($v as $k => $item) {
                if ($field != 'username' || $field !='pass') {
                    $errMsg = ($item == '') ? '参数为空' : '';
                }

                if($field == 'pass' && $item !== ''){
                    $item = substr(sha1(trim($item)), 20,20);
                }
                if ($field == 'pass') $field = 'password'; 
                $item = addslashes(htmlspecialchars(trim($item)));
                $res[$k][$field] = $item;
            }
        }

        if ($errMsg) {
            $this->ajaxReturn(array(
                'status'  => 'error',
                'errMsg'  => $errMsg
                ));
        } else {
            if ($adminModel->addAll($res)) {
                $this->ajaxReturn(array(
                    'status'  => 'ok'
                    ));
            }else{
                $this->ajaxReturn(array(
                    'status'  => 'error',
                    'errMsg'  => '添加失败，请重试',
                    ));
            }
        }
    }


    public function update()
    {
        $model = M('admin');
        $info  = $model->find($_SESSION[C('SESSION_PREFIX')]['id']);
        $this->assign(array(
            'menu' => self::$menu,
            'admin_info' => $info,
            'unique'  => $this->_unique('alter_form')
            ));
        $this->display();
    }


    public function updateHandle()
    {

        if (!IS_AJAX || empty($_POST) || !isset($_POST['unique']) || $_POST['unique'] !== $_SESSION['alter_form'] 
            || !filter_has_var(INPUT_POST, 'id')
            || !filter_has_var(INPUT_POST, 'verify')
            ) {
            $this->error('非法操作','Index/index',1);
            exit;
        }

        $errMsg = '';
        $model = M('admin');
        $id = (int)$_POST['id'];
        $verify = new \Think\Verify();
        if (!$verify->check($_POST['verify'])) {
            $errMsg = '验证码错误';
        }

        if (!$info = $model->find($id)) {
            $errMsg = '不存在用户';
        }

        if ($errMsg) {

            $this->ajaxReturn(array(
                'status'  => 'error',
                'errMsg'  => $errMsg
                ));

        } else {

            if ($info['username'] == '' || $info['password'] == '') {
                
                if (!filter_has_var(INPUT_POST, 'admin_username') || !filter_has_var(INPUT_POST, 'admin_new_password')) {
                    $this->error('非法操作','Index/index',1);
                    exit;
                }
                
                if($model->where(array('username' => trim(I('admin_username'))))->find()){
                     $this->ajaxReturn(array(
                        'status'  => 'error',
                        'errMsg'  => '用户名已存在,请重试',
                        ));
                     exit;
                }

                $model->id       =  $id;
                $model->username =  trim(I('admin_username'));
                $model->password =  substr(sha1(trim(I('admin_new_password'))), 20,20);
                if($model->save()){
                    $this->ajaxReturn(array(
                        'status'  => 'ok'
                        ));
                }else{
                    $this->ajaxReturn(array(
                        'status'  => 'error',
                        'errMsg'  => '修改失败,请重试',
                        ));
                }

            } else {

                if (!filter_has_var(INPUT_POST, 'admin_old_password') || !filter_has_var(INPUT_POST, 'admin_new_password')) {
                    $this->error('非法操作','Index/index',1);
                    exit;
                }

                if ($info['password'] !== substr(sha1(trim(I('admin_old_password'))), 20,20)) {
                    $this->ajaxReturn(array(
                        'status'  => 'error',
                        'errMsg'  => '原密码错误',
                        ));
                    exit;
                }

                $model->id = $id;
                $model->password = substr(sha1(trim(I('admin_new_password'))), 20,20);

                if($model->save()){

                    $this->ajaxReturn(array(
                        'status'  => 'ok',
                        ));
                    
                }else{

                    $this->ajaxReturn(array(
                        'status'  => 'error',
                        'errMsg'  => '修改失败，请重试',
                        ));
                   
                }
            }


        }
    }

    public function modify()
    {

        if (!IS_AJAX || !filter_has_var(INPUT_POST, 'id')
            || !filter_has_var(INPUT_POST, 'unique')
            || !filter_has_var(INPUT_POST, 'admin_part')
            || $_POST['unique'] !== $_SESSION['alter_form']
            ) {
            $this->error('非法操作');
            exit;
        }

        $model = M('admin');
        $model->id = (int)$_POST['id'];
        $model->admin_part = (int)$_POST['admin_part'];

        if ($model->save()) {
            $this->ajaxReturn(array(
                'status' => 'ok',
                ));
        }else{
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => '修改失败,请重试'
                ));
        }

    }

    public function access()
    {
        if (!isset($_GET['id']) || !isset($_GET['do']) || !isset($_GET['unique']) || $_GET['unique'] !== $_SESSION['alter_form'] ) {
            $this->error('非法操作');
            exit;
        }
        $model = M('admin');
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


    public function resetPass()
    {
        if (!isset($_GET['id']) ||  !isset($_GET['unique']) || $_GET['unique'] !== $_SESSION['alter_form'] ) {
            $this->error('非法操作');
            exit;
        }
        $model = M('admin');

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