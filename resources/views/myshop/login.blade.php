<form action="{{url('do_login')}}" method="post">
<h4 align="center">登录</h4>
    <table border="1" align="center">
    @csrf
        <tr>
            <td>账号</td>
            <td><input type="text" name="name"></td>
        </tr>
        <tr>
            <td>密码</td>
            <td><input type="password" name="pwd"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit"  value="登录">
            </td>
        </tr>
    </table>
</form>