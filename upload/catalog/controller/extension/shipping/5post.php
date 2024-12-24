<?php
# Разработчик: Кузнецов Богдан	
# ipol.ru
# 5post - служба доставки

class ControllerExtensionShipping5post extends Controller {
	#Получение списка ПВЗ
	public function getPoints(){
		$this->load->model('extension/shipping/5post');
		
		$json = array();
				
		#Габариты и вес
		$totalWeight = ($this->cart->getWeight() > 0) ? $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), 2) : $this->config->get('shipping_5post_weightD');
        $dimansions = $this->model_extension_shipping_5post->calcShipmentDimensions($this->cart->getProducts());
		$maxCellDimensionsHash = $this->model_extension_shipping_5post->makeDimensionsHash($dimansions['W'], $dimansions['H'], $dimansions['L']);
		
		$totalWeightKg = $this->weight->convert($totalWeight, 2, 1);
		
		# Местоположение и поинты
		$cityData = $this->model_extension_shipping_5post->getCityFromPoints($this->session->data['shipping_address']['city'], $this->session->data['shipping_address']['zone_code']);
		$points = $this->model_extension_shipping_5post->getPoints($cityData, $maxCellDimensionsHash, $totalWeight);
		
		# Cache params
		$params = array();
		$params['city'] = $cityData['city'];
		$params['region'] = $cityData['region'];
		$params['maxCellDimensionsHash'] = $maxCellDimensionsHash;
		$params['totalWeight'] = $totalWeight;
		$params['rateType'] = $this->config->get('shipping_5post_rateType');
		
		$cachePrice = 'fivepost.shipping.deliveryPrice.' . md5(implode('', $params));
		$cacheRate = 'fivepost.shipping.deliveryRate.' . md5(implode('', $params));
		
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
					$points[$key]['rate'] = $rate;
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
				
		if(!empty($points)){
			$json['type'] = 'FeatureCollection';
			
			$position = $points[0];
						
			$json['position'] = array();
			
			$json['position']['location'] = array($position['lat'], $position['lng']);
									
			$json['features'] = array();
			
			foreach($points as $point){
				if(!empty($rate[$point['pointId']])){
				
					$workingHours = array();
					$workingHoursHtml = '';
			
					$countDays = 0;
					
					$price = $rate[$point['pointId']]['rateValueWithVat'];
					
					# Учёт перевеса по тарифу
					if($totalWeightKg > $this->config->get('shipping_5post_baseRate')){
						
						$preponderance = $totalWeightKg - $this->config->get('shipping_5post_baseRate');
						$preponderance = (int)$preponderance;
						$preponderanceOverweight = $preponderance*$this->config->get('shipping_5post_overweight');
						
						$rateExtraValueWithVat = $preponderanceOverweight*$rate[$point['pointId']]['rateExtraValueWithVat'];
						
						$price = $price + $rateExtraValueWithVat;
					}
					
					# Сроки доставки
					$termsUnserial = unserialize($point['deliverySL']);
					$terms = $termsUnserial[0]['Sl'];
					
					if($this->config->get('shipping_5post_increase')){
						$terms = $terms + $this->config->get('shipping_5post_increase');
					}
					
					if(!empty($point['workHours'])){
						
						$point['workHours'] = unserialize($point['workHours']);
						
						if(!empty($point['workHours'])){
							$workingHours[0]['day'] = "Пн: " . $point['workHours'][0]['O'] . " - " . $point['workHours'][0]['C'];
							$workingHours[1]['day'] = "Вт: " . $point['workHours'][1]['O'] . " - " . $point['workHours'][1]['C'];
							$workingHours[2]['day'] = "Ср: " . $point['workHours'][2]['O'] . " - " . $point['workHours'][2]['C'];
							$workingHours[3]['day'] = "Чт: " . $point['workHours'][3]['O'] . " - " . $point['workHours'][3]['C'];
							$workingHours[4]['day'] = "Пт: " . $point['workHours'][4]['O'] . " - " . $point['workHours'][4]['C'];
							$workingHours[5]['day'] = "Сб: " . $point['workHours'][5]['O'] . " - " . $point['workHours'][5]['C'];
							$workingHours[6]['day'] = "Вс: " . $point['workHours'][6]['O'] . " - " . $point['workHours'][6]['C'];
							
							ksort($workingHours);
						}
					}

					# Наценка
					if(($this->config->get('shipping_5post_markup_type') == 1) && ($this->config->get('shipping_5post_markup')) > 0){
						$percent = $price*$this->config->get('shipping_5post_markup')/100;
						$price = $percent + $price;
					}elseif(($this->config->get('shipping_5post_markup_type') == 0) && ($this->config->get('shipping_5post_markup') > 0)){
						$price = $this->config->get('shipping_5post_markup') + $price;
					}	
					
					$json['features'][] = array(
						'type'       => 'Feature',
						'id'         => $point['pointId'],
						'geometry'   => array('type' => 'Point', 'coordinates' => array($point['lat'], $point['lng'])),
						'properties' => array(
							'address'     	=> $point['fullAddress'],
							'cashAllowed'   => $point['cashAllowed'],
							'cardAllowed'   => $point['cardAllowed'],
							'workingHours'	=> $workingHours,
							'description' 	=> $point['additional'],
							'id'          	=> $point['pointId'],
							'rate_id'		=> $rate[$point['pointId']]['rate_id'],
							'terms'			=> 'срок ' . $terms . ' дн.',
							'price'			=> $this->currency->format($this->tax->calculate(round($price, 0), 0, $this->config->get('config_tax')), $this->session->data['currency'])
						),
						'options' => array(
							'iconLayout'		=> 'default#imageWithContent',
							'iconImageHref'		=> '../../../../image/catalog/5post/' . $point['type'] . '.svg',
							'iconImageSize'		=> array(35, 45),
							'iconImageOffset'	=>  array(-10, -43)
						)
					);
				}
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	#Получение списка ПВЗ
	public function getPointsAdmin(){
		
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		
		$this->load->model('extension/shipping/5post');
		
		$json = array();
		
		if(!empty($this->request->get['pointId'])){
			$shipping_address = $this->model_extension_shipping_5post->getShippingAddress(trim($this->request->get['pointId']));
		}else{
			$this->load->model('checkout/order');
			
			$order_info_main = $this->model_checkout_order->getOrder($this->request->get['order_id']);
			
			$shipping_address['code'] = $this->model_extension_shipping_5post->getZoneCode($order_info_main['shipping_zone_id']);
			$shipping_address['city'] = $order_info_main['shipping_city'];
		}
		
		$order_info = $this->model_extension_shipping_5post->getOrder5post($this->request->get['order_id']);
						
		$goods = unserialize($order_info['goods']);
		
		#Габариты и вес
		$totalWeight = $goods['weight'];
		$maxCellDimensionsHash = $this->model_extension_shipping_5post->makeDimensionsHash($goods['width'], $goods['height'], $goods['length']);
		
		$totalWeightKg = $this->weight->convert($totalWeight, 2, 1);
		
		# Местоположение и поинты
		if(isset($shipping_address['region'])){
			$cityData = $this->model_extension_shipping_5post->getCityFromPointsRegion(trim($shipping_address['city']), $shipping_address['region']);
		}else{
			$cityData = $this->model_extension_shipping_5post->getCityFromPointsCode(trim($shipping_address['city']), $shipping_address['code']);
		}
		
		$points = $this->model_extension_shipping_5post->getPoints($cityData, $maxCellDimensionsHash, $totalWeight);
		
		# Cache params
		$params = array();
		$params['city'] = $cityData['city'];
		$params['region'] = $cityData['region'];
		$params['maxCellDimensionsHash'] = $maxCellDimensionsHash;
		$params['totalWeight'] = $totalWeight;
		$params['rateType'] = $this->config->get('shipping_5post_rateType');
		
		$cachePrice = 'fivepost.shipping.deliveryPrice.' . md5(implode('', $params));
		$cacheRate = 'fivepost.shipping.deliveryRate.' . md5(implode('', $params));
		
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
					$points[$key]['rate'] = $rate;
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
				
		if(!empty($points)){
			$json['type'] = 'FeatureCollection';
			
			$position = $points[0];
						
			$json['position'] = array();
			
			$json['position']['location'] = array($position['lat'], $position['lng']);
									
			$json['features'] = array();
			
			foreach($points as $point){
				if(!empty($rate[$point['pointId']])){
				
					$workingHours = array();
					$workingHoursHtml = '';
								
					$countDays = 0;
					
					$price = $rate[$point['pointId']]['rateValueWithVat'];
					
					# Учёт перевеса по тарифу
					if($totalWeightKg > $this->config->get('shipping_5post_baseRate')){
						
						$preponderance = $totalWeightKg - $this->config->get('shipping_5post_baseRate');
						$preponderance = (int)$preponderance;
						$preponderanceOverweight = $preponderance*$this->config->get('shipping_5post_overweight');
						
						$rateExtraValueWithVat = $preponderanceOverweight*$rate[$point['pointId']]['rateExtraValueWithVat'];
						
						$price = $price + $rateExtraValueWithVat;
					}
					
					# Сроки доставки
					$termsUnserial = unserialize($point['deliverySL']);
					$terms = $termsUnserial[0]['Sl'];
					
					if($this->config->get('shipping_5post_increase')){
						$terms = $terms + $this->config->get('shipping_5post_increase');
					}
					
					if(!empty($point['workHours'])){
						
						$point['workHours'] = unserialize($point['workHours']);
						
						if(!empty($point['workHours'])){
							$workingHours['day0'] = "Пн: " . $point['workHours'][0]['O'] . " - " . $point['workHours'][0]['C'];
							$workingHours['day1'] = "Вт: " . $point['workHours'][1]['O'] . " - " . $point['workHours'][1]['C'];
							$workingHours['day2'] = "Ср: " . $point['workHours'][2]['O'] . " - " . $point['workHours'][2]['C'];
							$workingHours['day3'] = "Чт: " . $point['workHours'][3]['O'] . " - " . $point['workHours'][3]['C'];
							$workingHours['day4'] = "Пт: " . $point['workHours'][4]['O'] . " - " . $point['workHours'][4]['C'];
							$workingHours['day5'] = "Сб: " . $point['workHours'][5]['O'] . " - " . $point['workHours'][5]['C'];
							$workingHours['day6'] = "Вс: " . $point['workHours'][6]['O'] . " - " . $point['workHours'][6]['C'];
							
							ksort($workingHours);
						}
					}

					# Наценка
					if(($this->config->get('shipping_5post_markup_type') == 1) && ($this->config->get('shipping_5post_markup')) > 0){
						$percent = $price*$this->config->get('shipping_5post_markup')/100;
						$price = $percent + $price;
					}elseif(($this->config->get('shipping_5post_markup_type') == 0) && ($this->config->get('shipping_5post_markup') > 0)){
						$price = $this->config->get('shipping_5post_markup') + $price;
					}
					
					$json['features'][] = array(
						'type'       => 'Feature',
						'id'         => $point['pointId'],
						'geometry'   => array('type' => 'Point', 'coordinates' => array($point['lat'], $point['lng'])),
						'properties' => array(
							'address'     	=> $point['fullAddress'],
							'cashAllowed'   => $point['cashAllowed'],
							'cardAllowed'   => $point['cardAllowed'],
							'workingHours'	=> $workingHours,
							'description' 	=> $point['additional'],
							'id'          	=> $point['pointId'],
							'rate_id'		=> $rate[$point['pointId']]['rate_id'],
							'terms'			=> 'срок ' . $terms . ' дн.',
							'priceInt'		=> round($price, 0), 
							'price'			=> $this->currency->format($this->tax->calculate(round($price, 0), 0, $this->config->get('config_tax')), $order_info['currency'])
						),
						'options' => array(
							'iconLayout'		=> 'default#imageWithContent',
							'iconImageHref'		=> '../../../../image/catalog/5post/' . $point['type'] . '.svg',
							'iconImageSize'		=> array(35, 45),
							'iconImageOffset'	=>  array(-10, -43)
						)
					);
				}
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function autocomplete() {
		$this->load->model('extension/shipping/5post');
		
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/category');
									
			$filter_name = $this->request->get['filter_name'];

			$results = $this->model_extension_shipping_5post->getCity($filter_name);
						
			foreach ($results as $result) {
				
				$region = array();
				
				if($result['code']){
					$region = $this->model_extension_shipping_5post->getZone($result['code']);
				}
				
				$name = $this->replateCity($result['city']);
				$regionMain = str_replace(' город','',$result['region']);
				
				$json[] = array(
					'value'  	 => $name,
					'country_id' => !empty($region) ? $region['country_id'] : $this->session->data['shipping_address']['country_id'],
					'zone_id' 	 => !empty($region) ? $region['zone_id'] : $this->session->data['shipping_address']['zone_id'],
					'name'    	 => strip_tags(html_entity_decode($name . ', ' . $regionMain , ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function replateCity($str){
		$tr = array(
			" г"=>""," п/ст"=>""," рп"=>""," ст-ца"=>"",
			" пгт"=>""," д"=>""," гп"=>""," аул"=>""," дп"=>"",
			" сл"=>""," м"=>""," с/п"=>""," нп"=>"",
			" х"=>""," м"=>""," с/п"=>""," ж/д_ст"=>"",
			" ст"=>""," с"=>"", " п"=>"",
		);

		return strtr($str,$tr);
	}
	
	public function save(){
		$json = array();
				
		if(isset($this->request->post['country_id'])){
			if(!empty($this->request->post['country_id'])){
				$this->session->data['shipping_address']['country_id'] = $this->request->post['country_id'];
			}
		}
		
		if(isset($this->request->post['zone_id'])){
			if(!empty($this->request->post['zone_id'])){
				$this->session->data['shipping_address']['zone_id'] = $this->request->post['zone_id'];
			}
		}
		
		if(isset($this->request->post['id'])){
			$this->session->data['fivepost']['pointFlag'] = true;
			$this->session->data['fivepost']['id'] = $this->request->post['id'];
			$this->session->data['fivepost']['rate_id'] = $this->request->post['rate_id'];
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function saveAdmin(){
		$json = array();
		
		$this->load->model('extension/shipping/5post');
		
		if(isset($this->request->post['pointId'])){
			$this->model_extension_shipping_5post->updateOrder($this->request->post['pointId'], $this->request->post['delivery_cost'], $this->request->post['order_id']);
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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
		
		$this->model_extension_shipping_5post->clear5post();
		$this->response->redirect($this->url->link('extension/shipping/5post/import', '', true));
	}
	
	public function getStatuses(){
		$this->load->model('extension/shipping/5post');
		
		$app = $this->getApp();
		
		$orderStatuses = $this->model_extension_shipping_5post->getOrderStatuses();
						
		$statusInformation = $app->getOrderStatus($orderStatuses,'senderOrderId');
		
		if($statusInformation->isSuccess()){
			$fivepostStatuses = $statusInformation->getResponse()->getOrderStatuses();
			
			while ($statusInfo = $fivepostStatuses->getNext()) {
				$status = $statusInfo->getStatus();
				
				if($statusInfo->getExecutionStatus()){
					$statusExecution = $statusInfo->getExecutionStatus();
				
					$statusOc = $this->model_extension_shipping_5post->getStatusLink($status, $statusExecution);				
				}else{
					$statusOc = $this->model_extension_shipping_5post->getStatusLink($status);
				}
				
				$order_id = str_replace('OC_', '', $statusInfo->getSenderOrderId());
				
				$this->model_extension_shipping_5post->updateStatus($statusOc, $order_id);
													
				if($this->config->get('shipping_5post_status_' . $statusOc) != 'non'){
					$this->model_extension_shipping_5post->updateStatusMainOrder($this->config->get('shipping_5post_status_' . $statusOc), $order_id);
				}
			}
		}
	}
	
	public function import($page = 0){
		$this->load->model('extension/shipping/5post');
		
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
				
				$this->response->redirect($this->url->link('extension/shipping/5post/cityFormation', '', true));
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
	}
}