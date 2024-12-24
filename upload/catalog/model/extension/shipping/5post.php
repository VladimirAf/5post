<?php
# Разработчик: Кузнецов Богдан	
# ipol.ru
# 5post - служба доставки

class ModelExtensionShipping5post extends Model {
	
	public function saveOrder($order_info){
		#data организация
		$data['order_id'] = $order_info['order_id'];
		$data['brand_name'] = $this->config->get('shipping_5post_brand');
		
		if(!empty($order_info['firstname']) && !empty($order_info['lastname'])){
			$data['client_name'] = $order_info['firstname'] . ' ' . $order_info['lastname'];
		}else{
			$data['client_name'] = $order_info['firstname'];
		}
		
		$data['client_phone'] = '';
		
		if(!empty($order_info['telephone'])){
			$data['client_phone'] = $order_info['telephone'];
		}
				
		$data['client_email'] = '';

		if(!empty($order_info['email'])){
			$data['client_email'] = $order_info['email'];
		}
		
		$test = $this->config->get('shipping_5post_test') ? 1 : 0;
		$warehouse = $this->getWarehouse($test);
		
		if(isset($this->session->data['fivepost']['id'])){
			$data['receiver_location'] = $this->session->data['fivepost']['id'];
		}else{
			$data['receiver_location'] = '';
		}
		
		$data['sender_location'] = @$warehouse['partnerId'];
		$data['delivery_cost'] = $this->session->data['shipping_method']['cost'];
		
		$data['undeliverable_option'] = $this->config->get('shipping_5post_undeliverableOption');
		
		# Goods
		$dimansions = $this->calcShipmentDimensions($this->cart->getProducts());
		
		$data['goods']['width'] = $dimansions['W'];
		$data['goods']['height'] = $dimansions['H'];
		$data['goods']['length'] = $dimansions['L'];
		$data['goods']['weight'] = ($this->cart->getWeight() > 0) ? $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), 2) : $this->config->get('shipping_5post_weightD');
						
		# Type Payment
		$keyPayment = false;
		
		if($this->config->get('shipping_5post_card_payment')){
			$keyPayment = array_search($order_info['payment_code'], $this->config->get('shipping_5post_card_payment'));
		}
								
		$cardAllowed = false;
		$cashAllowed = false;
		
		if(isset($this->session->data['fivepost']['id'])){
			if($keyPayment !== false){
				$cardAllowed = $this->getCardAllowed();
				
				if($cardAllowed == false){
					$keyPayment = array_search($order_info['payment_code'], $this->config->get('shipping_5post_cash_payment'));
														
					if($keyPayment !== false){
						$cashAllowed = $this->getCashAllowed();
					}
				}
			}else{
				if($this->config->get('shipping_5post_cash_payment')){
					$keyPayment = array_search($order_info['payment_code'], $this->config->get('shipping_5post_cash_payment'));
				}
				
				if($keyPayment !== false){
					$cashAllowed = $this->getCashAllowed();
				}
			}
		}else{
			$cardAllowed = true;
		}
		
		$data['price'] = 0;
		
		$this->load->model('checkout/order');
		//$order_products = $this->model_checkout_order->getOrderProducts($order_info['order_id']);
        $order_products = $this->getOrderProducts($order_info['order_id']);
			
		foreach($order_products as $product){
			$priceQ = ((int)$product['price'] + (int)$product['tax'])*(int)$product['quantity'];
			$data['price'] += $priceQ;				
		}
		
		$data['items'] = array();
						
		foreach ($this->cart->getProducts() as $key => $product) {
			$query = $this->db->query("SELECT sku FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product['product_id'] . "'");
			
			$data['items'][$key]['product_id'] = $product['product_id'];
			$data['items'][$key]['name'] = $product['name'];
			$data['items'][$key]['sku'] = $query->row['sku'];
			
			$data['items'][$key]['quantity'] = (int)$product['quantity'];
			
			$data['items'][$key]['price'] = (int)$order_products[$key]['tax']+(int)$order_products[$key]['price'];
			
			if($this->config->get('shipping_5post_nds')){
				$data['items'][$key]['nds'] = ((int)$order_products[$key]['tax']*100)/(int)$order_products[$key]['price'];
			}else{
				$data['items'][$key]['nds'] = $this->config->get('shipping_5post_tax');
			}
		}
		
		if($cardAllowed == true){			
			$data['payment_type'] = 'Card';
			$data['payment_value'] = $data['price'] + $data['delivery_cost'];
			$data['is_beznal'] = 0;
		}elseif($cashAllowed == true){
			$data['payment_type'] = 'Cash';
			$data['payment_value'] = $data['price'] + $data['delivery_cost'];
			$data['is_beznal'] = 0;
		}else{
			$data['payment_type'] = 'Bill';
			$data['payment_value'] = 0;
			$data['is_beznal'] = 1;
		}
		
		$data['delivery_cost'] = round($data['delivery_cost']);
		
		$this->db->query("INSERT INTO `ipol_5post_orders` SET 
		order_id = '" . (int)$data['order_id'] . "', 
		brand_name = '" . $this->db->escape($data['brand_name']) . "',
		client_name = '" . $this->db->escape($data['client_name']) . "', 
		client_phone = '" . $this->db->escape($data['client_phone']) . "', 
		client_email = '" . $this->db->escape($data['client_email']) . "',
		receiver_location = '" . $this->db->escape($data['receiver_location']) . "', 
		sender_location = '" . $this->db->escape($data['sender_location']) . "', 
		undeliverable_option = '" . $this->db->escape($data['undeliverable_option']) . "',
		payment_type = '" . $this->db->escape($data['payment_type']) . "', 
		payment_value = '" . (double)$data['payment_value'] . "', 
		delivery_cost = '" . (double)$data['delivery_cost'] . "', 
		price = '" . (double)$data['price'] . "',
		is_beznal = '" . (int)$data['is_beznal'] . "', 
		goods = '" . serialize($data['goods']) . "', 
		items = '" . serialize($data['items']) . "',
		currency = 'RUB', ok = 'N', uptime = '" . time() . "'
		");
		
		unset($this->session->data['fivepost']);
		
	}

    public function getOrderProducts($order_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

        return $query->rows;
    }
	
	public function getWarehouse($test) {	
		$query = $this->db->query("SELECT * FROM ipol_5post_warehouse WHERE test = '" . (int)$test . "' ORDER BY added DESC LIMIT 1");
		
		return $query->row;
	}
	
	function getQuote($address) {
		if ($this->config->get('shipping_5post_status')) {
			$status = true;
		} else {
			$status = false;
		}
        $status = true;
		$method_data = array();
				
		#Габариты и вес
		$totalWeight = ($this->cart->getWeight() > 0) ? $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), 2) : $this->config->get('shipping_5post_weightD');
        $dimansions = $this->calcShipmentDimensions($this->cart->getProducts());
		$maxCellDimensionsHash = $this->makeDimensionsHash($dimansions['W'], $dimansions['H'], $dimansions['L']);
		
		#Проверка ещё одного лимита
		$optLimitCellDimensionsHash = $this->makeDimensionsHash(360, 400, 610);
		$totalWeightKg = $this->weight->convert($totalWeight, 2, 1);
		
		if(($maxCellDimensionsHash > $optLimitCellDimensionsHash) or ($totalWeightKg > 15)){
			$this->log->write('5post калькуляция: Превышен лимит ячейки!');
			return $method_data;
		}
				
		# Местоположение и поинты
		$cityData = $this->getCityFromPoints($this->session->data['shipping_address']['city'], $this->session->data['shipping_address']['zone_code']);
		
		if(empty($cityData)){
			$status = false;
		}else{
			$points = $this->getPoints($cityData, $maxCellDimensionsHash, $totalWeight);
			
			# Cache params
			$params = array();
			$params['city'] = $cityData['city'];
			$params['region'] = $cityData['region'];
			$params['maxCellDimensionsHash'] = $maxCellDimensionsHash;
			$params['totalWeight'] = $totalWeight;
			$params['rateType'] = $this->config->get('shipping_5post_rateType');
						
			if(empty($points)){
				$status = false;
			}else{
				$cacheRate = 'fivepost.shipping.deliveryRate.' . md5(implode('', $params));
				$cachePrice = 'fivepost.shipping.deliveryPrice.' . md5(implode('', $params));
				
				if($this->config->get('shipping_5post_rateType') == 'MIN_PRICE'){
					
					if (! $rate = $this->cache->get($cacheRate)) {
						foreach($points as $key => $point){
							$rate[$point['pointId']] = $this->getRateMinPrice($point['pointId']);
						}
						
						$this->cache->set($cacheRate, $rate);
					}
					
					if (! $rateValues = $this->cache->get($cachePrice)) {
						foreach($points as $key => $point){
							$RateMinPriceRateValue = $this->getRateMinPriceRateValue($point['pointId']);
							if(!empty($RateMinPriceRateValue)){
								$rateValues[$RateMinPriceRateValue['id']] = $RateMinPriceRateValue['rateValueWithVat'];
							}
						}
						
						$this->cache->set($cachePrice, $rateValues);
					}
				}else{
						
					if (! $rate = $this->cache->get($cacheRate)) {
						foreach($points as $key => $point){
							$rate[$point['pointId']] = $this->getRateForType($point['pointId'], $this->config->get('shipping_5post_rateType'));
						}
						
						$this->cache->set($cacheRate, $rate);
					}
					
					if (! $rateValues = $this->cache->get($cachePrice)) {
						foreach($points as $key => $point){
							$RateForTypeRateValue = $this->getRateForTypeRateValue($point['pointId'], $this->config->get('shipping_5post_rateType'));
							if(!empty($RateForTypeRateValue)){
								$rateValues[$RateForTypeRateValue['id']] = $RateForTypeRateValue['rateValueWithVat'];
							}
						}
						
						$this->cache->set($cachePrice, $rateValues);
					}
					
				}
				
				if(empty($rateValues)){
					$status = false;
				}else{
					# Минимальная стоимость доставки
					$minPrice = min($rateValues);
					
					# Ключ минимальной доставки
					$rate_id = array_search(min($rateValues), $rateValues);
					
					$dataMinRate = $this->getDataMinRate($rate_id);
					
					# Учёт перевеса по тарифу
					if($totalWeightKg > $this->config->get('shipping_5post_baseRate')){
									
						# Пересчёт
						$preponderance = $totalWeightKg - $this->config->get('shipping_5post_baseRate');
						$preponderance = (int)$preponderance;
						$preponderanceOverweight = $preponderance*$this->config->get('shipping_5post_overweight');
												
						$rateExtraValueWithVat = $preponderanceOverweight*$dataMinRate['rateExtraValueWithVat'];
						
						$minPrice = $minPrice + $rateExtraValueWithVat;
					}
					
					$pointDate = $this->getPoint($dataMinRate['pointId']);
						
					# Сроки доставки
					$termsUnserial = unserialize($pointDate['deliverySL']);
					$terms = $termsUnserial[0]['Sl'];
					
					if($this->config->get('shipping_5post_increase')){
						$terms = $terms + $this->config->get('shipping_5post_increase');
					}
				}
				
			}
		}
		
		# Наценка
		if(($this->config->get('shipping_5post_markup_type') == 1) && ($this->config->get('shipping_5post_markup')) > 0){
			$percent = $minPrice*$this->config->get('shipping_5post_markup')/100;
			$minPrice = $percent + $minPrice;
		}elseif(($this->config->get('shipping_5post_markup_type') == 0) && ($this->config->get('shipping_5post_markup') > 0)){
			$minPrice = $this->config->get('shipping_5post_markup') + $minPrice;
		}

		if ($status) {
			$quote_data = array();

			$quote_data['5post'] = array(
				'code'         => '5post.5post',
				'title'        => $this->config->get('shipping_5post_name_pvz'),
				'cost'         => $minPrice,
				'tax_class_id' => 0,
				'fivepost'     => true,
				'text'         => '<i class="fivepost-min-price">' . $this->currency->format($this->tax->calculate($minPrice, 0, $this->config->get('config_tax')), $this->session->data['currency']) . '</i>, <i class="fivepost-terms">срок ' . $terms . ' дн.</i>'
			);

			$method_data = array(
				'code'       => '5post',
				'title'      => '',
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_5post_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
	
	public function updateOrder($pointId, $delivery_cost, $order_id){
		$delivery_cost = round($delivery_cost);
		
		$this->db->query("UPDATE ipol_5post_orders SET receiver_location = '" . $this->db->escape($pointId) . "', delivery_cost = '" . (double)$delivery_cost . "' WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function getShippingAddress($pointId){
				
		$query = $this->db->query("SELECT city, region FROM ipol_5post_pickup_points WHERE pointId = '" . $this->db->escape($pointId) . "' LIMIT 1");
		
		return array('city' => $query->row['city'], 'region' => $query->row['region']);
	}
	
	public function getZoneCode($zone_id) {
		$query = $this->db->query("SELECT code FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row['code'];
	}
	
	public function getOrder5post($order_id){
		$query = $this->db->query("SELECT * FROM ipol_5post_orders WHERE order_id = '" . (int)$order_id . "'");
		
		return $query->row;
	}
	
	public function getPoint($pointId){
		
		$query = $this->db->query("SELECT * FROM ipol_5post_pickup_points WHERE pointId = '" . $this->db->escape($pointId) . "'");
					
		return $query->row;
	}
	
	public function getCashAllowed(){
		
		$query = $this->db->query("SELECT cashAllowed FROM ipol_5post_pickup_points WHERE pointId = '" . $this->db->escape($this->session->data['fivepost']['id']) . "'");
					
		return $query->row['cashAllowed'];
	}
	
	public function getCardAllowed(){
		
		$query = $this->db->query("SELECT cardAllowed FROM ipol_5post_pickup_points WHERE pointId = '" . $this->db->escape($this->session->data['fivepost']['id']) . "'");
					
		return $query->row['cardAllowed'];
	}
			
	public function getCity($filter_name){
		$query = $this->db->query("SELECT * FROM ipol_5post_city WHERE city LIKE '%" . $this->db->escape($filter_name) . "%' ORDER BY isCity DESC LIMIT 5");
		
		return $query->rows;
	}
	
	public function getRateMinPrice($pointId){
		$query = $this->db->query("SELECT * FROM ipol_5post_pickup_points_rate WHERE pointId = '" . $this->db->escape($pointId) . "' ORDER BY rateValueWithVat DESC LIMIT 1");
		
		return $query->row;
	}
	
	public function getRateMinPriceRateValue($pointId){
		$query = $this->db->query("SELECT rateValueWithVat, rate_id FROM ipol_5post_pickup_points_rate WHERE pointId = '" . $this->db->escape($pointId) . "' ORDER BY rateValueWithVat DESC LIMIT 1");
		
		if($query->num_rows){
			return array('rateValueWithVat' => $query->row['rateValueWithVat'], 'id' => $query->row['rate_id']);
		}else{
			return array();
		}
	}
	
	public function getRateForType($pointId, $rateType){
		$query = $this->db->query("SELECT * FROM ipol_5post_pickup_points_rate WHERE pointId = '" . $this->db->escape($pointId) . "' AND rateType = '" . $this->db->escape($rateType) . "' ORDER BY rateValueWithVat DESC LIMIT 1");
		
		return $query->row;
	}
	
	public function getRateForTypeRateValue($pointId, $rateType){
		$query = $this->db->query("SELECT rateValueWithVat, rate_id FROM ipol_5post_pickup_points_rate WHERE pointId = '" . $this->db->escape($pointId) . "' AND rateType = '" . $this->db->escape($rateType) . "' ORDER BY rateValueWithVat DESC LIMIT 1");
		
		if($query->num_rows){
			return array('rateValueWithVat' => $query->row['rateValueWithVat'], 'id' => $query->row['rate_id']);
		}else{
			return array();
		}
	}
	
	public function getDataMinRate($rate_id){
		$query = $this->db->query("SELECT * FROM ipol_5post_pickup_points_rate WHERE rate_id = '" . (int)$rate_id . "'");
		
		return $query->row;
	}
	
	public function getCityFromPoints($filter_name, $code){
		$query = $this->db->query("SELECT * FROM ipol_5post_city WHERE city LIKE '%" . $this->db->escape($filter_name) . "%' AND code = '" . $this->db->escape($code) . "' LIMIT 1");
		
		return $query->row;
	}
	
	public function getCityFromPointsRegion($filter_name, $region){
		$query = $this->db->query("SELECT * FROM ipol_5post_city WHERE city LIKE '%" . $this->db->escape($filter_name) . "%' AND region = '" . $this->db->escape($region) . "' LIMIT 1");
		
		return $query->row;
	}
	
	public function getCityFromPointsCode($filter_name, $code){
		$query = $this->db->query("SELECT * FROM ipol_5post_city WHERE city LIKE '%" . $this->db->escape($filter_name) . "%' AND code = '" . $this->db->escape($code) . "' LIMIT 1");
		
		return $query->row;
	}
	
	public function getPoints($data, $maxCellDimensionsHash, $totalWeight){
		$query = $this->db->query("SELECT * FROM ipol_5post_pickup_points WHERE city = '" . $this->db->escape($data['city']) . "' 
		AND region = '" . $this->db->escape($data['region']) . "' AND maxCellDimensionsHash > '" . (int)$maxCellDimensionsHash . "' 
		AND maxWeight > '" . (int)$totalWeight . "' AND extStatus = 'ACTIVE'");
		
		return $query->rows;
	}

	public function getZone($code){
		$sql = "SELECT zone_id, country_id FROM " . DB_PREFIX . "zone WHERE code = '" . $code . "'";
		
		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function makeDimensionsHash($a, $b, $c) {
        $arr = [$a, $b, $c];

        array_walk($arr, function (&$val, $key) {$val = (int)floor($val / 10);});
        sort($arr);

        return ($arr[0] + $arr[1]*1000 + $arr[2]*1000000);
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
	
	public function clear5post() {
		$this->db->query("DELETE FROM ipol_5post_pickup_points");
		$this->db->query("DELETE FROM ipol_5post_pickup_points_rate");
		$this->db->query("DELETE FROM ipol_5post_city");
	}
	
	public function addPoint($data) {
		$workHours = $this->preparePointWorkHours($data['workHours']);
		$maxCellDimensionsHash = $this->makeDimensionsHash($data['cellLimits']['maxCellWidth'], $data['cellLimits']['maxCellHeight'], $data['cellLimits']['maxCellLength']);
		
		$data['lastMileWarehouse']['id'] = isset($data['lastMileWarehouse']['id']) ? $data['lastMileWarehouse']['id'] : '';
		$data['lastMileWarehouse']['name'] = isset($data['lastMileWarehouse']['name']) ? $data['lastMileWarehouse']['name'] : '';
		
		$this->db->query("INSERT INTO ipol_5post_pickup_points SET pointId = '" . $this->db->escape($data['id']) . "', name = '" . $this->db->escape($data['name']) . "', 
		partnerName = '" . $this->db->escape($data['partnerName']) . "', type = '" . $this->db->escape($data['type']) . "', additional = '" . $this->db->escape($data['additional']) . "', 
		fullAddress = '" . $this->db->escape($data['fullAddress']) . "', country = '" . $this->db->escape($data['address']['country']) . "', zipCode = '" . $this->db->escape($data['address']['zipCode']) . "', 
		region = '" . $this->db->escape($data['address']['region']) . "', city = '" . $this->db->escape($data['address']['city']) . "', cityType = '" . $this->db->escape($data['address']['cityType']) . "', 
		street = '" . $this->db->escape($data['address']['street']) . "', house = '" . $this->db->escape($data['address']['house']) . "', lat = '" . (double)$data['address']['lat'] . "', 
		lng = '" . (double)$data['address']['lng'] . "', metroStation = '" . $this->db->escape($data['address']['metroStation']) . "', maxCellWidth = '" . (int)$data['cellLimits']['maxCellWidth'] . "', 
		maxCellHeight = '" . (int)$data['cellLimits']['maxCellHeight'] . "', maxCellLength = '" . (int)$data['cellLimits']['maxCellLength'] . "', maxWeight = '" . (int)$data['cellLimits']['maxWeight'] . "', 
		maxCellDimensionsHash = '" . (int)$maxCellDimensionsHash . "', returnAllowed = '" . (int)$data['returnAllowed'] . "', cashAllowed = '" . (int)$data['cashAllowed'] . "', 
		cardAllowed = '" . (int)$data['cardAllowed'] . "', loyaltyAllowed = '" . (int)$data['loyaltyAllowed'] . "', timezone = '" . $this->db->escape($data['timezone']) . "', 
		phone = '" . $this->db->escape($data['phone']) . "', extStatus = '" . $this->db->escape($data['extStatus']) . "', lastMileWarehouseId = '" . $this->db->escape($data['lastMileWarehouse']['id']) . "',
		lastMileWarehouseName = '" . $this->db->escape($data['lastMileWarehouse']['name']) . "', workHours = '" . serialize($workHours) . "', deliverySL = '" . serialize($data['deliverySL']) . "'");
		
		foreach($data['rate'] as $row){
			$this->db->query("INSERT INTO ipol_5post_pickup_points_rate SET pointId = '" . $this->db->escape($data['id']) . "', zone = '" . (int)$row['zone'] . "', 
			rateType = '" . $this->db->escape($row['rateType']) . "', rateValue = '" . (double)$row['rateValue'] . "', rateExtraValue = '" . (double)$row['rateExtraValue'] . "',
			rateCurrency = '" . $this->db->escape($row['rateCurrency']) . "', vat = '" . (int)$row['vat'] . "', rateValueWithVat = '" . (double)$row['rateValueWithVat'] . "',
			rateExtraValueWithVat = '" . (double)$row['rateExtraValueWithVat'] . "'");
		}
	}
	
	public function preparePointWorkHours($workHours) {
        $days = array_flip(['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN']);
        $result = [];

        foreach ($workHours as $day)
        {
            // Fuck seconds
            $tmpO = explode(':', $day['opensAt']);
            unset($tmpO[2]);

            $tmpC = explode(':', $day['closesAt']);
            unset($tmpC[2]);

            $result[$days[$day['day']]] = ['O' => implode(':', $tmpO), 'C' => implode(':', $tmpC)];
        }

        return $result;
    }
	
	public function getOrderStatuses(){
		$query = $this->db->query("SELECT order_id FROM ipol_5post_orders WHERE ok = 'Y' ORDER BY order_id DESC LIMIT 100");
		
		$arrOut = array();
		
		foreach($query->rows as $row){
			$arrOut[$row['order_id']] = 'OC_' . $row['order_id'];
		}
		
		return $arrOut;
	}
	
	public function getStatusLink($status, $execution=false) {
        $arDependenses = array(
            array('status'=>'NEW',        'oc'=>'new'),
            array('status'=>'APPROVED',   'oc'=>'valid'),
            array('status'=>'REJECTED',   'oc'=>'rejected'),
            array('status'=>'IN_PROCESS', 'oc'=>'warehouse',  '!execution'=>'PLACED_IN_POSTAMAT'),
            array('status'=>'IN_PROCESS', 'oc'=>'inpostamat', 'execution' =>'PLACED_IN_POSTAMAT'),
            array('status'=>'INTERRUPTED','oc'=>'interrupted','execution'=>'PROBLEM'),
            array('status'=>'INTERRUPTED','oc'=>'lost',       'execution'=>'LOST'),
            array('status'=>'UNCLAIMED',  'oc'=>'reclaim',    'execution'=>array('READY_FOR_WITHDRAW_FROM_PICKUP_POINT','PLACED_IN_POSTAMAT')),
            array('status'=>'UNCLAIMED',  'oc'=>'repickup',   'execution'=>array('WAITING_FOR_REPICKUP')),
            array('status'=>'UNCLAIMED',  'oc'=>'unclaimed',  '!execution'=>array('READY_FOR_WITHDRAW_FROM_PICKUP_POINT','PLACED_IN_POSTAMAT','WAITING_FOR_REPICKUP')),
            array('status'=>'CANCELLED',  'oc'=>'canceled'),
            array('status'=>'DONE',       'oc'=>'done'),
        );

        $ocStatus = false;
        foreach ($arDependenses as $arStatus){
            if($arStatus['status'] === $status){
                if(!array_key_exists('execution',$arStatus) && !array_key_exists('!execution',$arStatus)){
                    $ocStatus = $arStatus['oc'];
                    break;
                }
				
                if(array_key_exists('execution',$arStatus)){
                    if(is_array($arStatus['execution'])){
                        if(in_array($execution,$arStatus['execution'])){
                            $ocStatus = $arStatus['oc'];
                            break;
                        }
                    } elseif($arStatus['execution'] === $execution){
                        $ocStatus = $arStatus['oc'];
                        break;
                    }
                }
                if(array_key_exists('!execution',$arStatus)){
                    if(is_array($arStatus['execution'])){
                        if(!in_array($execution,$arStatus['execution'])){
                            $ocStatus = $arStatus['oc'];
                            break;
                        }
                    } elseif($arStatus['execution'] !== $execution){
                        $ocStatus = $arStatus['oc'];
                        break;
                    }
                }
            }
        }

        return $ocStatus;
    }
	
	public function getStatusName($status) {
		$arDependenses = array(
            array('status'=>'new', 'name'=>'Заявка отправлена'),
			array('status'=>'valid', 'name'=>'Заявка одобрена'),
			array('status'=>'rejected', 'name'=>'Ошибка в обработке заказа (отклонен)'),
			array('status'=>'warehouse', 'name'=>'Заказ на складе 5Post'),
			array('status'=>'inpostamat', 'name'=>'Заказ в ячейке постамата'),
			array('status'=>'interrupted', 'name'=>'Ошибка в обработке заказа (исполнение прервано)'),
			array('status'=>'lost', 'name'=>'Посылка утеряна'),
			array('status'=>'reclaim', 'name'=>'Заказ в ячейке, срок истек, получение возможно'),
			array('status'=>'repickup', 'name'=>'Заказ готов к повторной выдаче'),
			array('status'=>'unclaimed', 'name'=>'Заказ не был востребован'),
			array('status'=>'done', 'name'=>'Заказ получен покупателем'),
			array('status'=>'canceled', 'name'=>'Заказ отменен'),
		);
		
		$name = false;
		
		foreach ($arDependenses as $arStatus){
			 if($arStatus['status'] === $status){
				 $name = $arStatus['name'];
                 break;
			 }
		}
		
		return $name;
	}
	
	public function updateStatus($fivepost_status, $order_id){
		$this->db->query("UPDATE ipol_5post_orders SET fivepost_status = '" . $this->db->escape($fivepost_status) . "' WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function updateStatusMainOrder($order_status_id, $order_id){
		$this->db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . (int)$order_status_id . "' WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function getCodeRegion($region) {
		
		$query = $this->db->query("SELECT code FROM " . DB_PREFIX . "zone WHERE name = '" . $this->db->escape($region) . "'");
		
		$code = '';
		
		if($query->num_rows){
			return $query->row['code'];
		}else{
			if($region == 'Москва город'){
				$code = 'MOW';
			}elseif($region == 'Ямало-Ненецкий автономный округ'){
				$code = 'YAN';
			}elseif($region == 'Татарстан республика'){
				$code = 'TA';
			}elseif($region == 'Марий-Эл республика'){
				$code = 'ME';
			}elseif($region == 'Адыгея республика'){
				$code = 'AD';
			}elseif($region == 'Башкортостан республика'){
				$code = 'BA';
			}elseif($region == 'Мордовия республика'){
				$code = 'MO';
			}elseif($region == 'Чувашия республика'){
				$code = 'CU';
			}elseif($region == 'Санкт-Петербург город'){
				$code = 'SPE';
			}elseif($region == 'Ханты-Мансийский автономный округ'){
				$code = 'KHM';
			}
		}
		
		return $code;
	}
	
	public function addCityAll(){
		$query = $this->db->query("SELECT DISTINCT city, region, cityType FROM ipol_5post_pickup_points ORDER BY city ASC");
		
		if($query->num_rows){
			foreach($query->rows as $row){
				$code = $this->getCodeRegion($row['region']);
				
				$isCity = 0;
				
				if($row['cityType'] == 'г'){
					$isCity = 1;
				}
				
				$this->db->query("INSERT INTO ipol_5post_city SET city = '" . $this->db->escape($row['city']) . "', region = '" . $this->db->escape($row['region']) . "', code = '" . $this->db->escape($code) . "', isCity = '" . (int)$isCity . "'");
			}
			
			return true;
		}else{
			return false;
		}
	}
}