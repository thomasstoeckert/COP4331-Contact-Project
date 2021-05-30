
<?php
  // Remove after testing
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: *");
  header('Access-Control-Allow-Methods: GET, POST');

	$inData = getRequestInfo();

	$ID = 0;
	$FirstName = "";
	$LastName = "";

	$conn = new mysqli("localhost", "Group15Admin", "ByVivec", "COP4331");
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("SELECT ID,FirstName,LastName FROM Users WHERE Login=? AND Password =?");
		$stmt->bind_param("ss", $inData["Login"], $inData["Password"]);
		$stmt->execute();
		$result = $stmt->get_result();

		if( $row = $result->fetch_assoc() )
		{
			returnWithInfo( $row['FirstName'], $row['LastName'], $row['ID'], $row['Login'] );
		}
		else
		{
			returnWithError("No Records Found");
		}

		$stmt->close();
		$conn->close();
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

	function returnWithError( $err )
	{
		$retValue = '{"ID":0,"FirstName":"","LastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $FirstName, $LastName, $ID, $Login )
	{
		$retValue = '{"ID":' . $ID . ',"FirstName":"' . $FirstName . '","LastName":"' . $LastName . '","Login":"' . $Login . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>
