<?php

require_once 'VersionComparison.php';

class VersionComparisonTest extends PHPUnit_Framework_TestCase {
	/**
	 * Version List Provider
	 *
	 * Provides List Array with Groups and Item : Version test information 
	 */
	public function version_provider(){
		return array(
			// Same Packages Installed on both Servers
			array(
				// List Array
				// Group => {"Item":"Version"}
				array(
					'Server-1' => '{"Package1":"1.8.1","Package2":"1.0.22","Package3":"1.1.4","Package4":"0.9","Package5":"2.1.31"}',
					'Server-2' => '{"Package1":"1.8.1","Package2":"1.0.22","Package3":"1.1.4","Package4":"0.9","Package5":"2.1.31"}',
				),
				// Expected Result
				array(
					'Server-2' => array(
						'missing' => array(), 
						'different' => array(), 
						'extra' => array()
					),
				),
			),
			// Same Packages Installed on both Servers but with Different Versions
			array(
				// List Array
				// Group => {"Item":"Version"}
				array(
					'Server-1' => '{"Package1":"1.8.1","Package2":"1.0.22","Package3":"1.1.4","Package4":"0.9","Package5":"2.1.31"}',
					'Server-2' => '{"Package1":"1.8.0","Package2":"1.0.22","Package3":"1.1.4","Package4":"0.8","Package5":"2.1.31"}',
				),
				// Expected Result
				array(
					'Server-2' => array(
						'missing' => array(), 
						'different' => array(
							"Package1" => "1.8.1",
							"Package4" => "0.9",
						), 
						'extra' => array()
					),
				),
			),
			// Certain Packages Not Installed in Server-2
			array(
				// List Array
				// Group => {"Item":"Version"}
				array(
					'Server-1' => '{"Package1":"1.8.1","Package2":"1.0.22","Package3":"1.1.4","Package4":"0.9","Package5":"2.1.31"}',
					'Server-2' => '{"Package1":"1.8.1","Package4":"0.9","Package5":"2.1.31"}',
				),
				// Expected Result
				array(
					'Server-2' => array(
						'missing' => array("Package2","Package3"), 
						'different' => array(), 
						'extra' => array(),
					),
				),
			),
			// Extra Packages Installed in Server-2
			array(
				// List Array
				// Group => {"Item":"Version"}
				array(
					'Server-1' => '{"Package1":"1.8.1","Package2":"1.0.22","Package3":"1.1.4","Package4":"0.9","Package5":"2.1.31"}',
					'Server-2' => '{"Package1":"1.8.1","Package2":"1.0.22","Package3":"1.1.4","Package4":"0.9","Package5":"2.1.31","extra-Package":"1.0.0"}',
				),
				// Expected Result
				array(
					'Server-2' => array(
						'missing' => array(), 
						'different' => array(), 
						'extra' => array("extra-Package")
					),
				),
			),
		);
	}

	/**
	 * Test Compare Version
	 *
	 * @dataProvider version_provider
	 */
	public function test__CompareVersion($list_array, $expected_result){
		$actual_result = VersionComparison::CompareVersions($list_array);
		$this->assertEquals($expected_result, $actual_result);
	}

}

?>
