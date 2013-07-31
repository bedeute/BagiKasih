			<div class="span3">
				<div class="thumbnail">
					<div class="sidebar-title">
						<h4 class="text-center"><?=langtext('account')?></h4>
					</div>

					<div class="tab-content" id="myTabContent">
						<ul class="nav nav-pills nav-stacked">
						<li><?=anchor('user/home', langtext('my_account'))?></li>
						<li><?=anchor('user/view_orders', langtext('order_history'))?></li>
						<li><?=anchor('user/confirm_payment_list', langtext('payment_confirmation'))?></li>
						<li><?=anchor('user/delivery_status', langtext('delivery_status'))?></li>
						<li><?=anchor('user_review', 'Review')?></li>
						</ul>
					</div>
				</div>
			</div>