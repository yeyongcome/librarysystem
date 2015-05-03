<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller{
    
    //初始化控制器，加载权限，判断登陆
    public function _initialize(){
        //设置默认导航栏状态
        if(!session('?is_del_line')){
            session('is_del_line',C("LINE_STATUS")); 
        }
        if(session('id') == null || session('id') == ''){
            session('id',1);//游客
            session('type',1);//游客
            session('user_name',"admin1");
            cookie("selected_menu",null);//清空cookie
        }
        $type        = session('type');
        $base_model  = D('Base'); 
        //得到顶级导航
        $menu        = $base_model->get_top_menu($type); 
        $top_menu    = $this->change_url($menu);
        $child_menu     = $base_model->get_child_menu($type); 
        $get_child_menu = $this->change_par_chi($menu,$child_menu);
        
        $top_url        = CONTROLLER_NAME."/".ACTION_NAME;
        $selected_menu  = $this->get_selected_menu($child_menu,$top_url);
        $this->assign('controller',CONTROLLER_NAME);
        $this->assign('children_color',$top_url); 
        //选中的url
        $this->assign('selected_menu',$selected_menu['url']);
        //选择过的url
        $this->assign('all_menu',$this->get_all_menu($selected_menu,$child_menu));
        $this->assign('top_menu',$top_menu);
        $this->assign('child_menu',$get_child_menu);
    }

    //得到导航栏
    public function get_all_menu($selected_menu,$child_menu){
        //得到原来的数据
        //cookie("is_del_line",null);
        $status = 0;//状态参量
        foreach($child_menu as $k => $value){
            //判断url是否是要加入的合法url
            if($value['url'] == $selected_menu['url'] && $value['type'] == 'menu'){
                //参量加1，同时跳出循环
                $status++;
                break;
            }
        }
        //得到原来的导航信息
        $old_selected = cookie("selected_menu");
        //如果是合法导航url则加入
        if($status > 0){
            //判断原导航时是否是空
            if(empty($old_selected)){
                $old_selected = array();
            }
            //判断是否已经存在
            if(!in_array($selected_menu['url'],$old_selected)){
                //原来不存在就加入到数组中
                array_push($old_selected,$selected_menu['url'],$selected_menu['name']);
                cookie('selected_menu',$old_selected);
            }
        }
        return array_chunk($old_selected,2); 
    } 

    //得到选择的url
    public function get_selected_menu($child_menu,$top_url){
        foreach($child_menu as $k => $value){
            if($value['url'] == $top_url){
                return $child_menu[$k];
            }
        }
    }
    /*
    	检验该用户是否具有访问指定url的权限
    	ret表示是否需要返回值，0表示不需要，直接跳转到无权限页面，1表示返回值
     */
    public function check_power($ret = 0 , $url = null,$now_child_url = null){
    	if(empty($url)){
			$url = MODULE_NAME.'/'.CONTROLLER_NAME."/".ACTION_NAME;
		}
		$type = session('type');
		if($type == C("ADMIN_TYPE")){
			return 1;
		}else{
			$base_model = D('Base');
			$result = $base_model->get_power($type,$url,$now_child_url);	
		}
		if($ret != 0){
			return $result;
		}
    }
    /**
     *	生成页面左边的导航和子导航
     */
    public function change_par_chi($menu,$child_menu){
        $list = array();   		//存放二级导航
		//该一级导航下的所有子导航
		$menu_count = count($menu); 
		$child_menu_count = count($child_menu);  
		$nums = 0;
		for($m = 0;$m < $menu_count;$m++){
			for($n = 0;$n < $child_menu_count ;$n++){
				if($menu[$m]['id'] == $child_menu[$n]['parent_id']){

					$menu[$m]['children'][$nums++] = $child_menu[$n];
				}
			}
			$nums = 0;
		}
		return $menu; 	 //该数组的一维键等于二级导航
    }

    /**
     * 改变主导航的url(添加index)
     */
   	public function change_url($top_menu){
   		$top_menu_count = count($top_menu);
   		for($i = 0 ;$i < $top_menu_count;$i++){
   			$top_menu[$i]['oldurl'] = $top_menu[$i]['url'];
   			$top_menu[$i]['url'] = $top_menu[$i]['url'].'/index';
   		}
   		return $top_menu;
   	}

    /*
        得到当前url
     */
    public function get_url(){
         $now_url = MODULE_NAME.'/'.CONTROLLER_NAME."/".ACTION_NAME;
         return $now_url;
    }

}