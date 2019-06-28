<?php

namespace App\Http\Controllers\home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\Goodss;
use DB;

class Index extends Controller
{

    public function index(Request $request)
    {
        $res=$request->all();
        if(isset($res['find_name'])){
            $data=Goodss::where('goods_name','like','%'.$res['find_name'].'%')->paginate(2);
        }else{
            $res['find_name']='';
            $data=Goodss::paginate(4);
        }
        // dd($data);
        return view('home/index',['data'=>$data],['name'=>$res['find_name']]);
    }

    public function wish(Request $request)
    {
        $id=$request->all();
        // dd($data);
        $res=Goodss::where('id',$id)->first();
        // dd($res);
        return view('home/wishlist',['res'=>$res]);
    }

    public function buyCart(Request $request)
    {
        $id=$request->all();
        // dd($id);     
        $uid=session('id');
        // dd($uid);
        $data=DB::table('goodss')->where('id',$id)->first();
        // dd($data);
        // $info = json_decode($data, true);
        $res=DB::table('cart')->where('id',$id)->insert([
            'uid'=>$uid,
            'goods_name'=>$data->goods_name,
            'goods_id'=>$data->id,
            'goods_pic'=>$data->goods_pic,
            'goods_price'=>$data->goods_price,
            'add_time'=>time()
        ]);
       
        if($res){
            return redirect()->action('home\Index@do_buyCart');
        }
    }

    public function do_buyCart(Request $request)
    {
        $uid=session('id');
        // dd($uid);
        $where=[
            ['uid','=',$uid],
            ['status','=',1]
        ];
        $data=DB::table('cart')->where($where)->get();
        // dd($data);
        $price=DB::table('cart')->where($where)->select('goods_price')->get();
        // dd($price);
        $total=0;
        foreach($price as $k=>$v){
            // var_dump($v);
            // $prices=$v;
            // $total=array_sum($v);
            $total += $v->goods_price;
        }
        // dd($total);
        // $total=array_sum($prices);
        // dd($prices);
        // dd($total);
        return view('home/buyCart',['data'=>$data],['total'=>$total]);
        
    }


    public function order(Request $request)
    {
        //接受购物车穿过来的id
        $id=$request->get('id');
        // dd($id);
        //去除点右边拼接的，
        $id=trim($id,',');
        // dd($id);
        //接受用户id
        $uid=session('id');
        // dd($uid);
        //生成订到号
        $oid=time().rand(1000,4000).$uid;
        // dd($oid);
        //查询单价
        $where=[
            'uid'=>$uid,
            'status'=>1,
        ];
        $price=DB::table('cart')->where($where)->select('goods_price')->get();
        // dd($price);
        $pay_money=0;
        //循环遍历出来单价相加 求出总价
        foreach($price as $k=>$v){
            // var_dump($v);
            // $prices=$v;
            // $total=array_sum($v);
            $pay_money += $v->goods_price;
        }
        // dd($pay_money);
        //入库 order
        $res=DB::table('order')->insert([
            'oid'=>$oid,
            'uid'=>$uid,
            'pay_money'=>$pay_money,
            'add_time'=>time()
        ]);

        //根据商品id查询商品信息
        $id=explode(',',$id); //因为whereIn里面的参数必须是数组
        // dd($id);
        $data=DB::table('goodss')->whereIn('id',$id)->get();
        // dd($data);
        //因为查询出来的是二维数组，需要循环遍历出来
        $info=[];
        foreach($data as $v){
            $info[]=[
                'oid'=>$oid,
                'goods_id'=>$v->id,
                'goods_name'=>$v->goods_name,
                'goods_pic'=>$v->goods_pic,
                'add_time'=>time(),
                'goods_price'=>$v->goods_price                                                 
            ];
        }
        $result=DB::table('order_detail')->insert($info);
        if($result){
            return redirect()->action('home\Index@do_order',['oid'=>$oid]);
        }
    }

    public function do_order(Request $request)
    {
        $oid=$request->get('oid'); //一个值用get get接值必须要有参数 如果用all接受是数组的形式
        // dd($oid);
        $where=[
            'oid'=>$oid,
            'status'=>'1'
        ];
        $res=DB::table('order_detail')->where($where)->get();
        return view('home/order',['res'=>$res]);
    }

    //同步通知
    public function return_url(Request $request){
        $data=$request->all();
        DB::table('order')->where('oid',$data['out_trade_no'])->update(['state'=>'2']);
        DB::table('order_detail')->where('oid',$data['out_trade_no'])->update(['status'=>'2']);
        $uid=Session('id');
        DB::table('cart')->where('uid',$uid)->update(['status'=>'2']);
        return view('home/return_url',['data'=>$data]);
        
    }

    //异步通知
    public function asynUrl(Request $request)
        {
            //接收支付宝异步通知
            $data=$request->all();
            $file="/data/wwwroot/default/shop/public/notify.log";
            if (empty($data)){
                file_put_contents($file,'no data',FILE_APPEND);
            }else{
                file_put_contents($file,print_r($data,true),FILE_APPEND);
            }
        }
    
}
