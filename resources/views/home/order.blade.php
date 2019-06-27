@extends('layouts.home')

@section('title', '商品登录')

@section('content')
<div class="checkout pages section">
		<div class="container">
			<div class="pages-head">
				<h3>CHECKOUT</h3>
			</div>
			<div class="checkout-content">
				<div class="row">
					<div class="col s12">
						<ul class="collapsible" data-collapsible="accordion">
							<li>
								<div class="collapsible-header"><h5>1 - 订单详情</h5></div>
								<div class="collapsible-body">
									<div class="order-review">
										<div class="row">
											<div class="col s12">
												@foreach($res as $v)
												<div class="cart-details">
													<div class="col s5">
														<div class="cart-product">
															<h5>{{$v->goods_name}}</h5>
														</div>
													</div>
													<div class="col s7">
														<div class="cart-product">
															<span>${{$v->goods_price}}</span>
														</div>
													</div>
												</div>
												@endforeach
											</div>
                                            
										</div>
									</div>
									<div class="order-review final-price">
										
										<a href="{{url('pay')}}?id={{$res['0']->oid}}" class="btn button-default button-fullwidth">CONTINUE</a>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div> 
@endsection	