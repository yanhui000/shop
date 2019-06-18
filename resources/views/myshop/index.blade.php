<form action="">
    <input type="text"  name="find_name" style="width: 200px;" placeholder="关键词" />
    <input type="submit" value="搜素">
</form>
<table border="1">
    <tr>
        <td>姓名</td>
        <td>库存数量</td>
        <td>上传图片</td>
        <td>添加时间</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
    <tr>
        <td>{{$v->name}}</td>
        <td>{{$v->num}}</td>
        <td><img src="{{$v->img}}" width="100" height="50"></td>
        <td>{{date('Y-m-d H:i:s',$v->createtime)}}</td>
        <td>
            <a href="{{url('delete')}}?id={{$v->id}}">删除</a>|
            <a href="{{url('edit')}}?id={{$v->id}}">修改</a>
        </td>
    </tr>
    @endforeach  
</table>
{{$data->appends(['find_name'=>$name])->links()}}
