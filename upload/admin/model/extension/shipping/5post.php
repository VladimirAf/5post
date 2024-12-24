<?php
# Разработчик: Кузнецов Богдан	
# ipol.ru
# ozon - служба доставки

class ModelExtensionShipping5post extends Model {
		
	public function clear5post() {
		$this->db->query("DELETE FROM ipol_5post_pickup_points");
		$this->db->query("DELETE FROM ipol_5post_pickup_points_rate");
		$this->db->query("DELETE FROM ipol_5post_city");
	}
	
	public function saveOrder($data, $order_id){
		$this->db->query("UPDATE ipol_5post_orders SET 
		fivepost_id = '" . $this->db->escape($data['fivepost_id']) . "',
		barcode = '" . $this->db->escape($data['barcode']) . "',
		fivepost_status = '" . $this->db->escape($data['fivepost_status']) . "',
		planned_receive_date = '" . $this->db->escape($data['planned_receive_date']) . "',
		sender_location = '" . $this->db->escape($data['sender_location']) . "',
		brand_name = '" . $this->db->escape($data['brand_name']) . "',
		undeliverable_option = '" . $this->db->escape($data['undeliverable_option']) . "',
		shipment_date = '" . $this->db->escape($data['shipment_date']) . "',
		goods = '" . serialize($data['goods']) . "',
		client_name = '" . $this->db->escape($data['client_name']) . "',
		client_email = '" . $this->db->escape($data['client_email']) . "',
		client_phone = '" . $this->db->escape($data['client_phone']) . "',
		is_beznal = '" . (int)$data['is_beznal'] . "',
		delivery_cost = '" . $this->db->escape($data['delivery_cost']) . "',
		payment_type = '" . $this->db->escape($data['payment_type']) . "',
		ok = '" . $this->db->escape($data['ok']) . "',
		items = '" . serialize($data['items']) . "'
		WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function updateStatus($fivepost_status, $order_id){
		$this->db->query("UPDATE ipol_5post_orders SET fivepost_status = '" . $this->db->escape($fivepost_status) . "' WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function updateStatusMainOrder($order_status_id, $order_id){
		$this->db->query("UPDATE " . DB_PREFIX . "order SET order_status_id = '" . (int)$order_status_id . "' WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function setMessage($message, $order_id){
		$this->db->query("UPDATE ipol_5post_orders SET message = '" . $this->db->escape($message) . "' WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function addOrder($data, $order_id){
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
		is_beznal = '" . (int)$data['is_beznal'] . "', 
		delivery_cost = '" . (double)$data['delivery_cost'] . "', 
		price = '" . (double)$data['price'] . "',
		goods = '" . serialize($data['goods']) . "', 
		items = '" . serialize($data['items']) . "',
		currency = 'RUB', ok = 'N', uptime = '" . time() . "'
		");
	}
	
	public function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

		return $query->rows;
	}
	
	public function getPaymentCode($order_id) {
		$query = $this->db->query("SELECT payment_code FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['payment_code'];
	}
	
	public function getPaymentTypes($pointId){
		$query = $this->db->query("SELECT cardAllowed, cashAllowed FROM ipol_5post_pickup_points WHERE pointId = '" . $this->db->escape($pointId) . "'");
					
		return $query->row;
	}
	
	public function getFivepostId($order_id) {
		$query = $this->db->query("SELECT fivepost_id FROM ipol_5post_orders WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['fivepost_id'];
	}
	
	public function getOrderPaymentType($order_id) {
		$query = $this->db->query("SELECT payment_type FROM ipol_5post_orders WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['payment_type'];
	}
	
	public function getZoneCode($zone_id) {
		$query = $this->db->query("SELECT code FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row['code'];
	}
	
	public function getShippingAddress($pointId){
		$query = $this->db->query("SELECT city, region FROM ipol_5post_pickup_points WHERE pointId = '" . $this->db->escape($pointId) . "'");
		
		return array('city' => $query->row['city'], 'region' => $query->row['region']);
	}
	
	public function getCityFromPoints($filter_name, $region){
		$query = $this->db->query("SELECT * FROM ipol_5post_city WHERE city LIKE '%" . $this->db->escape($filter_name) . "%' AND code = '" . $this->db->escape($region) . "' LIMIT 1");
		
		return $query->row;
	}
	
	public function getPoints($data, $maxCellDimensionsHash, $totalWeight){
		$query = $this->db->query("SELECT * FROM ipol_5post_pickup_points WHERE city = '" . $this->db->escape($data['city']) . "' 
		AND region = '" . $this->db->escape($data['region']) . "' AND maxCellDimensionsHash > '" . (int)$maxCellDimensionsHash . "' 
		AND maxWeight > '" . (int)$totalWeight . "' AND extStatus = 'ACTIVE'");
		
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
	
	public function getOrder5post($order_id){
		$query = $this->db->query("SELECT * FROM ipol_5post_orders WHERE order_id = '" . (int)$order_id . "'");
		
		return $query->row;
	}
	
	public function getPointAddress($pointId){
		$query = $this->db->query("SELECT fullAddress FROM ipol_5post_pickup_points WHERE pointId = '" . $this->db->escape($pointId) . "'");
		
		if($query->num_rows){
			return $query->row['fullAddress'];
		}else{
			return '';
		}
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
	
	public function getWarehouse($test) {	
		$query = $this->db->query("SELECT * FROM ipol_5post_warehouse WHERE test = '" . (int)$test . "' ORDER BY added DESC LIMIT 1");
		
		return $query->row;
	}
	
	public function getCashAllowed($pointId){
		$query = $this->db->query("SELECT cashAllowed FROM ipol_5post_pickup_points WHERE pointId = '" . $this->db->escape($pointId) . "'");
					
		return $query->row['cashAllowed'];
	}
	
	public function getCardAllowed($pointId){
		$query = $this->db->query("SELECT cardAllowed FROM ipol_5post_pickup_points WHERE pointId = '" . $this->db->escape($pointId) . "'");
					
		return $query->row['cardAllowed'];
	}
	
	public function getWarehouses($test) {	
		$query = $this->db->query("SELECT * FROM ipol_5post_warehouse WHERE test = '" . (int)$test . "' ORDER BY added DESC");
		
		return $query->rows;
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
	
	public function addWarehouse($uuid, $data, $worktime, $test){
		$this->db->query("INSERT INTO ipol_5post_warehouse SET uuid = '" . $this->db->escape($uuid) . "', name = '" . $this->db->escape($data['name']) . "', 
		partnerId = '" . $this->db->escape($data['partnerId']) . "', region = '" . $this->db->escape($data['region']) . "', federaldistrict = '" . $this->db->escape($data['federaldistrict']) . "',
		zip = '" . $this->db->escape($data['zip']) . "', city = '" . $this->db->escape($data['city']) . "', street = '" . $this->db->escape($data['street']) . "', test = '" . (int)$test . "',
		house = '" . $this->db->escape($data['house']) . "', coordsX = '" . $this->db->escape($data['coordsX']) . "', coordsY = '" . $this->db->escape($data['coordsY']) . "', 
		phone = '" . $this->db->escape($data['phone']) . "', timezone = '" . $this->db->escape($data['timezone']) . "', worktime = '" . serialize($worktime) . "', added = NOW()");
	}
	
	public function getRateType(){
		$query = $this->db->query("SELECT DISTINCT rateType FROM ipol_5post_pickup_points_rate ORDER BY rateType ASC");
		
		if($query->num_rows){
			return $query->rows;
		}else{
			return array();
		}
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
	
	public function addPoint($data) {
		$workHours = $this->preparePointWorkHours($data['workHours']);
		$maxCellDimensionsHash = $this->makeDimensionsHash($data['cellLimits']['maxCellWidth'], $data['cellLimits']['maxCellHeight'], $data['cellLimits']['maxCellLength']);
		
		$data['lastMileWarehouse']['id'] = isset($data['lastMileWarehouse']['id']) ? $data['lastMileWarehouse']['id'] : '';
		$data['lastMileWarehouse']['name'] = isset($data['lastMileWarehouse']['name']) ? $data['lastMileWarehouse']['name'] : '';
		
		$data['address']['cityType'] = isset($data['address']['cityType']) ? $data['address']['cityType'] : '';
		$data['address']['metroStation'] = isset($data['address']['metroStation']) ? $data['address']['metroStation'] : '';
		$data['address']['house'] = isset($data['address']['house']) ? $data['address']['house'] : '';
		$data['address']['street'] = isset($data['address']['street']) ? $data['address']['street'] : '';
		
		if($data['cellLimits']['maxWeight'] > 0){
			$data['cellLimits']['maxWeight'] = $data['cellLimits']['maxWeight']/1000;
		}
		
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
	
	public function makeDimensionsHash($a, $b, $c) {
        $arr = [$a, $b, $c];

        array_walk($arr, function (&$val, $key) {$val = (int)floor($val / 10);});
        sort($arr);

        return ($arr[0] + $arr[1]*1000 + $arr[2]*1000000);
    }
	
	public function getWhRegions(){
		$wh_regions[] = "Выберите регион";
		$wh_regions[] = "Республика Адыгея";
		$wh_regions[] = "Республика Башкортостан";
		$wh_regions[] = "Республика Бурятия";
		$wh_regions[] = "Республика Алтай";
		$wh_regions[] = "Республика Дагестан";
		$wh_regions[] = "Республика Ингушетия";
		$wh_regions[] = "Кабардино-Балкарская Республика";
		$wh_regions[] = "Республика Калмыкия";
		$wh_regions[] = "Карачаево-Черкесская Республика";
		$wh_regions[] = "Республика Карелия";
		$wh_regions[] = 'Республика Коми';
		$wh_regions[] = 'Республика Марий Эл';
		$wh_regions[] = 'Республика Мордовия';
		$wh_regions[] = 'Республика Саха (Якутия)';
		$wh_regions[] = 'Республика Северная Осетия - Алания';
		$wh_regions[] = 'Республика Татарстан (Татарстан)';
		$wh_regions[] = 'Республика Тыва';
		$wh_regions[] = 'Удмуртская Республика';
		$wh_regions[] = 'Республика Хакасия';
		$wh_regions[] = 'Чеченская Республика';
		$wh_regions[] = 'Чувашская Республика - Чувашия';
		$wh_regions[] = 'Алтайский край';
		$wh_regions[] = 'Краснодарский край';
		$wh_regions[] = 'Красноярский край';
		$wh_regions[] = 'Приморский край';
		$wh_regions[] = 'Ставропольский край';
		$wh_regions[] = 'Хабаровский край';
		$wh_regions[] = 'Амурская область';
		$wh_regions[] = 'Архангельская область';
		$wh_regions[] = 'Астраханская область';
		$wh_regions[] = 'Белгородская область';
		$wh_regions[] = 'Брянская область';
		$wh_regions[] = 'Владимирская область';
		$wh_regions[] = 'Волгоградская область';
		$wh_regions[] = 'Вологодская область';
		$wh_regions[] = 'Воронежская область';
		$wh_regions[] = 'Ивановская область';
		$wh_regions[] = 'Иркутская область';
		$wh_regions[] = 'Калининградская область';
		$wh_regions[] = 'Калужская область';
		$wh_regions[] = 'Камчатский край';
		$wh_regions[] = 'Кемеровская область';
		$wh_regions[] = 'Кировская область';
		$wh_regions[] = 'Костромская область';
		$wh_regions[] = 'Курганская область';
		$wh_regions[] = 'Курская область';
		$wh_regions[] = 'Ленинградская область';
		$wh_regions[] = 'Липецкая область';
		$wh_regions[] = 'Магаданская область';
		$wh_regions[] = 'Московская область';
		$wh_regions[] = 'Мурманская область';
		$wh_regions[] = 'Нижегородская область';
		$wh_regions[] = 'Новгородская область';
		$wh_regions[] = 'Новосибирская область';
		$wh_regions[] = 'Омская область';
		$wh_regions[] = 'Оренбургская область';
		$wh_regions[] = 'Орловская область';
		$wh_regions[] = 'Пензенская область';
		$wh_regions[] = 'Пермский край';
		$wh_regions[] = 'Псковская область';
		$wh_regions[] = 'Ростовская область';
		$wh_regions[] = 'Рязанская область';
		$wh_regions[] = 'Самарская область';
		$wh_regions[] = 'Саратовская область';
		$wh_regions[] = 'Сахалинская область';
		$wh_regions[] = 'Свердловская область';
		$wh_regions[] = 'Смоленская область';
		$wh_regions[] = 'Тамбовская область';
		$wh_regions[] = 'Тверская область';
		$wh_regions[] = 'Томская область';
		$wh_regions[] = 'Тульская область';
		$wh_regions[] = 'Тюменская область';
		$wh_regions[] = 'Ульяновская область';
		$wh_regions[] = 'Челябинская область';
		$wh_regions[] = 'Забайкальский край';
		$wh_regions[] = 'Ярославская область';
		$wh_regions[] = 'г. Москва';
		$wh_regions[] = 'Санкт-Петербург';
		$wh_regions[] = 'Еврейская автономная область';
		$wh_regions[83] = 'Ненецкий автономный округ';
		$wh_regions[86] = 'Ханты-Мансийский автономный округ - Югра';
		$wh_regions[87] = 'Чукотский автономный округ';
		$wh_regions[89] = 'Ямало-Ненецкий автономный округ';
		$wh_regions[99] = 'Иные территории, включая город и космодром Байконур';
		
		return $wh_regions;
	}
}