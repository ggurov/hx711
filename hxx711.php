#!/usr/bin/php
<?


$dt = 30;
$sck = 31;

function init($dt, $sck) {
	file_put_contents("/sys/class/gpio/export", $dt);
	file_put_contents("/sys/class/gpio/export", $sck);
	file_put_contents("/sys/class/gpio/gpio$dt/direction", "in");
	file_put_contents("/sys/class/gpio/gpio$sck/direction", "out");
	file_put_contents("/sys/class/gpio/gpio$dt/active_low", "0");
	file_put_contents("/sys/class/gpio/gpio$sck/active_low", "0");
	file_put_contents("/sys/class/gpio/gpio$sck/value", "0");
        file_put_contents("/sys/class/gpio/gpio$dt/edge", 'falling');
}

@init($dt, $sck);

$fds[$sck] = fopen("/sys/class/gpio/gpio$sck/value", 'w');
$fds[$dt] =  fopen("/sys/class/gpio/gpio$dt/value", 'r');

function sck_on($sck) {
	global $fds;
	fseek($fds[$sck], 0);
	fwrite($fds[$sck], '1', 1);
}

function sck_off($sck) {
	global $fds;
	fseek($fds[$sck], 0);
	fwrite($fds[$sck], '1', 0);
}

function dt_read($dt) {
	global $fds;
	fseek($fds[$dt], 0);
	return(fread($fds[$dt], 1));
}

function read_value($dt, $sck)
{
	$count = 0;
	$i = 0;

	sck_on($sck);
	usleep(100);
	sck_off($sck);

  	$count=0;
  	while(dt_read($dt));

  	for($i=0;$i<24;$i++)
	{
	  	sck_on($sck);
	  	$count=$count<<1;
		sck_off($sck);
	  	if(dt_read($dt)) {
			print "1";
			$count++;
		}
		print ".";
	}
  	sck_on($sck);
        $count=$count^0x800000;
	sck_off($sck);
	usleep(100);
	return($count);
}



print_r(read_value($dt, $sck));


