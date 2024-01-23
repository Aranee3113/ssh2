<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Port Checker</title>  </head>
  <body>
    <h1>Check if a port is open</h1>
    <form method="post">
      <label for="ip_address">IP Address:</label>
      <input type="text" id="ip_address" name="ip_address"><br><br>
      <label for="port_number">Port Number:</label>
      <input type="text" id="port_number" name="port_number"><br><br>
      <input type="submit" value="Check">
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the IP address and port number from the form submission
        $ipAddress = $_POST['ip_address'];
        $portNumber = $_POST['port_number'];

        // Connect to the remote host using SSH
        $connection = ssh2_connect($ipAddress, $portNumber); // assumes SSH is running on port 22
        if (!$connection) {
            die("Failed to connect to remote host");
        }

        // Authenticate with the remote host using a username and password
        if (!ssh2_auth_password($connection, 'root', 'root')) { // replace with your own username and password
            die("Failed to authenticate with remote host");
        }

        // Execute the command to check if the port is open
        $command = "echo >/dev/tcp/$ipAddress/$portNumber && echo \"Port $portNumber is open\" || echo \"Port $portNumber is closed\"";
        $stream = ssh2_exec($connection, $command);

        // Read the output of the command
        stream_set_blocking($stream, true);
        $output = stream_get_contents($stream);

        // Close the SSH connection
        ssh2_disconnect($connection);

        // Display the result
        echo "<p>$output</p>";
    }
    ?>
  </body>
</html>

