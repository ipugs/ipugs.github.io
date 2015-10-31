<html>
<head>
<title>HTML and PHP together</title>
</head>
<body>
<?php

/**
     * Return a byte and split it out of the string
     *  - unsigned char
     *
     * @param string    $string String
     */
    function getByte(&$string)
    {
        $data = substr($string, 0, 1);

        $string = substr($string, 1);

        $data = unpack('Cvalue', $data);

        return $data['value'];
    }

    /**
     * Return an unsigned short and split it out of the string
     *  - unsigned short (16 bit, big endian byte order)
     *
     * @param string    $string String
     */
    function getShortUnsigned(&$string)
    {
        $data = substr($string, 0, 2);

        $string = substr($string, 2);

        $data = unpack('nvalue', $data);

        return $data['value'];
    }

    /**
     * Return a signed short and split it out of the string
     *  - signed short (16 bit, machine byte order)
     *
     * @param string    $string String
     */
    function getShortSigned(&$string)
    {
        $data = substr($string, 0, 2);

        $string = substr($string, 2);

        $data = unpack('svalue', $data);

        return $data['value'];
    }

    /**
     * Return a long and split it out of the string
     *  - unsigned long (32 bit, little endian byte order)
     *
     * @param string    $string String
     */
    function getLong(&$string)
    {
        $data = substr($string, 0, 4);

        $string = substr($string, 4);

        $data = unpack('Vvalue', $data);

        return $data['value'];
    }

    /**
     * Return a float and split it out of the string
     *
     * @param string    $string String
     */
    function getFloat(&$string)
    {
        $data = substr($string, 0, 4);

        $string = substr($string, 4);

        $array = unpack("fvalue", $data);

        return $array['value'];
    }

    /**
     * Return a string and split it out of the string
     *
     * @param string    $string String
     */
    function getString(&$string)
    {
        $data = "";

        $byte = substr($string, 0, 1);

        $string = substr($string, 1);

        while (ord($byte) != "0")
        {
                $data .= $byte;
                $byte = substr($string, 0, 1);
                $string = substr($string, 1);
        }

        return $data;
    }
// Constant
define('PACKET_SIZE', '1400');
define('SERVERQUERY_INFO', "\xFF\xFF\xFF\xFFTSource Engine Query");
define ('REPLY_INFO', "\x49");
define('SERVERQUERY_GETCHALLENGE', "\xFF\xFF\xFF\xFF\x57");
define ('REPLY_GETCHALLENGE', "\x41");
define('SERVERDATA_AUTH', 3) ;
define ('SERVERDATA_EXECCOMMAND', 2) ;

// Ip address and port
$_ip = $_POST['IP'] ; // server ip
$_port = $_POST['Port'] ; // server port
$_password = $_POST['Password'] ; // your rcon password
$s2 = '';
$command = $_POST['Command'] ; // the rcon command! Put the command you want here
print('$_ip');
print ('$_port');
print ('$_password');
print('$command');
$requestId = 1;

// open connection with server
$socket = fsockopen ('tcp://'.$_ip, $_port, $errno, $errstr, 30) ;

// Send auth packet

// Construct packet
$data = pack("VV", $requestId, SERVERDATA_AUTH).$_password.chr(0).$s2.chr(0);

// Prefix the packet by its size
$data = pack("V",strlen($data)).$data;

// Send packet
fwrite ($socket, $data, strlen($data)) ;

$requestId++ ;

// Check if auth is successful
$junk = fread ($socket, PACKET_SIZE) ;

$string = fread ($socket, PACKET_SIZE) ;
$size = getLong($string) ;
$id = getLong ($string) ;

if ($id == -1)
{
  // Error
  die ('Auth failed: bad password !') ;
}

// Sending the command and getting the answer
$data = pack ("VV", $requestId, SERVERDATA_EXECCOMMAND).$command.chr(0).$s2.chr(0) ;

// Prefix the packet by its size
$data = pack ("V", strlen ($data)).$data ;

// Send packet
fwrite ($socket, $data, strlen($data)) ;

$requestId++ ;

// Read response
$i = 0 ;
$text = '' ;
while ($string = fread($socket, 4))
{
  $info[$i]['size'] = getLong($string) ;
  $string = fread($socket, $info[$i]['size']) ;
  $info[$i]['id'] = getLong ($string) ;
  $info[$i]['type'] = getLong ($string) ;
  $info[$i]['s1'] = getString ($string) ;
  $info[$i]['s2'] = getString ($string) ;
  $text .= $info[$i]['s1'] ;
  $i++ ;
}
?>
</body>
</html>