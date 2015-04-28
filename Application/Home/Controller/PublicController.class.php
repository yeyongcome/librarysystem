<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;

class PublicController extends Controller {
    public function index(){
	    $this->display();
    }

    public function do_login(){
    	$user_id  = trim(I("post.user_id"));
    	$password = trim(I("post.password"));
        $type     = trim(I("post.type"));
    	if(empty($user_id) || empty($password)){
    		return false;
    	}
    	$where['user_name'] = $user_id; 
    	$where['password']  = md5($password);
        $where['user_type'] = $type;
    	$result = M()->table("user_info")
    	             ->where($where)
    	             ->find();
    	if(empty($result)){
    		return false;
    	}else{
    		echo 2;
            cookie("selected_menu",null);//清空cookie
    		session("id",$result["id"]);
            session("type",$result["user_type"]);
            session("user_name",$result["user_name"]);
            //检查是否超期
            $this->chaeck_time();
            $user['operate_id']   = $result["id"];
            $user['content']      = "登陆系统成功";
            $user['operate_name'] = $result["user_name"];
            savelog($user);
    	}
    }

    //检查是否超期
    public function chaeck_time(){
        $passtime = C('S_PASSTIME'); //得到借阅时间
        $result = M()->table('borrow')->select();
        $count = count($result);
        for($i = 0 ; $i < $count; $i++){
            if($result[$i]['is_back'] == 0){
                $time = (time() - $result[$i]['create_time']) - $passtime*3600*24;
                if($time > 0){
                    $day   = ceil($time/(3600*24));
                    $money = $day * 0.2;
                    $data['is_past']   = 1;
                    $data['pass_time'] = $day;
                    $data['money']     = $money;
                    $list = M()->table('borrow')->where(array('id'=>$result[$i]['id']))->save($data);
                }
            }
        }
    }

    public function Verify(){
    	$config = array(    
	    	'fontSize' => 15,    // 验证码字体大小  
	    	'length'   => 4,     // 验证码位数    
	    	'useNoise' => false, // 关闭验证码杂点
    	);
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    public function verify_check($code, $id = ''){    
    	$verify = new \Think\Verify();    
    	return $verify->check($code, $id);
    }

    /* 退出登录 */
    public function logout(){
        $user['operate_id']   = session("id");
        $user['content']      = "退出系统";
        $user['operate_name'] = session("user_name");
        savelog($user);
        session(null);
        cookie("selected_menu",null);
        $this->redirect('Index/index');
    }

    public function delete_selete_url(){
        $selete_url = I("post.selete_url");
        if(empty($selete_url)){
            return false;
        }
        $old_selected = cookie("selected_menu");
        foreach($old_selected as $k => $value){
            if($value == $selete_url){
                unset($old_selected[$k]);
                unset($old_selected[$k+1]);
                cookie('selected_menu',$old_selected);
                return false;
            }
        }
    }

    public function get_city(){
        $province_id = I("post.province_id");
        if($province_id === false){
            return false;
        }
        $where['province_id'] = $province_id;
        $result = M("city")->where($where)->select();
        $string = "<option value='0'>选择市</option>";
        if(!empty($result)){
            foreach($result as $k=>$value){
                $string .= "<option value='".$value['id']."'>".$value['name']."</option>";
            }
        }
        echo  $string;
    }

    public function get_area(){
        $city_id = I("post.city_id");
        if($city_id === false){
            return false;
        }
        $where['city_id'] = $city_id;
        $result = M("area")->where($where)->select();
        $string = "<option value='0'>选择区</option>";
        if(!empty($result)){
            foreach($result as $k=>$value){
                $string .= "<option value='".$value['id']."'>".$value['name']."</option>";
            }
        }
        echo  $string;
    }

    /**
     * 记录页面的布局状态
     */
    public function change_session(){
        $status  = I("post.status");
        //$str     = I('post.str');
        session('is_del_line',$status);
        /*$str_arr = explode('[+]', $str);
        if(empty($str_arr)){
            return false;
        }
        //暂时不用
        //得到历史导航
        $old_selected = cookie("selected_menu");
        foreach($str_arr as $k1 => $value1){
            foreach($old_selected as $k2 => $value2){
                if($value2 == trim($value1)){
                    //删除需要更新的导航
                    unset($old_selected[$k2]);
                    unset($old_selected[$k2+1]);
                }
            }
        }
        //更新cookie
        cookie('selected_menu',$old_selected);*/
    }

    public function delete_saekv(){
        header("Content-type:text/html;charset=utf-8");
        $kv = new \SaeKV();
        // 初始化KVClient对象
        $ret = $kv->init();
        var_dump($ret);
        echo "<hr>";
        $ret = $kv->pkrget('', 100);
        foreach ($ret as $key => $v){    
            $ret = $kv->delete($key);
            echo "key:".$key."<br>值".$v."已被删除<hr>";
        } 
    }
}