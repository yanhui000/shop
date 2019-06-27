<style type="text/css">
	#div1{
		color: red;
		margin:0 auto; 
		width: 300px;
		height: 400px;
	}
</style>
<div id="div1">
	<h1>你已支付成功</h1>
	<p>你的支付金额为：{{$data['total_amount']}}</p>
	<p>你的订单号为：{{$data['out_trade_no']}}</p>
	<p>支付时间：{{$data['timestamp']}}</p>
	<a href="{{url('/home_index')}}">继续购物</a>
</div>