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
      if ( ! preg_match( '/^(([01]?\d\d?|2[0-4]\d|25[0-5])\.){3}([01]?\d\d?|2[0-4]\d|25[0-5])$/', $_POST['IP'] ) )
        die( "Invalid IP" );

      if ( preg_match( '/192\.168[.]*/', $_POST['IP'] ) || preg_match( '/172\.[.]*/', $_POST['IP'] ) || preg_match( '/10\.[.]*/', $_POST['IP'] ) )
        die( "Private IP blocks are blocked" );
 
      if ( ! preg_match( '/[0-9]{1,5}/', $_POST['port'] ) )
        die( "Invalid port" );
 
      if ( $_POST['IP'] == "MY OWN IP" || $_POST['IP'] == "127.0.0.1" )
        die( "Not forwarding to myself" );
 
      $db = new PDO('sqlite:pReProx.db');
      if ( !$db || $db == null ) die( "error opening database" );
      $stmt = $db->query( "SELECT * FROM ports WHERE expires <= CURRENT_TIMESTAMP" );

      if ( $stmt == null )
        die( "Error fetching ports." );

      $port = $stmt->fetch(PDO::FETCH_OBJ);
      if ( $port == null )
        die( "Sorry, no free ports. Please try again later." );
 
      exec( "./newredir.sh " . $port->port . " " . $_POST['IP'] . " " . $_POST['port'] . "1800 &" );
      $db->exec( "UPDATE ports SET t_port = " . $_POST['port'] . ", t_ip = '" . $_POST['IP'] . "', expires=DATETIME(CURRENT_TIMESTAMP, '30 minutes') WHERE port = " . $port->port );
 
      echo( "Hooray, you are now reachable at " . $_SERVER['HTTP_HOST'] . ":" . $port->port . "!" );
    } else { ?>
    <form action="#" method="post">
      The IP to bind: <input type="text" name="IP" /><br/>
      The port to bind: <input type="text" name="port" /><br/>
      <input type="submit" name="bind" value="Bind!" />
    </form>
    <?php } ?>
    <footer>
      pReProx by <a href="http://lethemfind.us/community/user/4085-1nsignia/">S0lll0s aka 1nsignia</a><br/>
      Visit our friendly community at <a href="http://lethemfind.us">lethemfind.us</a>
    </footer>
</body>
</html>
