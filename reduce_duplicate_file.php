<?php
// 1. solve current server status
// Get all file with distince name 
// dont bother about if it s symlink 
// if not we will query all other file have same name, remove it and create symlink again
// 2. solve the get email problems
$servername = "localhost";
$username = "root";
$password = "binhmatt2018";
$database_name = "suitecrm";

// Create connection
$conn = new mysqli($servername, $username, $password, $database_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "
	SELECT  n.id, e.name, n.filename FROM `notes` n 
	LEFT JOIN emails e ON n.parent_id = e.id
	WHERE e.name IS NOT NULL
	AND n.parent_id NOT IN ('7ec05348-7386-6d87-b40b-5aa0905177ca', '29c9f493-2b41-530b-fc8c-5694e1267eed', '2c6db0d1-6843-feb9-5583-5694e1f46257', 'acd0d03e-e494-d298-79ce-5a057236fb84', '61557a5b-c02e-4b48-8a19-5aacd5aee6b0', '5fe5259a-9fb3-b7a8-2473-5ae3eccd9c01', '2e166ef8-a8df-34e9-82d7-5694e1f008c3', '1a916e8d-db16-58d8-cbf8-59fc29f9e49f', 'e537912b-524c-4766-96d3-59cfa4ece333', '3c23be80-7d7c-54a8-1e35-5696387eab4d', 'c466d37b-2ac2-ce42-ba82-59e7e83f6e3b', '5a36a733-f6c1-39b3-a736-5a940feae542', '8d17bb23-939f-d480-8a75-5a940f8a7fe5', '30eab08a-bbd4-77c7-74fe-5694e1341f6f', 'acb8b967-6c65-4445-f464-59ffd089c3a8', '82675a55-a8ea-0439-7ad8-5af3b5b901e5', '6d1dbc2e-38f4-27d6-e08b-5694e1497038', '2361ca39-a862-e898-3cc7-5bb588b9adeb', '2b25ebd0-4c17-a3ca-d689-5694e1730700', 'c223decb-4033-e600-1841-5a00177bdf23', 'ec302586-cd96-e843-bd9b-5b25c5b0b321', '3742953d-1318-43cb-00e3-5bbaab707bcd', '180953f6-3dda-b10e-8f39-5bbbfe2bec38', 'd89d6ef0-411d-395a-710f-5bd15b3f34c0', '98ea8922-6a3f-42b0-e426-5bd15b13c7cb', '12fb3725-0581-cf2c-18ed-5bbbfe6b0089', '5ad80115-b756-ea3e-ca83-5abb005602bf')
	ORDER BY n.date_entered DESC
	LIMIT 1000
";
$result =  $conn->query($sql);
$notes = array();
$i=0;
echo $result->num_rows."hehehe";
if($result->num_rows > 0){
    while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $notes[$i]=$row;
        $i++;
    }
}
$solved = 0;
echo count($notes);
for ($i = 0 ; $i < count($notes); $i ++ ){
	$solved ++;
	$duplicates = 0;
	for ($j = $i + 1 ; $j < count($notes); $j ++ ){
		if (($notes[$i]['name'] == $notes[$j]['name']) && ($notes[$i]['filename'] == $notes[$j]['filename'])) {

			// check if file j is not symlink first
			if( is_link ( "/var/www/suitecrm/upload/".$notes[$j]['id'] )){
				echo "symlink rooif <br/>".$notes[$j]['id'];
				continue;
			}

			// check if file j is equal with file j
			if(filesize( "/var/www/suitecrm/upload/".$notes[$i]['id'] ) != filesize( "/var/www/suitecrm/upload/".$notes[$j]['id'] ) ){
				echo "filesize khong bawngf nhau<br/>".$notes[$j]['id'];
				continue;
			}
			//unlink the notej
			unlink("/var/www/suitecrm/upload/".$notes[$j]['id']);
			// create symlink notej
			if (!symlink("/var/www/suitecrm/upload/".$notes[$i]['id'], "/var/www/suitecrm/upload/".$notes[$j]['id'])) {
	            echo "KHong create dc symlink!";
	        }
			$duplicates ++;
			//unset($notes[$j]);
		}
	}
	echo "dup:".$duplicates ++."<br>";
}
echo "solved:".$solved;