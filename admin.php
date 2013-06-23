<?php
  require_once('config.php');
  session_start();
  $loggedin = false;

  if ( isset( $_POST['login'] ) && $_POST['username'] == USERNAME && $_POST['password'] == PASSWORD ) {
    $_SESSION['token'] = sha1( USERNAME . "megasecr3t" );
    $loggedin = true;
  } elseif ( isset( $_SESSION['token'] ) && $_SESSION['token'] == sha1( USERNAME . SECRET ) ) {
    $loggedin = true;
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>pReProx</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <a href="https://github.com/S0lll0s/pReProx"><img style="position: absolute; top: 0; left: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_left_white_ffffff.png" alt="Fork me on GitHub"></a>
  <header>
    <h1 id="title">pReProx - Admin Panel</h1>
    <p>
      A simple reverse-proxy Service using netcat, bash and PHP.
    </p>
  </header>
  <?php
    if ( $loggedin ) {
      $db = new PDO('sqlite:pReProx.db');
      if ( !$db || $db == null ) die( "error opening database" );

      if ( isset( $_GET[ 'delete' ] ) ) {
        $db->exec( "DELETE FROM ports WHERE port = " . $_GET[ 'delete' ] );
      }
      if ( isset( $_POST[ 'add' ] ) && isset( $_POST[ 'port' ] ) ) {
        $db->exec( "INSERT INTO ports ( port ) VALUES( " . $_POST[ 'port' ] . " )" );
      }
      if ( isset( $_POST[ 'range' ] ) && isset( $_POST[ 'start' ] ) && isset( $_POST[ 'end' ] ) ) {
        $query = "INSERT INTO ports (port) SELECT " . $_POST['start'] . " AS port\n";
        for ( $port = $_POST['start'] + 1; $port < $_POST['end'] + 1; $port++ )
          $query .= "UNION SELECT  " . $port . "\n";
        $db->exec( $query );
      }


      $stmt = $db->query( "SELECT CASE WHEN expires <= CURRENT_TIMESTAMP = 0 THEN 'yes' ELSE 'no' END AS running, port, t_ip || ':' || t_port AS target FROM ports" );

      if ( $stmt == null )
        die( "Error fetching ports." );
      ?>
      <table>
      <tr><th>port</th><th>runs?</th><th>actions</th><th>target</th></tr>
      <?php
      while( ( $port = $stmt->fetch(PDO::FETCH_OBJ) ) != null ) { ?>
        <tr><td><?php echo $port->port ?></td><td><?php echo $port->running ?></td><td><a href="?delete=<?php echo $port->port ?>">delete</a></td><td><?php echo $port->target ?></td></tr>
      <?php } ?>
      </table><br/>
      <form action="#" method="post">
        <input type="text" name="port" value="1338" /> <input type="submit" name="add" value="add port" />
      </form>
      <form action="#" method="post">
        <input type="text" name="start" value="1000" />-<input type="text" name="end" value="2000"/> <input type="submit" name="range" value="add ports" />
      </form>
    <?php  } else { ?>
      <form action="#" method="post">
        <input type="text" name="username" /><br/>
        <input type="password" name="password" /><br/>
        <input type="submit" name="login" value="log in" />
      </form>
    <?php } ?>
    <footer>
      <span id="left"><a href="index.php">back to index</a></span>
      pReProx by <a href="http://lethemfind.us/community/user/4085-1nsignia/">S0lll0s aka 1nsignia</a><br/>
      Visit our friendly community at <a href="http://lethemfind.us">lethemfind.us</a>
    </footer>
</body>
</html>
