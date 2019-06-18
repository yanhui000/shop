<form action="{{url('update')}}" method="post" enctype="multipart/form-data">
    <table   align="center">
    @csrf
        <tr>
            <td>商品姓名</td>
            <td><input type="text" name="name" value="{{$res->name}}"></td>
        </tr>
        <tr>
            <td>库存数量</td>
            <td>
                <input type="text" name="num" value="{{$res->num}}">
            </td>
        </tr>
       
        <tr>
            <td>商品图片</td>
            <td>
                <input type="file" name="img" />
                <img src="{{$res->img}}" alt="" width="100" height="50">
                <button type="submit">上传</button>
            </td>
        </tr>
       <input type="hidden" name="id" value="{{$res->id}}">
        <tr>
            <td colspan="2"><input type="submit" value="提交"></td>
        </tr>
    </table>
</form>