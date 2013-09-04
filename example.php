<?php

/**
 * Find installed package list of each server
 */
$servers = array('user1@host1','user2@host2','user3@host3');

$transport = '/usr/bin/ssh';
$command = 'rpm -qa --qf "\"%{NAME}\":\"%{VERSION}\"," | rev | cut -c 2- | rev';

$installed_packages = array();
foreach($servers as $server){
	# Example: /usr/bin/ssh host1 'rpm -qa --qf "\"%{NAME}\":\"%{VERSION}\"," | rev | cut -c 2- | rev'
	$exec_command = $transport . " " . $server . " '" . $command . "'";

	$out = array();
	exec($exec_command, $out, $status);
	var_dump($out);

	if($status < 1){
		$installed_packages[$server] = '{' . $out[0] . '}'; // json package list
	}
}


/**
 * Compare installed package list with VersionComparison class
 */
require_once 'VersionComparison.php';
$server_details = VersionComparison::CompareVersions($installed_packages);

/**
 * Format the output
 */
echo "Details of Package Differences on Servers\n";
echo "Server List: " . implode(', ', $servers) . "\n";
// Base server unspecified find from function
echo "Base Server: " . VersionComparison::GetBaseGroup($installed_packages) . "\n";
foreach($server_details as $key => $value){
	echo "\nServer: $key\n";

	echo "Missing Packages:\n";
	echo "- " . implode("\n- ", $value['missing']) . "\n";

	echo "Different Package Versions:\n";
	echo "- " . implode("\n- ", array_map_assoc(function ($k, $v){ return "$k => $v"; },$value['different'])) . "\n";

	echo "Extra Packages Installed:\n";
	echo "- " . implode("\n- ", $value['extra']) . "\n";
}

/**
 * Imploding an associative array in PHP
 * http://stackoverflow.com/questions/6556985/imploding-an-associative-array-in-php
 */
function array_map_assoc($callback, $array){
	$result = array();

	foreach($array as $key => $value){
		$result[$key] = $callback($key, $value);
	}

	return $result;
}

?>
