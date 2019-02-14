<?php

$articles = [
	'list' => [],
	'scan' => []
];

$articles['scan'] = fileconnect(PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> param, $module -> settings -> ext) or die;

if (!empty($module -> settings -> sort)) {
	moduleArticleSort($articles['scan'], $module -> settings -> sort, $module -> param);
}



if (
	is_string($module -> this) &&
	$module -> this !== 'all'
) {
	
	$articles['list'][$module -> this] = array_merge(
		[
			'id' => array_search($module -> this . '.' . $module -> settings -> ext, $articles['scan']),
			'name' => $module -> this,
			'date' => date(filectime( PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> param . DIRECTORY_SEPARATOR . $module -> this . '.' . $module -> settings -> ext ))
		],
		moduleArticleGet($module -> this, $module -> param, $module -> settings)
	);
	
} else {
	
	foreach ($articles['scan'] as $key => $item) {
		$item = substr($item, 0, strrpos($item, '.' . $module -> settings -> ext));
		if (
			!$item ||
			!isset($module -> settings -> page -> articles) ||
			!$module -> settings -> page -> articles ||
			(
				!$module -> settings -> page -> skip &&
				$key < $module -> settings -> page -> articles
			) ||
			(
				$module -> settings -> page -> skip &&
				$key >= $module -> settings -> page -> skip &&
				$key < ($module -> settings -> page -> articles + $module -> settings -> page -> skip)
			)
		) {
			$articles['list'][] = array_merge(
				[
					'id' => $key,
					'date' => date(filectime( PATH_ARTICLES . DIRECTORY_SEPARATOR . $module -> param . DIRECTORY_SEPARATOR . $item . '.' . $module -> settings -> ext ))
				],
				moduleArticleGet($item, $module -> param, $module -> settings)
			);
		}
	}
	
}



datareplacelang($articles['list']);
$module -> data = $articles['list'];

unset($articles);

?>