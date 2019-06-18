<form action="{{url('save')}}" method="post" enctype="multipart/form-data">
    <table  align="center">
    @csrf
        <tr>
            <td>商品姓名</td>
            <td><input type="text" name="name"></td>
        </tr>
        <tr>
            <td>库存数量</td>
            <td>
                <input type="text" name="num">
            </td>
        </tr>
       
        <tr>
            <td>商品图片</td>
            <td>
                <input type="file" name="img" />
                <button type="submit">上传</button>
            </td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="提交"></td>
        </tr>
    </table>
</form>