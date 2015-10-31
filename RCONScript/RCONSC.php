<?php
	require __DIR__ . '\SourceQuery\bootstrap.php';
	use xPaw\SourceQuery\SourceQuery;
	
	// For the sake of this example
	Header( 'Content-Type: text/plain' );
	Header( 'X-Content-Type-Options: nosniff' );
	$IP = $_POST['IP'];
	// Edit this ->
	define( 'SQ_SERVER_ADDR', $IP );
	define( 'SQ_SERVER_PORT', 27015 );
	define( 'SQ_TIMEOUT',     1 );
	define( 'SQ_ENGINE',      SourceQuery::SOURCE );
	// Edit this <-
	
	$Query = new SourceQuery( );
	
	try
	{
		$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );

		$RCONPASS = $_POST['RCONPASS'];
		
		$Query->SetRconPassword( $RCONPASS );

		$COMMAND = $_POST['COMMAND'];
		
		var_dump( $Query->Rcon( $COMMAND ) );
	}
	catch( Exception $e )
	{
		echo $e->getMessage( );
	}
	finally
	{
		$Query->Disconnect( );
	}
?>