<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class BorrowingController extends BaseController {
    public function index(){
	    $p         = I('get.p');
        $book_name = I('get.book_name');
        if(empty($p)){
            $p = 1;
        }
        if(!empty($book_name)){
            $content['bi.name'] = array('like','%'.$book_name.'%');
        }
        $content['bi.is_del'] = 0;
        $content['b.user_id'] = session('id');
        $content['b.is_back'] = 0;
        $list_rows = C('LIST_ROWS');
        $startPage = ($p-1) * $list_rows;
        $total_num = M('book_info bi')
                   ->join('book_type bt on bi.type_id = bt.id')
                   ->join('borrow b on b.book_id = bi.id')
                   ->where($content)
                   ->count();
    	$result    = M('book_info bi')
                   ->field('bi.name,b.*,bt.name as type_name')
                   ->join('book_type bt on bi.type_id = bt.id')
                   ->join('borrow b on b.book_id = bi.id')
                   ->where($content)
                   ->limit($startPage,$list_rows)
                   ->order("create_time desc")
                   ->select();
         //var_dump($result);
    	$page      = new \Think\Page($total_num, $list_rows);
        $this->assign('_page', $page->show($p));
    	$this->assign('result',$result);
        $this->assign('content',I("get."));
	    $this->display();
    }

     public function history(){
	    $p         = I('get.p');
        $book_name = I('get.book_name');
        if(empty($p)){
            $p = 1;
        }
        if(!empty($book_name)){
            $content['bi.name'] = array('like','%'.$book_name.'%');
        }
        $content['bi.is_del'] = 0;
        $content['b.user_id'] = session('id');
        $content['b.is_back'] = 1;
        $list_rows = C('LIST_ROWS');
        $startPage = ($p-1) * $list_rows;
        $total_num = M('book_info bi')
                   ->join('book_type bt on bi.type_id = bt.id')
                   ->join('borrow b on b.book_id = bi.id')
                   ->where($content)
                   ->count();
    	$result    = M('book_info bi')
                   ->field('bi.name,b.*,bt.name as type_name')
                   ->join('book_type bt on bi.type_id = bt.id')
                   ->join('borrow b on b.book_id = bi.id')
                   ->where($content)
                   ->limit($startPage,$list_rows)
                   ->order("create_time desc")
                   ->select();
         //var_dump($result);
    	$page      = new \Think\Page($total_num, $list_rows);
        $this->assign('_page', $page->show($p));
    	$this->assign('result',$result);
        $this->assign('content',I("get."));
	    $this->display();
    }

    public function back_book(){
        $id = I('post.id');
        if(empty($id)){
            return false;
        }
        $where['back_time'] = time();
        $where['is_back']   = 1;
        $data['is_checkout'] = 0;
        $list = M()->table('borrow')->where(array('id'=>$id))->find();
        M()->table('book_info')->where(array('id'=>$list['book_id']))->save($data);
        $result = M()->table('borrow')->where(array('id'=>$id))->save($where);
        if(empty($result)){
            return false;
        }
        echo $result;
    }
}