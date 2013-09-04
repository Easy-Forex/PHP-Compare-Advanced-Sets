<?php
/**
 * Compare Versions from a list to find missing, different and extra packages
 */
class VersionComparison {

	/**
	 * Compare Version
	 *
	 * @param array $list_array Array of group (key) and json encoded item : version data (value)
	 * @param string $base_group Which group to use as base comparison (if not set GetBaseGroup)
	 */
	public static function CompareVersions($list_array = array(), $base_group = ''){
		$result = array();

		if(!empty($list_array)){

			// Check for base_group, if not found use key of first group
			if($base_group == ''){
				$base_group = self::GetBaseGroup($list_array);
			}
			$base_list = json_decode($list_array[$base_group], true);

			foreach($list_array as $key => $value){
				if($key != $base_group){
					$compare_list = json_decode($value, true);
					$result[$key] = self::Compare($base_list, $compare_list);
				}
			}
		}else{
			die('No Data Given');
		}

		return $result;
	}

	// Get base group from 1st item in list array
	public static function GetBaseGroup($list_array){
		reset($list_array);
		$result = key($list_array);
		return $result;
	}

	// Compare listA and listB and return missing, different and extra items
	private static function Compare($listA, $listB){
		$result = array();

		$result['missing'] = self::getMissing($listA, $listB);
		$result['different'] = self::getDifferent($listA, $listB);
		$result['extra'] = self::getExtra($listA, $listB);

		return $result;
	}

	// Get the diff between array keys (A->B) (package names)
	private static function getMissing($listA, $listB){
		$keysA = array_keys($listA);
		$keysB = array_keys($listB);

		$diff = array_diff($keysA, $keysB);

		return array_values($diff);
	}

	// Get diff between array key and value, remove non-existing in non-base
	private static function getDifferent($listA, $listB){
		$diff = array_diff_assoc($listA, $listB);

		$result = array();
		foreach($diff as $key => $value){
			if(isset($listB[$key])){
				$result[$key] = $value;
			}
		}
		
		return $result;
	}

	// Get the diff between array keys (B->A) (package names)
	private static function getExtra($listA, $listB){
		$keysA = array_keys($listA);
		$keysB = array_keys($listB);

		$diff = array_diff($keysB, $keysA);

		return array_values($diff);
	}

}

?>
