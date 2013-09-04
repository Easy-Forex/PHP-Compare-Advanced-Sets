PHP Compare Advanced Sets
=========================

Description
-----------
PHP script to compare sets of json encoded data in an associative array.

Originally developed to give a comparison of installed packages on Centos Servers.

* Sample data can be found in the unit test 'VersionComparisonTest.php'
* Working example (needs valid hosts) can be found in 'example.php'

Details
-------
### Input Format

The format of the data to pass to the CompareVersions function (1st param):

```
array(
	'group1' => '{"item_name":"item_version","item2_name":"item2_version"}',
	'group2' => '{"item_name":"item_version","item2_name":"item2_version"}',
)
```

### Base Group

A base comparison group can be passed to the CompareVersions function (2nd param) as
a string of the group name (input array key).

If the base group is not set the first group of the data array will be used.

If unsure of which base group is used it can be returned using the GetBaseGroup function.

### Return Data

The comparison script returns an array of 'missing' items, 'different' versions and 'extra' items
for each group compared to the base.

Example
-------
The example.php script will:
* Connect to a list of pre-set servers.
* Use a set command to retrieve and format a list of installed packages.
* Use 'CompareVersions' function to return an array of differences.
* List differences in a readable manner.

Unchanged (except adding valid hosts) the script will work for Centos, Fedora, Redhat

