			function form5post(order_id, user_token, catalog, formType) {
				$('#form5post'+order_id).button('loading');
				$('#form5post'+order_id).attr('disabled', true);
				
				$.ajax({
					url: 'index.php?route=extension/shipping/5post/formOrder&user_token=' + user_token,
					type: 'post',
					data: $('form').serialize() + "&order_id=" + order_id + "&formType=" + formType,
					dataType: 'json',
					success: function(json) {
						$('#fivepostModal').remove();
						$('.modal-backdrop').remove();
						
						var fivepostModal = '<div class="modal fade" id="fivepostModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
						fivepostModal +=  '<div class="modal-dialog modal-lg" role="document">';
						fivepostModal +=  '	<div class="modal-content">';
						fivepostModal +=  '	  <div class="modal-header">';
						fivepostModal +=  '		<button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">';
						fivepostModal +=  '		  <span aria-hidden="true">×</span>';
						fivepostModal +=  '		</button>';
						fivepostModal +=  '		<h4 class="modal-title" id="myModalLabel">Редактирования данных для 5post</h4>';
						fivepostModal +=  '	  </div>';
						fivepostModal +=  '	  <div class="modal-body">';
						fivepostModal +=  '	   <form id="form-5post" class="form-horizontal">';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 col-form-label">Статус</label>';
						
						fivepostModal +=  '			<div class="col-sm-4">';
						if(json.fivepost_status != ''){
						fivepostModal += json.status + '</br>';
						}else if(json.ok == 'Y'){
						fivepostModal +=  'Заявка отправлена</br>';
						}else if(json.ok == 'N'){
						fivepostModal +=  'Заявка еще не отправлялась</br>';
						}
						
						if(json.message != '' && json.ok == 'N'){
						fivepostModal +=  json.message;
						}
						fivepostModal +=  '			</div>';
						
						fivepostModal +=  '			<span class="col-sm-12" style="font-size:12px;">Данные формы взяты из заказа или из базы данных модуля, если была неудачная попытка его отправить.</span>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="alert alert-info" align="center" style="margin: 0px 12px;" role="alert">';
						fivepostModal +=  '			Общие данные';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 col-form-label">Номер заказа</label>';
						fivepostModal +=  '			<div class="col-sm-4">' + json.order_id + '</div>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 col-form-label">Дата создания заказа</label>';
						fivepostModal +=  '			<div class="col-sm-4">' + json.uptime + '</div>';
						fivepostModal +=  '		</div>';
						
						if(json.barcode != ''){
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 col-form-label">Штрих-код груза партнера</label>';
						fivepostModal +=  '			<div class="col-sm-4">' + json.barcode + '</div>';
						fivepostModal +=  '		</div>';
						}
						
						if(json.ok == 'Y'){
							fivepostModal +=  '		<div class="form-group">';
							fivepostModal +=  '			<label class="col-sm-8 col-form-label">Трек номер</label>';
							fivepostModal +=  '			<div class="col-sm-4">OC_' + json.order_id + '</div>';
							fivepostModal +=  '		</div>';
						}
						
						if(json.fivepost_id != ''){
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 col-form-label">N заказа в системе fivepost</label>';
						fivepostModal +=  '			<div class="col-sm-4">' + json.fivepost_id + '</div>';
						fivepostModal +=  '		</div>';
						}
						
						/*fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 col-control-label" for="input-date-available">Плановая дата передачи заказа покупателю</label>';
						fivepostModal +=  '			<div class="col-sm-4">';
						fivepostModal +=  '			  <div class="input-group date">';
						fivepostModal +=  '				<input type="text" name="planned_receive_date" value="' +  json.planned_receive_date + '" placeholder="" data-date-format="YYYY-MM-DD" id="input-date-available" class="form-control"/> <span class="input-group-btn">';
						fivepostModal +=  '				<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>';
						fivepostModal +=  '				</span></div>';
						fivepostModal +=  '			</div>';
						fivepostModal +=  '		</div>';*/
						
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 col-form-label">Точка выдачи</label>';
						fivepostModal +=  '			<div class="col-sm-4" id="fivepost-address">' + json.point_address + '</div>';
						fivepostModal +=  '			<div class="col-sm-8"></div>';
						
						if(json.disabled == false && json.ok == 'N'){
						fivepostModal +=  '			<div class="col-sm-4" style="margin-left: -0.7%;"><a href="javascript:void(0);" style="font-size:15px;" ct="' + catalog + '" oi="' + json.order_id + '" pi="' + json.receiver_location + '" ut="' + user_token + '" id="fivepost-open-pvz">Выбрать точку</a></div>';
						}
						
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 control-label" style="text-align:left;">Склад, на котором хранится заказ</label>';
						fivepostModal +=  '			<div class="col-sm-4">';
						fivepostModal +=  '				<select name="sender_location" class="form-control">';
						
						for (var key in json.warehouses) {
							
							if (json.sender_location == json.warehouses[key].partnerId){
							fivepostModal +=  '					<option value="' + json.warehouses[key].partnerId + '" selected="selected">' + json.warehouses[key].name + '</option>';
							}else{
							fivepostModal +=  '					<option value="' + json.warehouses[key].partnerId + '">' + json.warehouses[key].name + '</option>';
							}
							
						}
						
						fivepostModal +=  '				</select>';
						fivepostModal +=  '			</div>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="alert alert-info" align="center" style="margin: 0px 12px;" role="alert">';
						fivepostModal +=  '			Особенности груза';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 control-label" style="text-align:left;">Брэнд отправителя</label>';
						fivepostModal +=  '			<div class="col-sm-4"><input class="form-control" type="text" name="brand_name" value="' + json.brand_name + '" /></div>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 control-label" style="text-align:left;">Способ обработки невостребованного заказа</label>';
						fivepostModal +=  '			<div class="col-sm-4">';
						fivepostModal +=  '				<select name="undeliverable_option" class="form-control">';
						
						if (json.undeliverable_option == 'RETURN'){
						fivepostModal +=  '					<option value="RETURN" selected="selected">Возврат на склад партнера</option>';
						fivepostModal +=  '					<option value="UTILIZATION">Утилизация</option>';
						}else{
						fivepostModal +=  '					<option value="RETURN">Возврат на склад партнера</option>';
						fivepostModal +=  '					<option value="UTILIZATION" selected="selected">Утилизация</option>';
						}
						
						fivepostModal +=  '				</select>';
						fivepostModal +=  '			</div>';
						fivepostModal +=  '		</div>';
						
						/*fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 col-control-label" for="input-date-available2">Плановая дата отгрузки заказа со склада партнера</label>';
						fivepostModal +=  '			<div class="col-sm-4">';
						fivepostModal +=  '			  <div class="input-group date">';
						fivepostModal +=  '				<input type="text" name="shipment_date" value="' +  json.shipment_date + '" placeholder="" data-date-format="YYYY-MM-DD" id="input-date-available2" class="form-control"/> <span class="input-group-btn">';
						fivepostModal +=  '				<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>';
						fivepostModal +=  '				</span></div>';
						fivepostModal +=  '			</div>';
						fivepostModal +=  '		</div>';*/
						
						fivepostModal +=  '		<div class="alert alert-info" align="center" style="margin: 0px 12px;" role="alert">';
						fivepostModal +=  '			Габариты заказа';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-3 control-label" style="text-align:left;">Размеры(мм)</label>';
						fivepostModal +=  '			<div class="col-sm-2"><input class="form-control" placeholder="Длина" type="text" name="goods[length]" value="' + json.goods.length + '" /></div>';
						fivepostModal +=  '			<label class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i></label>';
						fivepostModal +=  '			<div class="col-sm-2"><input class="form-control" placeholder="Ширина" type="text" name="goods[width]" value="' + json.goods.width + '" /></div>';
						fivepostModal +=  '			<label class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i></label>';
						fivepostModal +=  '			<div class="col-sm-2"><input class="form-control" placeholder="Высота" type="text" name="goods[height]" value="' + json.goods.height + '" /></div>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-3 control-label" style="text-align:left;">Вес(г)</label>';
						fivepostModal +=  '			<div class="col-sm-2"><input class="form-control" type="text" name="goods[weight]" value="' + json.goods.weight + '" /></div>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="alert alert-info" align="center" style="margin: 0px 12px;" role="alert">';
						fivepostModal +=  '			Данные получателя';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 control-label" style="text-align:left;">Контактное лицо</label>';
						fivepostModal +=  '			<div class="col-sm-4"><input class="form-control" type="text" id="client_name" name="client_name" value="' + json.client_name + '" /></div>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 control-label" style="text-align:left;">E-mail</label>';
						fivepostModal +=  '			<div class="col-sm-4"><input class="form-control" type="text" id="client_email" name="client_email" value="' + json.client_email + '" /></div>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 control-label" style="text-align:left;">Мобильный телефон</label>';
						fivepostModal +=  '			<div class="col-sm-4"><input class="form-control" placeholder="7999ххххххх или 999ххххххх" type="num" name="client_phone" value="' + json.client_phone + '" /></div>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="alert alert-info" align="center" style="margin: 0px 12px;" role="alert">';
						fivepostModal +=  '			Оплата';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 control-label" style="text-align:left;" for="input-is_beznal">Заказ оплачен</label>';
						fivepostModal +=  '			<div class="col-sm-3" style="margin-left: 2%;">';
						fivepostModal +=  '				<div class="checkbox">';
						
						if (json.is_beznal == 1){
						fivepostModal +=  '					<input type="checkbox" id="input-is_beznal" name="is_beznal" checked="checked" />';
						}else{
						fivepostModal +=  '					<input type="checkbox" id="input-is_beznal" name="is_beznal"/>';
						}
						
						fivepostModal +=  '				</div>';
						fivepostModal +=  '			</div>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 col-form-label">Стоимость товаров</label>';
						fivepostModal +=  '			<div class="col-sm-4" id="sumProduct">' + json.priceProduct + '</div>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 control-label" style="text-align:left;">Стоимость доставки</label>';
						
						if (json.is_beznal == 1){
						fivepostModal +=  '			<div class="col-sm-4"><input class="form-control" ' + json.disabled_param + ' type="text" id="delivery_cost" name="delivery_cost" value="0" /></div>';
						}else{
						fivepostModal +=  '			<div class="col-sm-4"><input class="form-control" type="text" id="delivery_cost" name="delivery_cost" value="' + json.delivery_cost + '" /></div>';
						}
						
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 control-label" style="text-align:left;">Способ оплаты</label>';
						fivepostModal +=  '			<div class="col-sm-4">';
						fivepostModal +=  '				<select id="payment_type"  name="payment_type" class="form-control">';
												
						for (var key in json.payments_type) {
							if(json.payments_type[key].value == 'Bill' && json.is_beznal == 1){
							fivepostModal +=  '					<option ' + json.disabled_param + ' value="' + json.payments_type[key].value + '" selected="selected">' + json.payments_type[key].name + '</option>';
							}else if (json.payment_type == json.payments_type[key].value){
							fivepostModal +=  '					<option value="' + json.payments_type[key].value + '" selected="selected">' + json.payments_type[key].name + '</option>';
							}else if(json.payments_type[key].value == 'Bill'){
							fivepostModal +=  '					<option disabled="disabled" value="' + json.payments_type[key].value + '">' + json.payments_type[key].name + '</option>';
							}else{
							fivepostModal +=  '					<option value="' + json.payments_type[key].value + '">' + json.payments_type[key].name + '</option>';
							}
							
						}
						
						fivepostModal +=  '				</select>';
						fivepostModal +=  '			</div>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="alert alert-info" align="center" style="margin: 0px 12px;" role="alert">';
						fivepostModal +=  '			Управление товарами';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-3 control-label" style="text-align:left;">Товар</label>';
						fivepostModal +=  '			<label class="col-sm-3 control-label" style="text-align:left;">Артикул</label>';
						fivepostModal +=  '			<label class="col-sm-2 control-label" style="text-align:left;">Цена</label>';
						fivepostModal +=  '			<label class="col-sm-2 control-label" style="text-align:left;">Количество</label>';
						fivepostModal +=  '			<label class="col-sm-2 control-label" style="text-align:left;">Ставка НДС(%)</label>';
						fivepostModal +=  '		</div>';
												
						for (var key in json.items) {
							fivepostModal +=  '		<div class="form-group">';
							fivepostModal +=  '			<div class="col-sm-3">' + json.items[key].name + '</div>';
							fivepostModal +=  '			<div class="col-sm-3"><input class="form-control" style="margin-top:-4%; margin-left: -2%;" type="text" name="items[' + key + '][sku]" value="' + json.items[key].sku + '" /></div>';
							fivepostModal +=  '			<div class="col-sm-2">' + json.items[key].price + '</div>';
							fivepostModal +=  '			<div class="col-sm-2">' + json.items[key].quantity + '</div>';
							fivepostModal +=  '			<div class="col-sm-2"><input class="form-control" style="margin-top:-8%;" type="number" name="items[' + key + '][nds]" value="' + json.items[key].nds + '" /></div>';
							
							fivepostModal +=  '		</div>';
						}
						
						fivepostModal +=  '		<hr>';
						fivepostModal +=  '		<div class="form-group">';
						fivepostModal +=  '			<label class="col-sm-8 control-label" style="text-align:left;">Объявленная стоимость товара</label>';
						fivepostModal +=  '			<div class="col-sm-4">' + json.price + '</div>';
						fivepostModal +=  '		</div>';
						fivepostModal +=  '	   </form>';
						fivepostModal +=  '	  </div>';
						fivepostModal +=  '	  <div class="modal-footer">';
						
						if(json.ok == 'Y' && json.fivepost_status != 'canceled'){
						fivepostModal +=  '		<button type="button" id="checkStatus5post" class="btn btn-secondary">Проверить статус</button>';
						fivepostModal +=  '		<a target="_blank" href="index.php?route=extension/shipping/5post/getSticker&user_token=' + user_token + '&order_id=OC_' + json.order_id + '&fivepost_id=' + json.fivepost_id + '" type="button" id="printSticker" class="btn btn-secondary">Печать наклейки</a>';
						}
						
						fivepostModal +=  '		<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>';
						
						if(json.ok == 'N'){
						fivepostModal +=  '		<button type="button" id="send5post" class="btn btn-success">Отправить</button>';
						fivepostModal +=  '		<button type="button" id="save5post" class="btn btn-primary">Сохранить</button>';
						}else if(json.fivepost_status != 'canceled'){
						fivepostModal +=  '		<button type="button" id="cancel5post" class="btn btn-danger">Отменить</button>';
						}
						
						fivepostModal +=  '	  </div>';
						fivepostModal +=  '	</div>';
						fivepostModal +=  '  </div>';
						fivepostModal +=  '</div>';
						
						$('body').append(fivepostModal);
																		
						setTimeout(function() {
							
							$('#fivepostModal').modal('show');
							
							$('#form5post'+order_id).button('reset');
							$("#send5post").button('reset');
							$("#save5post").button('reset');
							$("#cancel5post").button('reset');
							$("#checkStatus5post").button('reset');
							
							$('#form5post'+order_id).attr('disabled', false);
							$('#send5post').attr('disabled', false);
							$('#save5post').attr('disabled', false);
							$('#cancel5post').attr('disabled', false);
							$('#checkStatus5post').attr('disabled', false);
							
											
							if(json.ok == 'Y'){
								$('#fivepostModal .modal-body :input').attr('disabled', true);
							}else{
								$('#fivepostModal .modal-body :input').attr('disabled', false);
							}
							
							if(json.disabled == true){
								$('#send5post').attr('disabled', true);
								$('#save5post').attr('disabled', true);
							}
							
							$('.date').datetimepicker({
								language: 'ru',
								pickTime: false
							})
							
						}, 500);
												
						setTimeout(function() {
							if ($('#input-is_beznal').is(':checked')){
								$('#delivery_cost').attr('disabled', true);
								$('#payment_type').attr('disabled', true);
							}
							
						}, 1000);
						
						if (typeof json.error !== 'undefined') {
							alert(json.error);
						}
						
						$("#input-is_beznal").click(function() {
							if ($('#input-is_beznal').is(':checked')){
								$('#sumProduct').text('0');
								
								$('#delivery_cost').attr('disabled', true);
								$('#delivery_cost').val(0);
								
								$('#payment_type').attr('disabled', true);
								$('#payment_type').val('Bill');
							}else{
								$('#sumProduct').text(json.price);
								
								$('#delivery_cost').attr('disabled', false);
								$('#delivery_cost').val(json.delivery_cost);
								
								$('#payment_type').attr('disabled', false);
								$('#payment_type').val(json.payment_type);
							}
							
							
							if(json.point_address != ''){
								/*$.post("index.php?route=extension/shipping/5post/validPointPayment&user_token=" + user_token, {order_id: order_id}).done(function (data) {
									if (typeof json.error !== 'undefined') {
										alert(json.error);
									}
								})*/
							}
						});
						
						$('#payment_type').on('change', function (e) {
							if(json.point_address != ''){
								var valueSelected = this.value;
								
								$.post("index.php?route=extension/shipping/5post/validPointPayment&user_token=" + user_token, {
									order_id: order_id, payment_type: valueSelected, receiver_location: json.receiver_location
								}).done(function (data) {
									
									if (typeof data.error !== 'undefined') {
										alert(data.error);
									}
								})
							}
						});
						
						$("#save5post").click(function() {
							form5post(order_id, user_token, catalog, 'save');
							$("#save5post").button('loading');
							$('#save5post').attr('disabled', false);
						});
																		
						$("#send5post").click(function() {
							form5post(order_id, user_token, catalog, 'send');
							$("#send5post").button('loading');
								
							$('#send5post').attr('disabled', false);
							$('#fivepostModal .modal-body :input').attr('disabled', true);
						});
						
						$("#cancel5post").click(function() {
							form5post(order_id, user_token, catalog, 'cancel');
							$("#cancel5post").button('loading');
							$('#cancel5post').attr('disabled', false);
						});
						
						$("#checkStatus5post").click(function() {  
							form5post(order_id, user_token, catalog, 'checkStatus');
							$("#checkStatus").button('loading');
							$('#checkStatus').attr('disabled', false);
						});
					}
				});
			}