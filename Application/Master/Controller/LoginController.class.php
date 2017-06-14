<?php
namespace Master\Controller;

use Think\Controller;

class LoginController extends Controller
{

    public function index()
    {
        $_unique = $this->_unique('form_unique');
        $this->assign('_unique',$_unique);
        $this->display();
    }

    //判断验证码 判断验证码  验证成功后会刷新唯一标识
    public function login()
    {   
        if ($_POST['_unique'] !== $_SESSION['form_unique'] 
            || !filter_has_var(INPUT_POST, 'verify')
            || !filter_has_var(INPUT_POST, 'tel')
            || !isset($_SESSION['user'][$_POST['tel']]['verify'])
            ) {

            $this->error('非法操作','index',1);
            exit;
        }

        $tel = addslashes(trim($_POST['tel']));
        $verify = sha1($_POST['verify']);
        if ($verify !== $_SESSION['user'][$tel]['verify']) {
            $this->error('验证码错误','index',1);
            exit;
        }
        $_SESSION = array();
        $userModel = M('admin');
        $result = $userModel->getByTel($tel);
        $data['id'] = $result['id'];
        $data['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['last_login_time'] = date('Y-m-d H:i:s', time());
        $userModel->data($data)->save();
        $_SESSION[C('SESSION_PREFIX')]['name'] = $result['name'];
        $_SESSION[C('SESSION_PREFIX')]['id'] = $result['id'];
        $_SESSION[C('SESSION_PREFIX')]['status'] = 'admin';
        $_SESSION[C('SESSION_PREFIX')]['admin_part'] = $result['admin_part'];
        $this->success('登录成功', '/Master/Index/index');
    }


    //检测是否存在用户  并生成短信验证唯一标识
    public function checkUser()
    {   

        if (!IS_AJAX || !isset($_POST['tel'])) {
            return;
        }

        $userModel = M('admin');

        //手机验证
        $tel = addslashes(trim($_POST[tel]));

        if (!$user = $userModel->getByTel($tel)) {
            $errMsg = '用户名不存在';
        }

        if ($user['is_use'] !== '1') {
            $errMsg = '用户被冻结';
        }

        if ($errMsg) {
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => $errMsg
                ));
        } else {
            $_unique = $this->_unique('msgUnique');
            $this->ajaxReturn(array(
                'status' => 'ok',
                '_unique' => $_unique
                ));
        }

    }


    public function sendVerify()
    {
        $errMsg = '';
        if (!filter_has_var(INPUT_POST, 'tel') || !filter_has_var(INPUT_POST, 'msgUnique')
            ) {
            $errMsg = '非法操作';
        }
        //验证短信唯一标识
       if ($_SESSION['msgUnique'] !== $_POST['msgUnique']) {
            $errMsg = '非法操作';
       } 

       if (isset($_SESSION['user'][$_POST['tel']]['timelimit']) && (time() < $_SESSION['user'][$_POST['tel']]['timelimit'])) $errMsg = '时间间隔为60秒';

       $unique = $this->_unique('msgUnique');

       if ($errMsg) {
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => $errMsg,
                'msgUnique' => $unique
                ));
       } else {

            //生成验证码
            //设置发送间隔
            $_SESSION['user'][$_POST['tel']]['timelimit'] = time()+60;

            $code = $this->makeVerify($_POST['tel']);
            $this->ajaxReturn(array(
                'status' => 'ok',
                'code'   => $code,
                'msgUnique' => $unique
                ));
       }
    }



    //生成验证码  并存到session  
    private function makeVerify($tel)
    {
        $length = C('VERIFY_LENGTH');
        $code = mt_rand(0,9);
        for ($i=1;$i<$length;$i++) {
            $code .= mt_rand(0,9);
        }
        $_SESSION['user'][$tel]['verify'] = sha1($code);
        return $code;
    }


    //短信接口
    private function sms($tel, $msg)
    {
		$statusStr = array(
		"0" => "短信发送成功",
		"-1" => "参数不全",
		"-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
		"30" => "密码错误",
		"40" => "账号不存在",
		"41" => "余额不足",
		"42" => "帐户已过期",
		"43" => "IP地址限制",
		"50" => "内容含有敏感词"
		);
		$smsapi = "http://api.smsbao.com/";
		$user = "18220877335"; //短信平台帐号
		$pass = md5("0594010"); //短信平台密码
		$content= $msg;//要发送的短信内容
		$phone = $tel;//要发送短信的手机号码
		$sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
		$result =file_get_contents($sendurl) ;
		if($result == '0') return true;
		return false;
    }


    //表单唯一标识
    private function _unique($name)
    {
        $code = mt_rand(0,9);
        for ($i=1;$i<5;$i++) {
            $code .= mt_rand(0,9);
        }
        return $_SESSION[$name] = sha1($code);
    }

    //退出登录
    public function logout()
    {
        setcookie(session_name(),session_id(),time()-1);
        unset($_SESSION);
        session_destroy();
        $this->redirect('/Master');
    }

    //用户名登录验证码
    public function verify()
    {
        $config =    array(
            'fontSize'    =>    15,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
            'imageW'      =>    0,
            'imageH'      =>    0,

        );
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }

    //用户名密码登录
    public function usernameLogin()
    {
        if (!filter_has_var(INPUT_POST, 'username')||
            !filter_has_var(INPUT_POST, 'password')||
            !filter_has_var(INPUT_POST, 'verify')||
            !filter_has_var(INPUT_POST, '_unique')||
            $_POST['_unique'] !== $_SESSION['form_unique']
            ) {
            $this->error('非法操作');
            exit;
        }

        if (!checkVerify($_POST['verify'])) {
            $this->error('验证码错误','/Master/Login/index');
            exit;
        }


        $model = M('admin');
        $map['username'] = ':username';
        $bind[':username'] = array(I('username'), \PDO::PARAM_STR);
        if(!$info = $model->where($map)->bind($bind)->find()){
            $this->error('不存在此用户','/Master/Login/index');
            exit;
        }

        if ($info['is_use'] !== '1') {
            $this->error('用户被冻结,请联系管理员', '/Master/Login/index');
            exit;
        }

        $password = substr(sha1(trim(I('password'))), 20,20);

        if($info['password'] !== $password){
            $this->error('密码错误','/Master/Login/index');
            exit;
        }

        $_SESSION = array();
        $data['id'] = $info['id'];
        $data['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['last_login_time'] = date('Y-m-d H:i:s', time());
        $model->data($data)->save();
        $_SESSION[C('SESSION_PREFIX')]['name'] = $info['name'];
        $_SESSION[C('SESSION_PREFIX')]['id'] = $info['id'];
        $_SESSION[C('SESSION_PREFIX')]['status'] = 'admin';
        $_SESSION[C('SESSION_PREFIX')]['admin_part'] = $info['admin_part'];
        $this->success('登录成功', '/Master/Index/index');
    }



}