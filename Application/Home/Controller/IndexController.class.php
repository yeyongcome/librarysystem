<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
	    $this->display();
    }

    public function setinfo(){
    	$data['id'] = session('id');
    	$result = M()->table('user_info')->where($data)->find();
    	$this->assign('result',$result);
    	$this->display();
    }

    public function save_info(){
    	$data['id']        = I('post.user_id');
        $data['real_name'] = I('post.rela_name');
        $data['gender']    = I('post.gender');
        $data['phone']     = I('post.phone');
        if(empty($data['id']) || empty($data)){
        	return false;
        }
        $result = M()->table('user_info')->save($data);
        if(empty($result)){
        	return false;
        }
        echo $result;
    }
    public function resetpwd(){
    	$this->display();
    }

    public function save_pwd(){
    	$old_pwd  = I("post.old_pwd");
        $new_pwd  = I("post.new_pwd");
        $re_pwd   = I("post.re_pwd");
        $user_id  = session('id');
        if($new_pwd != $re_pwd){
        	echo 4;
        	return false;
        }
        if(empty($old_pwd) || empty($new_pwd) || empty($re_pwd)){
        	echo 5;
        	return false;
        }
        $where['id'] = $user_id;
		$password = M()->table('user_info')->where($where)->getField('password');
		if($old_pwd == $new_pwd){
			//新密码和旧密码相同
			echo 2;
		}else if($password != md5($old_pwd)){
			//输入的原密码错误
			echo 3;
		}else{
			$save_data['password'] = md5($new_pwd);
			$num = M()->table('user_info')->where($where)->save($save_data);
			echo $num;
		}
    }
}