<?php

$module -> data = array();

// для api вконтакте

if ($module -> settings -> api === 'vk') {
	
	if (isset($module -> settings -> disable)) {
		$module -> settings -> disable = json_decode( json_encode($module -> settings -> disable), true );
	}
	
	$result = file_get_contents('https://api.vk.com/method/wall.get', false, stream_context_create(array(
		'http' => array(
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',
			'content' => http_build_query(
				array(
					'owner_id' => $module -> settings -> id,
					//'count' => $module -> settings -> count,
					'access_token' => $module -> settings -> key,
					'v' => '5.85'
				)
			)
		)
	)));
	
	$result = json_decode($result);
	
	foreach ($result -> response -> items as $key => $item) {
		
		if (isset($module -> settings -> disable) && in_array($item -> id, $module -> settings -> disable)) {
			
			$module -> settings -> count++;
			
		} elseif (isset($module -> settings -> rules) && (
			($module -> settings -> rules === 'text' && !$item -> text) ||
			($module -> settings -> rules === 'images' && !$item -> attachments) ||
			($module -> settings -> rules === 'both' && (!$item -> text || !$item -> attachments))
		)) {
				
			$module -> settings -> count++;
			
		} elseif ($key < $module -> settings -> count) {
			
			//print_r($item);
			//echo '<br>---------------------------<br>';
			
			if (isset($module -> settings -> defaults)) {
				$l = $template -> lang;
				if (!$item -> text && $module -> settings -> defaults -> text -> $l) {
					$item -> text = $module -> settings -> defaults -> text -> $l;
				} elseif (!$item -> text && $module -> settings -> defaults -> text) {
					$item -> text = $module -> settings -> defaults -> text;
				}
				if (!$item -> images && $module -> settings -> defaults -> images -> $l) {
					$item -> images = $module -> settings -> defaults -> images -> $l;
				} elseif (!$item -> images && $module -> settings -> defaults -> images) {
					$item -> images = $module -> settings -> defaults -> images;
				}
				unset($l);
			}
			
			$module -> data[$key] = (object) array(
				'date' => $item -> date,
				'text' => $item -> text,
				'images' => array()
			);
			
			foreach ($item -> attachments as $images) {
				if ($images -> type === 'photo') {
					foreach ($images -> photo -> sizes as $image) {
						if ($image -> type === 'x') {
							$module -> data[$key] -> images[] = $image -> url;
						}
					}
				}
			}
			
		}
		
	}
	
	unset($result);
	
}

// для api instagram

if ($module -> settings -> api === 'instagram') {
	
	if (empty($module -> settings -> id)) {
		$module -> settings -> id = substr($module -> settings -> key, 0, strpos($module -> settings -> key, '.'));
	}
	
	$result = curl_init(); // инициализация cURL подключения
	curl_setopt( $result, CURLOPT_URL, "https://api.instagram.com/v1/users/" . $module -> settings -> id . "/media/recent?access_token=" . $module -> settings -> key ); // подключаемся
	curl_setopt( $result, CURLOPT_RETURNTRANSFER, 1 ); // просим вернуть результат
	curl_setopt( $result, CURLOPT_TIMEOUT, 15 );
	$temp = json_decode( curl_exec( $result ) ) -> data; // получаем и декодируем данные из JSON
	curl_close( $result ); // закрываем соединение
	$result = $temp;
	unset($temp);
	
	foreach ($result as $key => $item) {
		
		if (isset($module -> settings -> disable) && in_array($item -> id, $module -> settings -> disable)) {
			
			$module -> settings -> count++;
			
		} elseif ($key < $module -> settings -> count) {
			
			$module -> data[$key] = (object) array(
				'date' => $item -> created_time,
				'text' => $item -> caption -> text,
				'link' => $item -> link,
				'images' => array()
			);
			
			if ($item -> type === 'image' || $item -> type === 'video') {
				
				$module -> data[$key] -> images[] = $item -> images -> standard_resolution -> url;
				
				if ($item -> type === 'video') {
					$module -> data[$key] -> video = $item -> videos -> standard_resolution -> url;
				}
				
			} elseif ($item -> type === 'carousel') {
				
				foreach ($item -> carousel_media as $images) {
					$module -> data[$key] -> images[] = $images -> images -> standard_resolution -> url;
				}
				
			}
			
		}
		
	}
	
	unset($result);
	
}

?>