<?php
# Разработчик: Кузнецов Богдан	
# ipol.ru
# 5post - служба доставки

class ControllerExtensionShipping5post extends Controller {
	private $error;
	protected $obWarehouse = false;
	
	public function index() {
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
				
		$this->load->language('extension/shipping/5post');
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('extension/shipping/5post');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_5post', $this->request->post);

			$this->session->data['success'] = 'Настройки успешно изменены!';
			
			$this->response->redirect($this->url->link('extension/shipping/5post', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
		}
							
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Доставки',
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/5post', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		# links
		$data['action']   = $this->url->link('extension/shipping/5post', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel']   = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);
		$data['import']   = $this->url->link('extension/shipping/5post/importDel', 'user_token=' . $this->session->data['user_token'], true);
		$data['orders']   = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['user_token'] = $this->session->data['user_token'];
		
		/* Способы оплаты */
		$results_payment = $this->model_extension_shipping_5post->getExtensions('payment');
		
		foreach ($results_payment as $result) {
			if ($this->config->get('payment_' . $result['code'] . '_status')) {
				$title = $this->getPayment($result['code']);
				
				$method_data[] = array(
					'code'		 => $result['code'],
					'sort_order' => $this->config->get('payment_' . $result['code'] . '_sort_order'),
					'title'		 => $title
				);
			}
		}
		
		$sort_order = array();

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);
		
		$data['payment_methods'] = $method_data;
		
		$test = $this->config->get('shipping_5post_test') ? 1 : 0;
		
		# Tax list
		$data['tax_list'] = array();
		$data['tax_list'][0]['value'] = 0;
		$data['tax_list'][0]['name'] = 'Без НДС';
		$data['tax_list'][1]['value'] = 20;
		$data['tax_list'][1]['name'] = '20%';
		$data['tax_list'][2]['value'] = 10;
		$data['tax_list'][2]['name'] = '10%';
		
		# rate type list
		$data['rateType_list'] = null;
		$rateType = $this->model_extension_shipping_5post->getRateType();
		
		if(!empty($rateType)){
			$data['rateType_list'] = array();
			$data['rateType_list'][0]['value'] = 'MIN_PRICE';
			$data['rateType_list'][0]['name'] = 'Автовыбор (минимальная стоимость)';
			
			foreach($rateType as $key => $row){
				$key = $key + 1;
				
				$data['rateType_list'][$key]['value'] = $row['rateType'];
				$data['rateType_list'][$key]['name'] = $row['rateType'];
			}
		}
		
		# federaldistrict
		$data['wh_federaldistrict'][] = 'Выберите ФО';
		$data['wh_federaldistrict'][] = 'Центральный федеральный округ';
		$data['wh_federaldistrict'][] = 'Северо-Западный федеральный округ';
		$data['wh_federaldistrict'][] = 'Южный федеральный округ';
		$data['wh_federaldistrict'][] = 'Северо-Кавказский федеральный округ';
		$data['wh_federaldistrict'][] = 'Приволжский федеральный округ';
		$data['wh_federaldistrict'][] = 'Уральский федеральный округ';
		$data['wh_federaldistrict'][] = 'Сибирский федеральный округ';
		$data['wh_federaldistrict'][] = 'Дальневосточный федеральный округ';
		
		# regions
		$data['wh_regions'] = $this->model_extension_shipping_5post->getWhRegions();
		
		# Склад
		$data['warehouse'] = $this->model_extension_shipping_5post->getWarehouse($test);
		
		if(!empty($data['warehouse'])){
			$data['warehouse']['worktime'] = unserialize($data['warehouse']['worktime']);
			$data['warehouse']['region'] = $data['wh_regions'][$data['warehouse']['region']];
			$data['warehouse']['federaldistrict'] = $data['wh_federaldistrict'][$data['warehouse']['federaldistrict']];
		}else{
			$data['warehouse'] = null;
		}
		
		# timezone
		$data['wh_timezone'][] = "+02:00";
		$data['wh_timezone'][] = "+03:00";
		$data['wh_timezone'][] = "+04:00";
		$data['wh_timezone'][] = "+05:00";
		$data['wh_timezone'][] = "+06:00";
		$data['wh_timezone'][] = "+07:00";
		$data['wh_timezone'][] = "+08:00";
		$data['wh_timezone'][] = "+09:00";
		$data['wh_timezone'][] = "+10:00";
		$data['wh_timezone'][] = "+11:00";
		$data['wh_timezone'][] = "+12:00";
		
		# workTime
		$data['wh_worktime'][] = "00:00";
		$data['wh_worktime'][] = "01:00";
		$data['wh_worktime'][] = "02:00";
		$data['wh_worktime'][] = "03:00";
		$data['wh_worktime'][] = "04:00";
		$data['wh_worktime'][] = "05:00";
		$data['wh_worktime'][] = "06:00";
		$data['wh_worktime'][] = "07:00";
		$data['wh_worktime'][] = "08:00";
		$data['wh_worktime'][] = "09:00";
		$data['wh_worktime'][] = "10:00";
		$data['wh_worktime'][] = "11:00";
		$data['wh_worktime'][] = "12:00";
		$data['wh_worktime'][] = "13:00";
		$data['wh_worktime'][] = "14:00";
		$data['wh_worktime'][] = "15:00";
		$data['wh_worktime'][] = "16:00";
		$data['wh_worktime'][] = "17:00";
		$data['wh_worktime'][] = "18:00";
		$data['wh_worktime'][] = "19:00";
		$data['wh_worktime'][] = "20:00";
		$data['wh_worktime'][] = "21:00";
		$data['wh_worktime'][] = "22:00";
		$data['wh_worktime'][] = "23:00";
		$data['wh_worktime'][] = "24:00";
		
		# Heading_title
		$data['heading_title']		= $this->language->get('heading_title');
						
		# Success
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		# Errors
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['name_pvz'])) {
			$data['error_name_pvz'] = $this->error['name_pvz'];
		} else {
			$data['error_name_pvz'] = '';
		}
		
		if (isset($this->error['client_id'])) {
			$data['error_client_id'] = $this->error['client_id'];
		} else {
			$data['error_client_id'] = '';
		}
                
        if (isset($this->request->post['shipping_5post_test'])) {
			$data['shipping_5post_test'] = $this->request->post['shipping_5post_test'];
		} else {
			$data['shipping_5post_test'] = $this->config->get('shipping_5post_test');
		}
		
		# Основные настройки
		if (isset($this->request->post['shipping_5post_client_id'])) {
			$data['shipping_5post_client_id'] = $this->request->post['shipping_5post_client_id'];
		} else {
			$data['shipping_5post_client_id'] = $this->config->get('shipping_5post_client_id');
		}
		
		if (isset($this->request->post['shipping_5post_name_pvz'])) {
			$data['shipping_5post_name_pvz'] = $this->request->post['shipping_5post_name_pvz'];
		} elseif($this->config->get('shipping_5post_name_pvz')) {
			$data['shipping_5post_name_pvz'] = $this->config->get('shipping_5post_name_pvz');
		}else{
            $data['shipping_5post_name_pvz'] = "Fivepost";
        }
		
		if (isset($this->request->post['shipping_5post_status'])) {
			$data['shipping_5post_status'] = $this->request->post['shipping_5post_status'];
		} else {
			$data['shipping_5post_status'] = $this->config->get('shipping_5post_status');
		}
		
		if (isset($this->request->post['shipping_5post_sort_order'])) {
			$data['shipping_5post_sort_order'] = $this->request->post['shipping_5post_sort_order'];
		} else {
			$data['shipping_5post_sort_order'] = $this->config->get('shipping_5post_sort_order');
		}
		
		# Общие настройки
		if (isset($this->request->post['shipping_5post_increase'])) {
			$data['shipping_5post_increase'] = $this->request->post['shipping_5post_increase'];
		} else {
			$data['shipping_5post_increase'] = $this->config->get('shipping_5post_increase');
		}
		
		if (isset($this->request->post['shipping_5post_display_orders'])) {
			$data['shipping_5post_display_orders'] = $this->request->post['shipping_5post_display_orders'];
		} else {
			$data['shipping_5post_display_orders'] = $this->config->get('shipping_5post_display_orders');
		}
                
        if (isset($this->request->post['shipping_5post_brand'])) {
			$data['shipping_5post_brand'] = $this->request->post['shipping_5post_brand'];
		} else {
			$data['shipping_5post_brand'] = $this->config->get('shipping_5post_brand');
		}
                
        if (isset($this->request->post['shipping_5post_undeliverableOption'])) {
			$data['shipping_5post_undeliverableOption'] = $this->request->post['shipping_5post_undeliverableOption'];
		} else {
			$data['shipping_5post_undeliverableOption'] = $this->config->get('shipping_5post_undeliverableOption');
		}
		
		if (isset($this->request->post['shipping_5post_tax'])) {
			$data['shipping_5post_tax'] = $this->request->post['shipping_5post_tax'];
		} else {
			$data['shipping_5post_tax'] = $this->config->get('shipping_5post_tax');
		}
		
		if (isset($this->request->post['shipping_5post_nds'])) {
			$data['shipping_5post_nds'] = $this->request->post['shipping_5post_nds'];
		} else {
			$data['shipping_5post_nds'] = $this->config->get('shipping_5post_nds');
		}
		
		# Габариты по-умолчанию
		if (isset($this->request->post['shipping_5post_weightD'])) {
			$data['shipping_5post_weightD'] = $this->request->post['shipping_5post_weightD'];
		} else {
			$data['shipping_5post_weightD'] = $this->config->get('shipping_5post_weightD');
		}
		
		if (isset($this->request->post['shipping_5post_lenghtD'])) {
			$data['shipping_5post_lenghtD'] = $this->request->post['shipping_5post_lenghtD'];
		} else {
			$data['shipping_5post_lenghtD'] = $this->config->get('shipping_5post_lenghtD');
		}
		
		if (isset($this->request->post['shipping_5post_widthD'])) {
			$data['shipping_5post_widthD'] = $this->request->post['shipping_5post_widthD'];
		} else {
			$data['shipping_5post_widthD'] = $this->config->get('shipping_5post_widthD');
		}
		
		if (isset($this->request->post['shipping_5post_heightD'])) {
			$data['shipping_5post_heightD'] = $this->request->post['shipping_5post_heightD'];
		} else {
			$data['shipping_5post_heightD'] = $this->config->get('shipping_5post_heightD');
		}
		
		# Настройки калькуляции доставки
		if (isset($this->request->post['shipping_5post_baseRate'])) {
			$data['shipping_5post_baseRate'] = $this->request->post['shipping_5post_baseRate'];
		} elseif($this->config->get('shipping_5post_baseRate')) {
			$data['shipping_5post_baseRate'] = $this->config->get('shipping_5post_baseRate');
		}else{
			$data['shipping_5post_baseRate'] = 3;
		}
		
		if (isset($this->request->post['shipping_5post_overweight'])) {
			$data['shipping_5post_overweight'] = $this->request->post['shipping_5post_overweight'];
		} elseif($this->config->get('shipping_5post_overweight')) {
			$data['shipping_5post_overweight'] = $this->config->get('shipping_5post_overweight');
		}else{
			$data['shipping_5post_overweight'] = 1;
		}
                
        if (isset($this->request->post['shipping_5post_pvz'])) {
			$data['shipping_5post_pvz'] = $this->request->post['shipping_5post_pvz'];
		} else {
			$data['shipping_5post_pvz'] = $this->config->get('shipping_5post_pvz');
		}
		
		if (isset($this->request->post['shipping_5post_rateType'])) {
			$data['shipping_5post_rateType'] = $this->request->post['shipping_5post_rateType'];
		} else {
			$data['shipping_5post_rateType'] = $this->config->get('shipping_5post_rateType');
		}
		
		# Настройки калькуляции доставки
		if (isset($this->request->post['shipping_5post_cash_payment'])) {
			$data['shipping_5post_cash_payment'] = $this->request->post['shipping_5post_cash_payment'];
		} else {
			$data['shipping_5post_cash_payment'] = $this->config->get('shipping_5post_cash_payment');
		}
		
		if (isset($this->request->post['shipping_5post_card_payment'])) {
			$data['shipping_5post_card_payment'] = $this->request->post['shipping_5post_card_payment'];
		} else {
			$data['shipping_5post_card_payment'] = $this->config->get('shipping_5post_card_payment');
		}
		
		if (isset($this->request->post['shipping_5post_numberPartner'])) {
			$data['shipping_5post_numberPartner'] = $this->request->post['shipping_5post_numberPartner'];
		} else {
			$data['shipping_5post_numberPartner'] = $this->config->get('shipping_5post_numberPartner');
		}
		
		if (isset($this->request->post['shipping_5post_markup'])) {
			$data['shipping_5post_markup'] = $this->request->post['shipping_5post_markup'];
		} else {
			$data['shipping_5post_markup'] = $this->config->get('shipping_5post_markup');
		}
		
		if (isset($this->request->post['shipping_5post_markup_type'])) {
			$data['shipping_5post_markup_type'] = $this->request->post['shipping_5post_markup_type'];
		} else {
			$data['shipping_5post_markup_type'] = $this->config->get('shipping_5post_markup_type');
		}
				
		#Обратная связь(Статус)
		if (isset($this->request->post['shipping_5post_status_new'])) {
			$data['shipping_5post_status_new'] = $this->request->post['shipping_5post_status_new'];
		} else {
			$data['shipping_5post_status_new'] = $this->config->get('shipping_5post_status_new');
		}
		
		if (isset($this->request->post['shipping_5post_status_valid'])) {
			$data['shipping_5post_status_valid'] = $this->request->post['shipping_5post_status_valid'];
		} else {
			$data['shipping_5post_status_valid'] = $this->config->get('shipping_5post_status_valid');
		}
		
		if (isset($this->request->post['shipping_5post_status_rejected'])) {
			$data['shipping_5post_status_rejected'] = $this->request->post['shipping_5post_status_rejected'];
		} else {
			$data['shipping_5post_status_rejected'] = $this->config->get('shipping_5post_status_rejected');
		}
		
		if (isset($this->request->post['shipping_5post_status_warehouse'])) {
			$data['shipping_5post_status_warehouse'] = $this->request->post['shipping_5post_status_warehouse'];
		} else {
			$data['shipping_5post_status_warehouse'] = $this->config->get('shipping_5post_status_warehouse');
		}
		
		if (isset($this->request->post['shipping_5post_status_inpostamat'])) {
			$data['shipping_5post_status_inpostamat'] = $this->request->post['shipping_5post_status_inpostamat'];
		} else {
			$data['shipping_5post_status_inpostamat'] = $this->config->get('shipping_5post_status_inpostamat');
		}
		
		if (isset($this->request->post['shipping_5post_status_interrupted'])) {
			$data['shipping_5post_status_interrupted'] = $this->request->post['shipping_5post_status_interrupted'];
		} else {
			$data['shipping_5post_status_interrupted'] = $this->config->get('shipping_5post_status_interrupted');
		}
		
		if (isset($this->request->post['shipping_5post_status_lost'])) {
			$data['shipping_5post_status_lost'] = $this->request->post['shipping_5post_status_lost'];
		} else {
			$data['shipping_5post_status_lost'] = $this->config->get('shipping_5post_status_lost');
		}
		
		if (isset($this->request->post['shipping_5post_status_reclaim'])) {
			$data['shipping_5post_status_reclaim'] = $this->request->post['shipping_5post_status_reclaim'];
		} else {
			$data['shipping_5post_status_reclaim'] = $this->config->get('shipping_5post_status_reclaim');
		}
		
		if (isset($this->request->post['shipping_5post_status_repickup'])) {
			$data['shipping_5post_status_repickup'] = $this->request->post['shipping_5post_status_repickup'];
		} else {
			$data['shipping_5post_status_repickup'] = $this->config->get('shipping_5post_status_repickup');
		}
		
		if (isset($this->request->post['shipping_5post_status_unclaimed'])) {
			$data['shipping_5post_status_unclaimed'] = $this->request->post['shipping_5post_status_unclaimed'];
		} else {
			$data['shipping_5post_status_unclaimed'] = $this->config->get('shipping_5post_status_unclaimed');
		}
		
		if (isset($this->request->post['shipping_5post_status_done'])) {
			$data['shipping_5post_status_done'] = $this->request->post['shipping_5post_status_done'];
		} else {
			$data['shipping_5post_status_done'] = $this->config->get('shipping_5post_status_done');
		}
		
		if (isset($this->request->post['shipping_5post_status_canceled'])) {
			$data['shipping_5post_status_canceled'] = $this->request->post['shipping_5post_status_canceled'];
		} else {
			$data['shipping_5post_status_canceled'] = $this->config->get('shipping_5post_status_canceled');
		}
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
										
		$data['header'] 	 = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] 	 = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/5post', $data));
	}
	
	public function generateWarehouseListCollection() {
		
		$this->load->model('extension/shipping/5post');
		
		$app = $this->getApp();
				
		$json = array();
		
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		
		if(isset($this->request->post['warehouse'])){
			# validate
			if(empty($this->request->post['warehouse']['name'])){
				$json['error'] = '"Наименование склада" не может быть пустым!';
			}
			
			if(empty($this->request->post['warehouse']['partnerId'])){
				$json['error'] = '"ID расположения в системе партнера" не может быть пустым!';
			}
			
			if($this->request->post['warehouse']['region'] == 0){
				$json['error'] = 'Регион не был выбран!';
			}
			
			if($this->request->post['warehouse']['federaldistrict'] == 0){
				$json['error'] = 'Область не была выбрана!';
			}
			
			if(empty($this->request->post['warehouse']['zip'])){
				$json['error'] = '"Почтовый индекс" не может быть пустым!';
			}
			
			if(empty($this->request->post['warehouse']['city'])){
				$json['error'] = '"Наименование города" не может быть пустым!';
			}
			
			if(empty($this->request->post['warehouse']['street'])){
				$json['error'] = '"Наименование улицы" не может быть пустым!';
			}
			
			if(empty($this->request->post['warehouse']['house'])){
				$json['error'] = '"Номер дома" не может быть пустым!';
			}
			
			if(empty($this->request->post['warehouse']['coordsX']) or empty($this->request->post['warehouse']['coordsY'])){
				$json['error'] = '"Географические координаты" не могут быть пустыми!';
			}
			
			if(empty($this->request->post['warehouse']['phone'])){
				$json['error'] = '"Контактный телефон" не может быть пустым!';
			}
									
			if(!isset($json['error'])){
				# regions
				$data['wh_regions'] = $this->model_extension_shipping_5post->getWhRegions();
				$wtArray = array();
				
				//warehouseId каждый раз нужен новый, даже в тестовом контуре - иначе напомнят, что такой уже создан. Пример валидного id 'Warehouse_125'
				$obWorkingTimeCollection = new \Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WorkingTimeList(); //формируем объект расписания работы
				
				if(isset($this->request->post['warehouse']['worktime1open']) && isset($this->request->post['warehouse']['worktime1close'])){
					$obWorkingTimeElem1 = new \Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WorkingTime(); //формируем объект для одного дня
					$obWorkingTimeElem1->setDayNumber(1) //порядковый номер дня работы склада
					->setTimeFrom($this->request->post['warehouse']['worktime1open']) //Время открытия работы
					->setTimeTill($this->request->post['warehouse']['worktime1close']); //Время закрытия работы
					
					$wtArray[1]['worktimeOpen'] = $this->request->post['warehouse']['worktime1open'];
					$wtArray[1]['worktimeClose'] = $this->request->post['warehouse']['worktime1close'];
					
					$obWorkingTimeCollection->add($obWorkingTimeElem1);
				}
				
				if(isset($this->request->post['warehouse']['worktime2open']) && isset($this->request->post['warehouse']['worktime2close'])){
					$obWorkingTimeElem2 = new \Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WorkingTime(); //формируем объект для одного дня
					$obWorkingTimeElem2->setDayNumber(2) //порядковый номер дня работы склада
					->setTimeFrom($this->request->post['warehouse']['worktime2open']) //Время открытия работы
					->setTimeTill($this->request->post['warehouse']['worktime2close']); //Время закрытия работы
					
					$wtArray[2]['worktimeOpen'] = $this->request->post['warehouse']['worktime2open'];
					$wtArray[2]['worktimeClose'] = $this->request->post['warehouse']['worktime2close'];
					
					$obWorkingTimeCollection->add($obWorkingTimeElem2);
				}
				
				if(isset($this->request->post['warehouse']['worktime3open']) && isset($this->request->post['warehouse']['worktime3close'])){
					$obWorkingTimeElem3 = new \Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WorkingTime(); //формируем объект для одного дня
					$obWorkingTimeElem3->setDayNumber(3) //порядковый номер дня работы склада
					->setTimeFrom($this->request->post['warehouse']['worktime3open']) //Время открытия работы
					->setTimeTill($this->request->post['warehouse']['worktime3close']); //Время закрытия работы
					
					$wtArray[3]['worktimeOpen'] = $this->request->post['warehouse']['worktime3open'];
					$wtArray[3]['worktimeClose'] = $this->request->post['warehouse']['worktime3close'];
					
					$obWorkingTimeCollection->add($obWorkingTimeElem3);
				}
				
				if(isset($this->request->post['warehouse']['worktime4open']) && isset($this->request->post['warehouse']['worktime4close'])){
					$obWorkingTimeElem4 = new \Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WorkingTime(); //формируем объект для одного дня
					$obWorkingTimeElem4->setDayNumber(4) //порядковый номер дня работы склада
					->setTimeFrom($this->request->post['warehouse']['worktime4open']) //Время открытия работы
					->setTimeTill($this->request->post['warehouse']['worktime4close']); //Время закрытия работы
					
					$wtArray[4]['worktimeOpen'] = $this->request->post['warehouse']['worktime4open'];
					$wtArray[4]['worktimeClose'] = $this->request->post['warehouse']['worktime4close'];
					
					$obWorkingTimeCollection->add($obWorkingTimeElem4);
				}
				
				if(isset($this->request->post['warehouse']['worktime5open']) && isset($this->request->post['warehouse']['worktime5close'])){
					$obWorkingTimeElem5 = new \Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WorkingTime(); //формируем объект для одного дня
					$obWorkingTimeElem5->setDayNumber(5) //порядковый номер дня работы склада
					->setTimeFrom($this->request->post['warehouse']['worktime5open']) //Время открытия работы
					->setTimeTill($this->request->post['warehouse']['worktime5close']); //Время закрытия работы
					
					$wtArray[5]['worktimeOpen'] = $this->request->post['warehouse']['worktime5open'];
					$wtArray[5]['worktimeClose'] = $this->request->post['warehouse']['worktime5close'];
					
					$obWorkingTimeCollection->add($obWorkingTimeElem5);
				}
				
				if(isset($this->request->post['warehouse']['worktime6open']) && isset($this->request->post['warehouse']['worktime6close'])){
					$obWorkingTimeElem6 = new \Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WorkingTime(); //формируем объект для одного дня
					$obWorkingTimeElem6->setDayNumber(6) //порядковый номер дня работы склада
					->setTimeFrom($this->request->post['warehouse']['worktime6open']) //Время открытия работы
					->setTimeTill($this->request->post['warehouse']['worktime6close']); //Время закрытия работы
					
					$wtArray[6]['worktimeOpen'] = $this->request->post['warehouse']['worktime6open'];
					$wtArray[6]['worktimeClose'] = $this->request->post['warehouse']['worktime6close'];
					
					$obWorkingTimeCollection->add($obWorkingTimeElem6);
				}
				
				if(isset($this->request->post['warehouse']['worktime7open']) && isset($this->request->post['warehouse']['worktime7close'])){
					$obWorkingTimeElem7 = new \Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WorkingTime(); //формируем объект для одного дня
					$obWorkingTimeElem7->setDayNumber(7) //порядковый номер дня работы склада
					->setTimeFrom($this->request->post['warehouse']['worktime7open']) //Время открытия работы
					->setTimeTill($this->request->post['warehouse']['worktime7close']); //Время закрытия работы
					
					$wtArray[7]['worktimeOpen'] = $this->request->post['warehouse']['worktime7open'];
					$wtArray[7]['worktimeClose'] = $this->request->post['warehouse']['worktime7close'];
					
					$obWorkingTimeCollection->add($obWorkingTimeElem7);
				}
								
				$obWarehouseCollection = new \Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WarehouseElemList();
				$obWarehose = new \Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WarehouseElem();

				$obWarehose->setName($this->request->post['warehouse']['name']) //Наименование склада партнера (напр. Romashka-1)
				->setCountryId('RU') //Двухбуквенные коды стран мира международной организации по стандартизации (iso).
				->setRegionCode($this->request->post['warehouse']['region']) //Код региона.Возможные значения приложены в доке апи (может начинаться с нуля "01" итд)
				->setFederalDistrict($this->request->post['warehouse']['federaldistrict']) //Наименование области
				->setRegion($data['wh_regions'][$this->request->post['warehouse']['region']]) //Наименование региона
				->setIndex($this->request->post['warehouse']['zip']) //Почтовый индекс склада это строка, даже если и из цифр
				->setCity($this->request->post['warehouse']['city']) //Наименование города
				->setStreet($this->request->post['warehouse']['street']) //Наименование улицы
				->setHouseNumber($this->request->post['warehouse']['house']) //Номер дома склада, как видите, тоже именно строка
				->setCoordinates($this->request->post['warehouse']['coordsX'] . "," . $this->request->post['warehouse']['coordsY']) //Географические координаты склада
				->setContactPhoneNumber($this->request->post['warehouse']['phone']) //Контактный телефон объекта в формате +7**********
				->setTimeZone($this->request->post['warehouse']['timezone']) //Часовой пояс, в котором расположен склад
				->setWorkingTime($obWorkingTimeCollection) //
				->setPartnerLocationId($this->request->post['warehouse']['partnerId']);
								
				$obWarehouseCollection->add($obWarehose);
				
				$createWH = $app->createWarehouse($obWarehouseCollection);			
				
				if($createWH){
					if($createWH->isSuccess()){
						if($createWH->getResponse() &&
							$createWH->getResponse()->getWarehouses() &&
							$createWH->getResponse()->getWarehouses()->getFirst()->getId()
						){
							if($createWH->getResponse()->getWarehouses()->getFirst()->getStatus() == 'OK'){
								$uuid = $createWH->getResponse()->getWarehouses()->getFirst()->getId();
								$test = $this->config->get('shipping_5post_test') ? 1 : 0;
								$this->model_extension_shipping_5post->addWarehouse($uuid, $this->request->post['warehouse'], $wtArray, $test);
								
								$json['success'] = true;
							}else{
								$json['error'] = $createWH->getResponse()->getWarehouses()->getFirst()->getDescription();
							}
							
						}else{
							$json['error'] = $createWH->getResponse()->getErrorMsg();
						}
					}else{
						$json['error'] = $app->getLastError();
					}
				}else{
					$json['error'] = 'No response';
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }
	
	# Получение списка оплат(вкл)
	public function getPayment($code){
		$this->load->language('extension/payment/' . $code);
		
		$title = $this->language->get('heading_title');
		
		return $title;
	}
	
	# Подключение Application
	public function getApp(){
		
		require DIR_SYSTEM . 'library/fivepost/autoload.php';
				
		$app = new Ipol\Fivepost\Fivepost\FivepostApplication(
			$this->config->get('shipping_5post_client_id') ? $this->config->get('shipping_5post_client_id') : 'w0Pa59jCR88nXa7FkTO36end4ZPTNQkV', //client_id
			$this->config->get('shipping_5post_test') ? true : false, //true – for test api, false, for production
			50, //timeout for curl (sec)
			null, //implement of Ipol\Fivepost\Api\Entity\EncoderInterface – used if site can work not in UTF-8
			null, // implement of  Ipol\Fivepost\Core\Entity \CacheInterface – cache or die :[
			new Ipol\Fivepost\Admin\ToFileLoggerController(DIR_SYSTEM.'../logger.txt')
		);
                                		
		return $app;
	}
	
	# Import
	public function importDel(){		
		$this->load->model('extension/shipping/5post');
		
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		
		$this->model_extension_shipping_5post->clear5post();
		$this->response->redirect($this->url->link('extension/shipping/5post/import', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
	}
	
	public function import($page = 0){
		$this->load->model('extension/shipping/5post');
		
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		
		$app = $this->getApp();
		
		if(isset($this->session->data['PageImport5post'])){
			$page = $this->session->data['PageImport5post'];
		}else{
			$this->session->data['PageImport5post'] = $page;
		}
		
		if(!isset($this->session->data['SizeImport5post'])){
			$this->session->data['SizeImport5post'] = 0;
		}
		
		$pickupPoints = $app->getPickupPoints($page, 1000);
		
		if($pickupPoints->isSuccess() == true){
			if($pickupPoints->getResponse()->getTotalPages() == $page){
				unset($this->session->data['PageImport5post']);
				unset($this->session->data['SizeImport5post']);
				
				$this->response->redirect($this->url->link('extension/shipping/5post/cityFormation', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
			}else{
				foreach($pickupPoints->getResponse()->getContent()->getFields() as $point){
					$this->model_extension_shipping_5post->addPoint($point);
				}
				
				$this->session->data['PageImport5post'] = $this->session->data['PageImport5post'] + 1;
				$this->session->data['SizeImport5post'] = $this->session->data['SizeImport5post'] + 1000;
				
				header("refresh: 4");
				
				if($this->session->data['SizeImport5post'] > $pickupPoints->getResponse()->getTotalElements()){
					echo 'Импортированно ' . $pickupPoints->getResponse()->getTotalElements() . ' из ' . $pickupPoints->getResponse()->getTotalElements();
				}else{
					echo 'Импортированно ' . $this->session->data['SizeImport5post'] . ' из ' . $pickupPoints->getResponse()->getTotalElements();
				}
				die();
			}
		}elseif($app->getErrorCollection()->getFirst()){
			$error = $app->getErrorCollection()->getFirst();
			die($error->getMessage().PHP_EOL);
		}else{
			die($pickupPoints->getError()->getMessage());
		}
		
	}
	
	public function cityFormation(){
		$this->load->model('extension/shipping/5post');
		
		$allCity = $this->model_extension_shipping_5post->addCityAll();
		
		$this->session->data['success'] = 'Импорт успешно завершен!';
			
		$this->response->redirect($this->url->link('extension/shipping/5post', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
	}
	
	# Получение наклеек
	public function getStickersResults(){
		
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		
		if(isset($this->request->get['orderIds'])){
			$this->load->model('extension/shipping/5post');
			$orderIds = $this->request->get['orderIds'];
			
			$arrayOrderIds = explode("OC_", $orderIds);
			$content = "";
			
			foreach($arrayOrderIds as $order_id){
							
				$fivepost_id = $this->model_extension_shipping_5post->getFivepostId($order_id);
				$namePdf = $fivepost_id . '.pdf';

				$pathPdf = DIR_SYSTEM . 'library/fivepost/docs/stickers/' . $orderIds . '/' .$namePdf;
							
				if (file_exists($pathPdf)) {
					/*header('Accept-Ranges:bytes');
					header('Content-type:application/pdf');
					header('Content-disposition: inline; filename="' . $namePdf . '"');
					header('content-Transfer-Encoding:binary');
						
					readfile($pathPdf);*/
					
					/*header("Content-type: application/pdf; charset=utf-8");
					
					$file = fopen($pathPdf, "r");
										
					while($f = fgets($file,4096))
					{
						$content .= $f;
					}*/
					
					$content .= '<iframe src="https://docs.google.com/viewerng/viewer?url='.HTTPS_CATALOG.'docs/stickers/' . $orderIds . '/' .$namePdf.'&embedded=true" frameborder="0" height="100%" width="100%">
					</iframe>';
				}else{
					die('Ярлыки для запрошенных заказов не найдены или еще не готовы!');
				}
			}
			
			echo $content;
		}
	}
	
	public function getStickers(){
		$json = array();

		if(isset($this->request->post['orders'])){
			$this->load->model('extension/shipping/5post');
			$app = $this->getApp();
			
			$orderIds = '';
			$orderCount = count($this->request->post['orders']);
			$i = 0;
			$orders['senderOrderIds'] = array();
			
			foreach($this->request->post['orders'] as $order_id){
				$i++;
				
				if($orderCount == $i){
					$orderIds .= $order_id;
				}else{
					$orderIds .= $order_id . 'OC_';
				}
				
				$orders['senderOrderIds'][] = 'OC_' . $order_id;
			}
			
			$ch = curl_init();
						
			$orders = json_encode($orders);
			
			curl_setopt($ch, CURLOPT_URL, 'https://api-omni.x5.ru/api/v1/orderLabels/bySenderOrderId');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $orders);

			$headers = array();
			$headers[] = 'Content-Type: application/json';
			$headers[] = 'Authorization: bearer ' . $app->getJwt();
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
			}
			
			$info = curl_getinfo($ch);
									
			curl_close($ch);
			
			if($info['http_code'] == 200){
				$nameZip = $orderIds . '.zip';
				
				$pathZip = DIR_SYSTEM . 'library/fivepost/docs/stickers/' . $orderIds . '/' .$nameZip;
								
				if(!file_exists($pathZip)){
					mkdir(DIR_SYSTEM . 'library/fivepost/docs/stickers/' . $orderIds . '/', 0777);
					
					file_put_contents($pathZip, $result);
					
					$zip = new ZipArchive;
					
					$obj = $zip->open(DIR_SYSTEM . 'library/fivepost/docs/stickers/' . $orderIds . '/' . $nameZip);
					
					if ($obj === TRUE) {
					  $zip->extractTo(DIR_SYSTEM . 'library/fivepost/docs/stickers/' . $orderIds . '/');
					  $zip->close();
					} else {
					  $json['error'] = 'Не удалось распаковать архив!';
					}
				}
				
				foreach($this->request->post['orders'] as $order_id){
					$fivepost_id = $this->model_extension_shipping_5post->getFivepostId($order_id);
					
					$pathPdf = DIR_SYSTEM . 'library/fivepost/docs/stickers/' . $orderIds . '/' . $fivepost_id . '.pdf';
					
					if (file_exists($pathPdf)) {}else{
						$json['error'] = 'Ярлыки для запрошенных заказов не найдены или еще не готовы!';
					}
				}
				
				if (!isset($json['error'])) {
					$json['orderIds'] = $orderIds;
					$json['link'] = $this->url->link('extension/shipping/5post/getStickersResults', '', true);
				}else{
					$json['error'] = 'Ярлыки для запрошенных заказов не найдены или еще не готовы!';
				}
			}else{
				$json['error'] = 'Не удалось получить файл наклейки!';
			}		
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
		
	# Получение наклейки
	public function getSticker(){
		$app = $this->getApp();	
		
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
				
		if(isset($this->request->get['order_id']) && isset($this->request->get['fivepost_id'])){
			$ch = curl_init();
		
			$orders['senderOrderIds'] = array($this->request->get['order_id']);
			$orders = json_encode($orders);
			
			curl_setopt($ch, CURLOPT_URL, 'https://api-omni.x5.ru/api/v1/orderLabels/bySenderOrderId');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $orders);

			$headers = array();
			$headers[] = 'Content-Type: application/json';
			$headers[] = 'Authorization: bearer ' . $app->getJwt();
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
			}
			
			$info = curl_getinfo($ch);
									
			curl_close($ch);
			
			if($info['http_code'] == 200){
				$nameZip = $this->request->get['order_id'] . '.zip';
				$namePdf = $this->request->get['fivepost_id'] . '.pdf';
				
				$pathZip = DIR_SYSTEM . 'library/fivepost/docs/stickers/' . $this->request->get['order_id'] . '/' .$nameZip;
				$pathPdf = DIR_SYSTEM . 'library/fivepost/docs/stickers/' . $this->request->get['order_id'] . '/' .$namePdf;
				
				if(!file_exists($pathZip)){
					mkdir(DIR_SYSTEM . 'library/fivepost/docs/stickers/' . $this->request->get['order_id'] . '/', 0777);
					
					file_put_contents($pathZip, $result);
					
					$zip = new ZipArchive;
					
					$obj = $zip->open(DIR_SYSTEM . 'library/fivepost/docs/stickers/' . $this->request->get['order_id'] . '/' . $nameZip);
					
					if ($obj === TRUE) {
					  $zip->extractTo(DIR_SYSTEM . 'library/fivepost/docs/stickers/' . $this->request->get['order_id'] . '/');
					  $zip->close();
					} else {
					  echo 'Не удалось распаковать архив!';
					}
				}
				
				if (file_exists($pathPdf)) {
					header('Accept-Ranges:bytes');
					header('Content-type:application/pdf');
					header('Content-disposition: inline; filename="' . $namePdf . '"');
					header('content-Transfer-Encoding:binary');
					
					readfile($pathPdf);
				}else{
					die('Ярлыки для запрошенных заказов не найдены или еще не готовы!');
				}
			}else{
				die('Не удалось получить файл наклейки!');
			}
		}else{
			$this->response->redirect($this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'], true));
		}
	}
	
	#Проверка точка<->оплата
	public function validPointPayment(){
		$json = array();
		
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);

		if(isset($this->request->post['order_id']) && isset($this->request->post['receiver_location']) && isset($this->request->post['payment_type'])){
			$this->load->model('extension/shipping/5post');
			
			$paymentTypes = $this->model_extension_shipping_5post->getPaymentTypes($this->request->post['receiver_location']);
			
			$cardAllowed = $paymentTypes['cardAllowed'];
			$cashAllowed = $paymentTypes['cashAllowed'];
			
			if($this->request->post['payment_type'] == 'Cash' && $cashAllowed == 0){
				$json['error'] = 'В данном пункте нет оплаты наличными!';
			}
			
			if($this->request->post['payment_type'] == 'Card' && $cardAllowed == 0){
				$json['error'] = 'В данном пункте нет оплаты картой!';
			}
			
			$this->load->model('extension/shipping/5post');
		}
		
		if(isset($this->request->post['map_order_id']) && isset($this->request->post['receiver_location'])){
			$this->load->model('extension/shipping/5post');
			
			$payment_type = $this->model_extension_shipping_5post->getOrderPaymentType($this->request->post['map_order_id']);
			$paymentTypes = $this->model_extension_shipping_5post->getPaymentTypes($this->request->post['receiver_location']);
			
			$cardAllowed = $paymentTypes['cardAllowed'];
			$cashAllowed = $paymentTypes['cashAllowed'];
			
			if($payment_type == 'Cash' && $cashAllowed == 0){
				$json['error'] = 'В данном пункте нет оплаты наличными!';
			}
			
			if($payment_type == 'Card' && $cardAllowed == 0){
				$json['error'] = 'В данном пункте нет оплаты картой!';
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	# Получить данные для формы заказа
	public function formOrder(){
		$this->load->model('extension/shipping/5post');
		$app = $this->getApp();
		
		$json = array();
		
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		
		$test = $this->config->get('shipping_5post_test') ? 1 : 0;
		
		# Склады
		$json['warehouses'] = $this->model_extension_shipping_5post->getWarehouses($test);
		
		# Типы платёжки
		$json['payments_type'] = array();
		$json['payments_type'][0]['value'] = 'Cash';
		$json['payments_type'][0]['name'] = 'Оплата наличными';
		$json['payments_type'][1]['value'] = 'Card';
		$json['payments_type'][1]['name'] = 'Оплата картой';
		$json['payments_type'][2]['value'] = 'Bill';
		$json['payments_type'][2]['name'] = 'Предоплата';
		
		if(isset($this->request->post['order_id'])){
			$order_id = $this->request->post['order_id'];
			$order_info = $this->model_extension_shipping_5post->getOrder5post($order_id);
			
			if(!empty($order_info)){
				$json['ok'] = $order_info['ok'];
				$json['fivepost_status'] = $order_info['fivepost_status'];
				$json['status'] = '';
				$json['message'] = $order_info['message'];
				$json['disabled'] = false;
				
				if(!empty($json['fivepost_status'])){
					$json['status'] = $this->model_extension_shipping_5post->getStatusName($json['fivepost_status']);
				}
				
				$json['order_id'] = $order_id; 
				
				$json['uptime'] = date('d-m-Y' , $order_info['uptime']);
				
				if(isset($this->request->post['planned_receive_date'])){
					$json['planned_receive_date'] = $this->request->post['planned_receive_date'];
				}else{
					$json['planned_receive_date'] = $order_info['planned_receive_date'];
				}
				
				if($json['planned_receive_date'] = '0000-00-00'){
					$json['planned_receive_date'] = '';
				}
				
				$json['barcode'] = $order_info['barcode'];
				$json['fivepost_id'] = $order_info['fivepost_id'];
				
				if(isset($this->request->post['shipment_date'])){
					$json['shipment_date'] = $this->request->post['shipment_date'];
				}else{
					$json['shipment_date'] = $order_info['shipment_date'];
				}
				
				if($json['shipment_date'] = '0000-00-00'){
					$json['shipment_date'] = '';
				}
				
				$json['point_address'] = $this->model_extension_shipping_5post->getPointAddress($order_info['receiver_location']);
				
				if(isset($this->request->post['sender_location'])){
					$json['sender_location'] = $this->request->post['sender_location'];
				}else{
					$json['sender_location'] = $order_info['sender_location'];
				}
				
				$json['receiver_location'] = $order_info['receiver_location'];
				
				if(isset($this->request->post['brand_name'])){
					$json['brand_name'] = $this->request->post['brand_name'];
				}else{
					$json['brand_name'] = $order_info['brand_name'];
				}
				
				if(isset($this->request->post['undeliverable_option'])){
					$json['undeliverable_option'] = $this->request->post['undeliverable_option'];
				}else{
					$json['undeliverable_option'] = $order_info['undeliverable_option'];
				}
				
				$json['goods'] = unserialize($order_info['goods']);
				
				if(isset($this->request->post['goods'])){
					if(isset($this->request->post['goods']['length'])){
						$json['goods']['length'] = $this->request->post['goods']['length'];
					}

					if(isset($this->request->post['goods']['width'])){
						$json['goods']['width'] = $this->request->post['goods']['width'];
					}
					
					if(isset($this->request->post['goods']['height'])){
						$json['goods']['height'] = $this->request->post['goods']['height'];
					}
					
					if(isset($this->request->post['goods']['weight'])){
						$json['goods']['weight'] = $this->request->post['goods']['weight'];
					}
				}
				
				if(isset($this->request->post['client_name'])){
					$json['client_name'] = $this->request->post['client_name'];
				}else{
					$json['client_name'] = $order_info['client_name'];
				}
				
				if(isset($this->request->post['client_email'])){
					$json['client_email'] = $this->request->post['client_email'];
				}else{
					$json['client_email'] = $order_info['client_email'];
				}
				
				if(isset($this->request->post['client_phone'])){
					$json['client_phone'] = $this->request->post['client_phone'];
				}else{
					$json['client_phone'] = $order_info['client_phone'];
				}
				
				$json['price'] = $order_info['price'];
				
				if(isset($this->request->post['delivery_cost'])){
					$json['delivery_cost'] = $this->request->post['delivery_cost'];
				}else{
					$json['delivery_cost'] = $order_info['delivery_cost'];
				}
				
				$delivery_cost = $json['delivery_cost'];
				
				if(isset($this->request->post['payment_type'])){
					$json['payment_type'] = $this->request->post['payment_type'];
					
					if(isset($this->request->post['is_beznal'])){
						$json['is_beznal'] = 1;
					}else{
						$json['is_beznal'] = 0;
					}
				}else{
					$json['is_beznal'] = $order_info['is_beznal'];
					$json['payment_type'] = $order_info['payment_type'];
				}
												
				$json['disabled_param'] = '';
				$json['priceProduct'] = $order_info['price'];
				
				$payment_type = $json['payment_type'];
				
				if($json['is_beznal'] == 1){
					$json['priceProduct'] = 0;
					$json['disabled_param'] = 'disabled';
					
					$payment_type = 'Bill';
					$delivery_cost = 0;
				}
				
				$json['items'] = unserialize($order_info['items']);
								
				if(isset($this->request->post['items'])){
					foreach($this->request->post['items'] as $key => $row){
						$json['items'][$key]['nds'] = $row['nds'];
						$json['items'][$key]['sku'] = $row['sku'];
					}
				}
								
				# Сохраняем в базу
				if($this->request->post['formType'] == 'save' or $this->request->post['formType'] == 'send'){
					$this->model_extension_shipping_5post->saveOrder($json, $order_id);
				}
				
				# Проверка статуса
				if($this->request->post['formType'] == 'checkStatus'){
					$statusInfo = $app->getOrderStatus(array('OC_'.$order_id),'senderOrderId');
					
					if($statusInfo->isSuccess()){
						$status = $statusInfo->getResponse()->getOrderStatuses()->getFirst()->getStatus();
						
						if($statusInfo->getResponse()->getOrderStatuses()->getFirst()->getExecutionStatus()){
							$statusExecution = $statusInfo->getResponse()->getOrderStatuses()->getFirst()->getExecutionStatus();
							
							$statusOc = $this->model_extension_shipping_5post->getStatusLink($status, $statusExecution);				
						}else{
							$statusOc = $this->model_extension_shipping_5post->getStatusLink($status);
						}
						
						$this->model_extension_shipping_5post->updateStatus($statusOc, $order_id);
						
						$json['fivepost_status'] = $statusOc;
						$json['status'] = $this->model_extension_shipping_5post->getStatusName($json['fivepost_status']);
									
						if($this->config->get('shipping_5post_status_' . $statusOc) != 'non'){
							$this->model_extension_shipping_5post->updateStatusMainOrder($this->config->get('shipping_5post_status_' . $statusOc), $order_id);
						}
					}
				}
				
				# Отмена отгрузки
				if($this->request->post['formType'] == 'cancel'){
					$cancelOrder = $app->cancelOrderByNumber('OC_'.$order_id);
					
					$this->log->write($cancelOrder);
										
					if($cancelOrder->isSuccess()){
						if($cancelOrder->getResponse()->getError()){
							$json['error'] = $cancelOrder->getResponse()->getDecoded()->errorMessage;
						}else{
							$this->model_extension_shipping_5post->updateStatus('canceled', $order_id);
							$json['fivepost_status'] = 'canceled';
							$json['status'] = $this->model_extension_shipping_5post->getStatusName($json['fivepost_status']);
							
							if($this->config->get('shipping_5post_status_canceled') != 'non'){
								$this->model_extension_shipping_5post->updateStatusMainOrder($this->config->get('shipping_5post_status_canceled'), $order_id);
							}
						}
					}
				}
				
				# Отгрузка
				if($this->request->post['formType'] == 'send'){
					$cOrder = new \Ipol\Fivepost\Core\Order\Order();

					$receiver = new \Ipol\Fivepost\Core\Order\Receiver();
					$receiver
						->setEmail($json['client_email']) //optional
						->setFullName($json['client_name'])
						->setPhone($json['client_phone']); //(+79XXXXXXXXX, 79XXXXXXXXX, 89XXXXXXXXX or 9XXXXXXXXX)
						
					$receiverCollection = new \Ipol\Fivepost\Core\Order\ReceiverCollection();
					$receiverCollection->add($receiver);
					$cOrder->setReceivers($receiverCollection);

					$goods = new \Ipol\Fivepost\Core\Order\Goods();
					$goods
						->setLength($json['goods']['length']) //mm
						->setWidth($json['goods']['width']) //mm
						->setHeight($json['goods']['height']) //mm
						->setWeight($json['goods']['weight']); //gram
					$cOrder->setGoods($goods); //overall cargo dimensions and weight

					$payment = new \Ipol\Fivepost\Core\Order\Payment();
					$payment
						->setGoods(new \Ipol\Fivepost\Core\Entity\Money($json['priceProduct'], 'RUB')) //how much should fivepost charge buyer for order items
						->setDelivery(new \Ipol\Fivepost\Core\Entity\Money($delivery_cost, 'RUB')) //how much should fivepost charge buyer for delivery
						->setIsBeznal($json['is_beznal']) //optional. true for pre-payed orders (online payment). false by default
						->setEstimated(new \Ipol\Fivepost\Core\Entity\Money($json['price'], 'RUB')) //optional - estimated price of cargo(shipment) for insurance. Can be set to 0, but when not set at all, estimated price = goods price.
						->setType($payment_type); //type of payment - 'Cash'|'Card'|'Bill'
					$cOrder->setPayment($payment);

					$itemCollection = new \Ipol\Fivepost\Core\Order\ItemCollection();
					foreach ($json['items'] as $row) {
						$item = new \Ipol\Fivepost\Core\Order\Item();
						$item
							->setName($row['name'])
							->setPrice(new \Ipol\Fivepost\Core\Entity\Money($row['price'], 'RUB'))
							->setQuantity($row['quantity'])
							->setVatRate($row['nds'])
							->setBarcode('') //optional
							->setArticul($row['sku']) //optional
							->setField('oc', '') //optional Origin Country
							->setField('ccd', '') //optional GTD code
							->setField('tnved', ''); //optional
						$itemCollection->add($item);
					}
					$cOrder->setItems($itemCollection);
					
					$dt_createDate = new DateTime($json['uptime']);
					$dt_receiveDate = new DateTime($json['planned_receive_date']);
					$dt_shipmentDate = new DateTime($json['shipment_date']);
									
					$cOrder
						->setNumber('OC_' . $order_id) //Sender order id - id in CMS for sync
						->setField('createDate', $dt_createDate->format('Y-m-d\TH:i:s\Z')) //optional - timestamp when order was created
						->setField('receiveDate', $dt_receiveDate->format('Y-m-d\TH:i:s\Z')) //optional - timestamp when receiver wants to get order
						->setField('shipmentDate', $dt_shipmentDate->format('Y-m-d\TH:i:s\Z')) //optional - timestamp when seller plans to deliver to fivepost
						->setField('brandName', $json['brand_name']) //seller name for sms info for receiver
						->setField('track', 'OC_'. $order_id) //it is recommended to be equal with next field - barcodes - this field is used for tracking info for receiver as track-number
						->setField('senderCargoId', 'OC_'. $order_id) //must be unique. It is recommended to be equal with field barcodes. Cargo id in seller system (like shipment id in Bitrix)
						->setField('receiverLocation', $order_info['receiver_location']) //fivepost location uuid
						->setField('senderLocation', $json['sender_location']) //fivepost location uuid
						->setField('undeliverableOption', $json['undeliverable_option']) //RETURN | UTILIZATION - what to do if receiver declined
						->setField('currency', 'RUB'); //main order currency (Alpha-3 form)

					$orderCollection = new \Ipol\Fivepost\Core\Order\OrderCollection();
					$orderCollection->add($cOrder); //technically, API allows to send many orders in one request
					
					$sendOrderArray = $app->OrdersMake($orderCollection);
					
					$orderArray = $sendOrderArray->getResponse()->getContentList()->getFirst();
					
					if($sendOrderArray->isSuccess()){
						if($orderArray->isCreated()){
							$json['barcode'] = $orderArray->getCargoes()->getFirst()->getBarcode();
							$json['fivepost_id'] = $orderArray->getOrderId();
							$json['ok'] = 'Y';
							$json['fivepost_status'] = 'new';
							$json['status'] = $this->model_extension_shipping_5post->getStatusName($json['fivepost_status']);
							
							$this->model_extension_shipping_5post->saveOrder($json, $order_id);
							
							if($this->config->get('shipping_5post_status_new') != 'non'){
								$this->model_extension_shipping_5post->updateStatusMainOrder($this->config->get('shipping_5post_status_new'), $order_id);
							}
							
						}else{
							$json['error'] = $orderArray->getErrors()->getFirst()->getMessage();
							$json['message'] = $json['error'];
						
							$this->model_extension_shipping_5post->setMessage($json['error'], $order_id);
						}
					}else{
						$json['error'] = $app->getLastError();
					}
				}
			}else{
				$this->load->model('sale/order');
				$this->load->model('catalog/product');
				
				$order_info = $this->model_sale_order->getOrder($order_id);
				
				$json['ok'] = 'N';
				$json['fivepost_status'] = '';
				$json['status'] = '';
				$json['message'] = '';
				$json['order_id'] = $order_id;
				$json['uptime'] = date('d-m-Y' , time());
				$json['barcode'] = '';
				$json['fivepost_id'] = '';
				
				$json['planned_receive_date'] = '';
				$json['shipment_date'] = '';
								
				$test = $this->config->get('shipping_5post_test') ? 1 : 0;
				$warehouse = $this->model_extension_shipping_5post->getWarehouse($test);
		
				$json['sender_location'] = $warehouse['partnerId'];
				
				$json['brand_name'] = $this->config->get('shipping_5post_brand');
				
				$json['undeliverable_option'] = $this->config->get('shipping_5post_undeliverableOption');
				
				if(!empty($order_info['firstname']) && !empty($order_info['lastname'])){
					$json['client_name'] = $order_info['firstname'] . ' ' . $order_info['lastname'];
				}else{
					$json['client_name'] = $order_info['firstname'];
				}
				
				$json['client_phone'] = '';
		
				if(!empty($order_info['telephone'])){
					$json['client_phone'] = $order_info['telephone'];
				}
						
				$json['client_email'] = '';

				if(!empty($order_info['email'])){
					$json['client_email'] = $order_info['email'];
				}
										
				$order_products_5post = $this->model_sale_order->getOrderProducts($order_id);
				
				$json['items'] = array();
				$products = array();
				$weight = 0;
				$json['price'] = 0;	
				$json['is_beznal'] = 0;
				
				foreach($order_products_5post as $key => $product_5post){
					$product_info = $this->model_catalog_product->getProduct($product_5post['product_id']);
					
					$json['items'][$key]['product_id'] = $product_info['product_id'];
					$json['items'][$key]['name'] = $product_info['name'];
					$json['items'][$key]['sku'] = $product_info['sku'];
					
					$json['items'][$key]['quantity'] = (int)$product_5post['quantity'];
					
					$json['items'][$key]['price'] = (int)$product_5post['tax']+(int)$product_5post['price'];
					
					$json['items'][$key]['nds'] = ((int)$product_5post['tax']*100)/(int)$product_5post['price'];
					
					$products[$key] = $product_info;
					$products[$key]['quantity'] = $json['items'][$key]['quantity'];
					
					$weight += $this->weight->convert($this->cart->getWeight(), $product_info['weight_class_id'], 2); 
					
					$priceQ = ((int)$product_5post['price'] + (int)$product_5post['tax'])*(int)$product_5post['quantity'];
					$json['price'] += $priceQ;	
				}
				
				$json['priceProduct'] = $json['price'];
				 				
				$dimansions = $this->calcShipmentDimensions($products);
				
				$json['goods']['width'] = $dimansions['W'];
				$json['goods']['height'] = $dimansions['H'];
				$json['goods']['length'] = $dimansions['L'];
				$json['goods']['weight'] = ($weight > 0) ? $weight : $this->config->get('shipping_5post_weightD');
								
				$shipping_code = $this->model_extension_shipping_5post->getZoneCode($order_info['shipping_zone_id']);
				$shipping_city = $order_info['shipping_city'];
				
				$cityData = $this->model_extension_shipping_5post->getCityFromPoints($shipping_city, $shipping_code);
				
				$json['disabled'] = false;
				
				if(empty($cityData)){
					$json['delivery_cost'] = 0;
					$json['receiver_location'] = 0;
					$json['point_address'] = 'В данном городе нет пунктов!';
					$json['disabled'] = true;
				}else{
					$maxCellDimensionsHash = $this->model_extension_shipping_5post->makeDimensionsHash($json['goods']['width'], $json['goods']['height'], $json['goods']['length']);
					
					# Cache params
					$params = array();
					$params['city'] = $cityData['city'];
					$params['region'] = $cityData['region'];
					$params['maxCellDimensionsHash'] = $maxCellDimensionsHash;
					$params['totalWeight'] = $json['goods']['weight'];
					$params['rateType'] = $this->config->get('shipping_5post_rateType');
					
					$points = $this->model_extension_shipping_5post->getPoints($cityData, $maxCellDimensionsHash, $json['goods']['weight']);
					
					$cacheRate = 'fivepost.shipping.deliveryRate.' . md5(implode('', $params));
					$cachePrice = 'fivepost.shipping.deliveryPrice.' . md5(implode('', $params));
					
					if($this->config->get('shipping_5post_rateType') == 'MIN_PRICE'){
						
						if (! $rate = $this->cache->get($cacheRate)) {
							foreach($points as $key => $point){
								$rate[$point['pointId']] = $this->model_extension_shipping_5post->getRateMinPrice($point['pointId']);
							}
							
							$this->cache->set($cacheRate, $rate);
						}
						
						if (! $rateValues = $this->cache->get($cachePrice)) {
							foreach($points as $key => $point){
								$RateMinPriceRateValue = $this->model_extension_shipping_5post->getRateMinPriceRateValue($point['pointId']);
								if(!empty($RateMinPriceRateValue)){
									$rateValues[$RateMinPriceRateValue['id']] = $RateMinPriceRateValue['rateValueWithVat'];
								}
							}
							
							$this->cache->set($cachePrice, $rateValues);
						}
					}else{
							
						if (! $rate = $this->cache->get($cacheRate)) {
							foreach($points as $key => $point){
								$rate[$point['pointId']] = $this->model_extension_shipping_5post->getRateForType($point['pointId'], $this->config->get('shipping_5post_rateType'));
							}
							
							$this->cache->set($cacheRate, $rate);
						}
						
						if (! $rateValues = $this->cache->get($cachePrice)) {
							foreach($points as $key => $point){
								$RateForTypeRateValue = $this->model_extension_shipping_5post->getRateForTypeRateValue($point['pointId'], $this->config->get('shipping_5post_rateType'));
								if(!empty($RateForTypeRateValue)){
									$rateValues[$RateForTypeRateValue['id']] = $RateForTypeRateValue['rateValueWithVat'];
								}
							}
							
							$this->cache->set($cachePrice, $rateValues);
						}
						
					}
					
					if(!empty($rateValues)){
						# Минимальная стоимость доставки
						$minPrice = min($rateValues);
						
						# Ключ минимальной доставки
						$rate_id = array_search(min($rateValues), $rateValues);
						
						$dataMinRate = $this->model_extension_shipping_5post->getDataMinRate($rate_id);
						$totalWeightKg = $this->weight->convert($params['totalWeight'], 2, 1);
						
						# Учёт перевеса по тарифу
						if($totalWeightKg > $this->config->get('shipping_5post_baseRate')){
										
							# Пересчёт
							$preponderance = $totalWeightKg - $this->config->get('shipping_5post_baseRate');
							$preponderance = (int)$preponderance;
							$preponderanceOverweight = $preponderance*$this->config->get('shipping_5post_overweight');
													
							$rateExtraValueWithVat = $preponderanceOverweight*$dataMinRate['rateExtraValueWithVat'];
							
							$minPrice = $minPrice + $rateExtraValueWithVat;
						}
						
						$json['delivery_cost'] = $minPrice;
						$json['receiver_location'] = $dataMinRate['pointId'];
						$json['point_address'] = $this->model_extension_shipping_5post->getPointAddress($json['receiver_location']);
						
						# Type Payment
						$keyPayment = false;
						
						if($this->config->get('shipping_5post_card_payment')){
							$keyPayment = array_search($order_info['payment_code'], $this->config->get('shipping_5post_card_payment'));
						}
																						
						$cardAllowed = false;
						$cashAllowed = false;
												
						if($keyPayment !== false){
							$cardAllowed = $this->model_extension_shipping_5post->getCardAllowed($json['receiver_location']);
							
							if($cardAllowed == false){
								$keyPayment = array_search($order_info['payment_code'], $this->config->get('shipping_5post_cash_payment'));
																	
								if($keyPayment !== false){
									$cashAllowed = $this->model_extension_shipping_5post->getCashAllowed($json['receiver_location']);
								}
							}
						}else{
							$keyPayment = array_search($order_info['payment_code'], $this->config->get('shipping_5post_cash_payment'));
																
							if($keyPayment !== false){
								$cashAllowed = $this->model_extension_shipping_5post->getCashAllowed($json['receiver_location']);
							}
						}
						
						if($cardAllowed == true){			
							$json['payment_type'] = 'Card';
							$json['payment_value'] = $json['price'] + $json['delivery_cost'];
							$json['is_beznal'] = 1;
						}elseif($cashAllowed == true){
							$json['payment_type'] = 'Cash';
							$json['payment_value'] = $json['price'] + $json['delivery_cost'];
							$json['is_beznal'] = 1;
						}else{
							$json['payment_type'] = 'Bill';
							$json['payment_value'] = 0;
							$json['is_beznal'] = 1;
						}
						
						/* Сроки если будут нужны
						$pointDate = $this->getPoint($dataMinRate['pointId']);
							
						# Сроки доставки
						$termsUnserial = unserialize($pointDate['deliverySL']);
						$terms = $termsUnserial[0]['Sl'];
						
						if($this->config->get('shipping_5post_increase')){
							$terms = $terms + $this->config->get('shipping_5post_increase');
						}*/
						
						$this->model_extension_shipping_5post->addOrder($json, $order_id);
					}
				}							
			}
		}else{
			$json['error'] = 'Не получили номер заказа, попробуйте ещё раз';
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function calcShipmentDimensions($items, $defaultDimensions = array())
	{
		$defaultDimensions = $defaultDimensions ?: array(
			'LENGTH' => $this->config->get('shipping_5post_lenghtD'),
			'WIDTH'  => $this->config->get('shipping_5post_widthD'),
			'HEIGHT' => $this->config->get('shipping_5post_heightD'),
		);
		 
		$itemsRead = array();
		
		if ($items) {
			// получаем габариты одного вида товара в посылке с учетом кол-ва
			foreach ($items as $key => $item) {
				$itemsRead[$key]['DIMENSIONS']['WIDTH']  = ($item['width'] > 0)  ? $this->length->convert($item['width'], $item['length_class_id'], 2) : $defaultDimensions['WIDTH'];
				$itemsRead[$key]['DIMENSIONS']['HEIGHT'] = ($item['height'] > 0) ? $this->length->convert($item['height'], $item['length_class_id'], 2) : $defaultDimensions['HEIGHT'];
				$itemsRead[$key]['DIMENSIONS']['LENGTH'] = ($item['length'] > 0) ? $this->length->convert($item['length'], $item['length_class_id'], 2) : $defaultDimensions['LENGTH'];
				$itemsRead[$key]['QUANTITY'] = $item['quantity'];
			}
		}
		
		$sumDimensions = $this->sumDimensions($itemsRead);

		return array(
			// мм -> см
			'W'  => $sumDimensions['WIDTH'],

			// мм -> см
			'H' => $sumDimensions['HEIGHT'],

			// мм -> см
			'L' => $sumDimensions['LENGTH'],
		);
	}
	
	public function sumDimensions($items)
	{
		$ret = array(
			'VOLUME' => 0,
			'LENGTH' => 0,
			'WIDTH'  => 0,
			'HEIGHT' => 0,
		);

		$a = array();
		foreach ($items as $item) {
			$a[] = $this->calcItemDimensionWithQuantity(
				$item['DIMENSIONS']['WIDTH'],
				$item['DIMENSIONS']['HEIGHT'],
				$item['DIMENSIONS']['LENGTH'],
				$item['QUANTITY']
			);
		}

		$n = count($a);
		if ($n <= 0) { 
			return $ret;
		}

		for ($i3 = 1; $i3 < $n; $i3++) {
			// отсортировать размеры по убыванию
			for ($i2 = $i3-1; $i2 < $n; $i2++) {
				for ($i = 0; $i <= 1; $i++) {
					if ($a[$i2]['X'] < $a[$i2]['Y']) {
						$a1 = $a[$i2]['X'];
						$a[$i2]['X'] = $a[$i2]['Y'];
						$a[$i2]['Y'] = $a1;
					};

					if ($i == 0 && $a[$i2]['Y']<$a[$i2]['Z']) {
						$a1 = $a[$i2]['Y'];
						$a[$i2]['Y'] = $a[$i2]['Z'];
						$a[$i2]['Z'] = $a1;
					}
				}

				$a[$i2]['Sum'] = $a[$i2]['X'] + $a[$i2]['Y'] + $a[$i2]['Z']; // сумма сторон
			}

			// отсортировать грузы по возрастанию
			for ($i2 = $i3; $i2 < $n; $i2++) {
				for ($i = $i3; $i < $n; $i++) {
					if ($a[$i-1]['Sum'] > $a[$i]['Sum']) {
						$a2 = $a[$i];
						$a[$i] = $a[$i-1];
						$a[$i-1] = $a2;
					}
				}
			}

			// расчитать сумму габаритов двух самых маленьких грузов
			if ($a[$i3-1]['X'] > $a[$i3]['X']) {
				$a[$i3]['X'] = $a[$i3-1]['X'];
			}

			if ($a[$i3-1]['Y'] > $a[$i3]['Y']) { 
				$a[$i3]['Y'] = $a[$i3-1]['Y'];
			}

			$a[$i3]['Z'] = $a[$i3]['Z'] + $a[$i3-1]['Z'];
			$a[$i3]['Sum'] = $a[$i3]['X'] + $a[$i3]['Y'] + $a[$i3]['Z']; // сумма сторон
		}

		return array_merge($ret, array(
			'LENGTH' => $length = Round($a[$n-1]['X'], 2),
			'WIDTH'  => $width  = Round($a[$n-1]['Y'], 2),
			'HEIGHT' => $height = Round($a[$n-1]['Z'], 2),
			'VOLUME' => $width * $height * $length,
		));
	}
	
	public function calcItemDimensionWithQuantity($width, $height, $length, $quantity)
	{
		$ar = array($width, $height, $length);
		$qty = $quantity;
		sort($ar);

		if ($qty <= 1) {
			return array(
				'X' => $ar[0],
				'Y' => $ar[1],
				'Z' => $ar[2],
			);
		}

		$x1 = 0;
		$y1 = 0;
		$z1 = 0;
		$l  = 0;

		$max1 = floor(Sqrt($qty));
		for ($y = 1; $y <= $max1; $y++) {
			$i = ceil($qty / $y);
			$max2 = floor(Sqrt($i));
			for ($z = 1; $z <= $max2; $z++) {
				$x = ceil($i / $z);
				$l2 = $x*$ar[0] + $y*$ar[1] + $z*$ar[2];
				if ($l == 0 || $l2 < $l) {
					$l = $l2;
					$x1 = $x;
					$y1 = $y;
					$z1 = $z;
				}
			}
		}
		
		return array(
			'X' => $x1 * $ar[0],
			'Y' => $y1 * $ar[1],
			'Z' => $z1 * $ar[2]
		);
	}
			
	# Проверка формы
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/5post')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['shipping_5post_name_pvz']) < 1)) {
			$this->error['name_pvz'] = "Значение не может быть пустым!";
		}
		
		
		if ((utf8_strlen($this->request->post['shipping_5post_client_id']) < 1)) {
			$this->error['client_id'] = "Значение не может быть пустым!";
		}
						
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return !$this->error;
	}
	
	public function install() {
		#Таблица пунктов доставки
		$this->db->query("CREATE TABLE IF NOT EXISTS `ipol_5post_pickup_points` (
		  `pointId` varchar(50) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `partnerName` varchar(30) NOT NULL,
		  `type` varchar(30) NOT NULL,
		  `additional` varchar(255) NOT NULL,
		  
		  `fullAddress` varchar(255) NOT NULL,
		  `country` varchar(50) NOT NULL,
		  `zipCode` varchar(50) NOT NULL,
		  `region` varchar(50) NOT NULL,
		  `city` varchar(50) NOT NULL,
		  `cityType` varchar(50) NOT NULL,
		  `street` varchar(50) NOT NULL,
		  `house` varchar(50) NOT NULL,
		  `lat` double NOT NULL,
		  `lng` double NOT NULL,
		  `metroStation` varchar(50) NOT NULL,
		  
		  `maxCellWidth` int(11) NOT NULL DEFAULT '0',
		  `maxCellHeight` int(11) NOT NULL DEFAULT '0',
		  `maxCellLength` int(11) NOT NULL DEFAULT '0',
		  `maxWeight` int(11) NOT NULL DEFAULT '0',
		  `maxCellDimensionsHash` int(11) NOT NULL DEFAULT '0',
		  
		  `returnAllowed` int(1) NOT NULL DEFAULT '0',
		  `timezone` varchar(30) NOT NULL,
		  `phone` varchar(30) NOT NULL,
		  `cashAllowed` int(1) NOT NULL DEFAULT '0',
		  `cardAllowed` int(1) NOT NULL DEFAULT '0',
		  `loyaltyAllowed` int(1) NOT NULL DEFAULT '0',
		  `extStatus` varchar(30) NOT NULL,
		  `lastMileWarehouseId` varchar(255) NOT NULL,
		  `lastMileWarehouseName` varchar(255) NOT NULL,
		  
		  `workHours` text NOT NULL,
		  `deliverySL` text NOT NULL,
		  PRIMARY KEY (`pointId`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;");
				
		#Таблица тарифов
		$this->db->query("CREATE TABLE IF NOT EXISTS `ipol_5post_pickup_points_rate` (
		  `rate_id` int(11) NOT NULL AUTO_INCREMENT,
		  `pointId` varchar(50) NOT NULL,
		  `zone` int(11) NOT NULL,
		  `rateType` varchar(30) NOT NULL,
		  `rateValue` decimal(18,4) NOT NULL,
		  `rateExtraValue` decimal(18,4) NOT NULL,
		  `rateCurrency` varchar(30) NOT NULL,
		  `vat` int(11) NOT NULL,
		  `rateValueWithVat` decimal(18,4) NOT NULL,
		  `rateExtraValueWithVat` decimal(18,4) NOT NULL,
		   PRIMARY KEY (`rate_id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;");
		
		#Таблица городов
		$this->db->query("CREATE TABLE IF NOT EXISTS `ipol_5post_city` (
			 `id` int(11) NOT NULL AUTO_INCREMENT,
			 `city` varchar(50) NOT NULL,
			 `isCity` int(1) NOT NULL DEFAULT '0',
			 `region` varchar(50) NOT NULL,
			 `code` varchar(32) NOT NULL,
			 PRIMARY KEY (`id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;");
		
		#Таблица складов
		$this->db->query("CREATE TABLE IF NOT EXISTS `ipol_5post_warehouse` (
		  `uuid` varchar(40) NOT NULL,
		  `name` varchar(30) NOT NULL,
		  `partnerId` varchar(30) NOT NULL,
		  `region` varchar(30) NOT NULL,
		  `federaldistrict` varchar(30) NOT NULL,
		  `zip` varchar(11) NOT NULL,
		  `city` varchar(30) NOT NULL,
		  `street` varchar(50) NOT NULL,
		  `house` varchar(10) NOT NULL,
		  `coordsX` varchar(10) NOT NULL,
		  `coordsY` varchar(10) NOT NULL,
		  `phone` varchar(30) NOT NULL,
		  `timezone` varchar(10) NOT NULL,
		  `worktime` text NOT NULL,
		  `added` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
		  `test` int(1) NOT NULL,
		   PRIMARY KEY (`uuid`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;");
		
		# Заявки
		$this->db->query("CREATE TABLE IF NOT EXISTS `ipol_5post_orders` (
		  `id`                        INT(11) NOT NULL auto_increment,
		  `order_id`                  INT(11) NOT NULL,
		  `fivepost_id`		       	  VARCHAR(50) NOT NULL,
		  `barcode`           		  VARCHAR(50) NOT NULL,
		  `fivepost_status`           VARCHAR(50) NOT NULL,
		  `fivepost_execution_status` VARCHAR(50) NOT NULL,
		  `brand_name`		          text NOT NULL,
		  `client_name`               text NOT NULL,
		  `client_email`              text NOT NULL,
		  `client_phone` 	          VARCHAR(12) NOT NULL,
		  `planned_receive_date`      DATE NOT NULL,
		  `shipment_date`             DATE NOT NULL,
		  `receiver_location`         VARCHAR(50) NOT NULL,
		  `sender_location`	          text NOT NULL,
		  `undeliverable_option`      VARCHAR(11) NOT NULL,
		  `goods`                     text NOT NULL,
		  `items`                     text NOT NULL,
		  `currency`                  VARCHAR(3),
		  `delivery_cost`             DECIMAL(18,4),
		  `payment_value`             DECIMAL(18,4) NOT NULL,
		  `payment_type`              VARCHAR(10) NOT NULL,
		  `is_beznal`				  INT(1) NOT NULL,
		  `price`                     DECIMAL(18,4) NOT NULL,
		  `message`                   text NOT NULL,
		  `ok`                        CHAR(1),
		  `uptime`                    VARCHAR(10),
		  PRIMARY KEY (`id`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;");
		
	}
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `ipol_5post_pickup_points`");
		$this->db->query("DROP TABLE IF EXISTS `ipol_5post_pickup_points_rate`");
		$this->db->query("DROP TABLE IF EXISTS `ipol_5post_city`");
		$this->db->query("DROP TABLE IF EXISTS `ipol_5post_orders`");
	}
	
}