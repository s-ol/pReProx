<!DOCTYPE html>
<html>
<head>
  <title>pReProx</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <a href="https://github.com/S0lll0s/pReProx"><img style="position: absolute; top: 0; left: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_left_white_ffffff.png" alt="Fork me on GitHub"></a>
  <header>
    <h1 id="title">pReProx</h1>
    <p>
      A simple reverse-proxy Service using netcat, bash and PHP.
    </p>
  </header>
  <?php
    if( isset( $_POST[ 'bind' ] ) && isset( $_POST[ 'IP' ] ) && isset( $_POST[ 'port' ] ) ) {
      if( ! preg_match( '/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/', $_POST['IP'] ) )
        die( "Invalid IP" );
 
      if( ! preg_match( '/[0-9]*/', $_POST['port'] ) )
        die( "Invalid port" );
 
      if( $_POST['IP'] == "MY OWN IP" )
        die( "Not forwarding to myself" ); //dont know why, but I guess this could end "bad"
 
      $db = sqlite_open( "pReProx.db" );
      if (!$db) die( "error opening database" );
      
      $res = sqlite_query( $db, "SELECT * FROM ports WHERE expires <= NOW() LIMIT 1" );
      
      if ( sqlite_num_rows( $res ) < 1 )
        die( "Sorry, no free ports. Please try again later." );
        
      $port = mysql_fetch_array( $res, SQLITE_ASSOC );
 
      exec( "./newredir.sh " . $port['port'] . " " . $_POST['IP'] . " " . $_POST['port'] . "1800 &" );
      mysql_query( "UPDATE ports SET expires=DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE port = " . $port->port );
 
      echo( "Hooray, you are now reachable at " . $_SERVER['HTTP_HOST'] . ":" . $port['port'] . "!" );
    } else { ?>
    <form action="#" method="post">
      The IP to bind: <input type="text" name="IP" /><br/>
      The port to bind: <input type="text" name="port" /><br/>
      <input type="submit" value="bind" name="Bind!" />
    </form>
    <?php } ?>
    <footer>pReProx by <a href="http://lethemfind.us/community/user/4085-1nsignia/">S0lll0s aka 1nsignia</a></footer>
</body>
</html>
