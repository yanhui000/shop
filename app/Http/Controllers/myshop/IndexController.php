<?php

namespace App\Http\Controllers\myshop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\Shop;
use DB;
// use App\Http\Tools\Tools; 

class IndexController extends Controller
{
    public $redis;
    public function __construct()
    {
        $this->redis=new \Redis();
        $this->redis->connect('127.0.0.1','6379');
    }

    public function upload()
    {
        return view('myshop/upload');

    }

    public function do_upload(Request $request)
    {
        $path = $request->file('img')->store('');
        echo asset('storage'.'/'.$path); 
        dd($path);
    }

    public function create()
    {
        return view('myshop/create');
    }

    public function save(Request $request)
    {
        // echo 123;
        $data=$request->all();
        // dd($data);
        $path = $request->file('img')->store('');
        $img=asset('storage'.'/'.$path);
        $res=Shop::insert([
            'name'=>$data['name'],
            'img'=>$img,
            'num'=>$data['num'],
            'createtime'=>time(),
        ]);
        if($res){
            return redirect()->action('myshop\IndexController@index');
        }
    }     
    
    // public $Tools;
    public function index(Request $request)
    {
    //  $redis=new \Redis();  
    //  $redis->connect('127.0.0.1','6379');
    //  $num=$redis->incr('num');
    $num=$this->redis->incr('num');
     print_r($num);

        $res=$request->all();
        // dd($res);    
        if(isset($res['find_name'])){
            $data=Shop::where('name','like','%'.$res['find_name'].'%')->paginate(3);
        }else{
            $res['find_name']='';
            $data=Shop::paginate(3);
        }
       
        return view('myshop/index',['data'=>$data],['name'=>$res['find_name']]);
    }

    public function delete(Request $request)
    {
        $data=$request->all();
        // dd($id);
        $res=Shop::where(['id'=>$data['id']])->delete();
        if($res){
            return redirect()->action('myshop\IndexController@index');
        }
    }

    public function edit(Request $request)
    {
        $data=$request->all();
        $res=Shop::where(['id'=>$data['id']])->first();
        // dd($res);
        return view('myshop/edit',['res'=>$res]);

    }

    public function update(Request $request)
    {
        $data=$request->all();
        //   dd($data);
        $path=$request->file('img');
        // dd($path);
        if($path){
            $path = $request->file('img')->store('');
            $img=asset('storage'.'/'.$path);
            $res=Shop::where(['id'=>$data['id']])->update([
                'name'=>$data['name'],
                'num'=>$data['num'],
                'img'=>$img,
            ]);
        }else{
            $res=Shop::update([
                'name'=>$data['name'],
                'num'=>$data['num'],
                'img'=>$img,
            ]);
        }
        if($res){
            return redirect()->action('myshop\IndexController@index');
        }
    }
}
