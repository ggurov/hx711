HX711
=====

raspberry pi HX711 weight scale interface
-----------------------------------------

this thing interfaces with 24-Bit Analog-to-Digital Converter (ADC) for Weigh Scales
https://github.com/sparkfun/HX711-Load-Cell-Amplifier/blob/master/datasheets/hx711_english.pdf

http://www.amazon.com/SMAKN-Weighing-Dual-channel-Conversion-Shieding/dp/B00FVGRZ42 = $10

Compile by running make, which effectively does: 
	gcc  -o HX711 HX711.c gb_common.o

Run it like so:

First time

./hx711 

It will display a bunch of stuff:

root@bare-rasberripi:~/hx711# ./hx711
0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 1 1 1 0 1 1 1 1 1 1 0 0 n:       7672     -
0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 1 1 1 1 1 0 1 1 1 1 1 0 n:       8060     -

*SNIP* 

0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 1 1 1 1 1 1 0 1 1 1 1 1 n:       8126     -
0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 1 1 1 1 1 0 1 1 1 1 1 1 n:       8062     -
0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 1 1 1 1 1 1 1 0 0 1 1 1 n:       8142     -
8038

The final number is the 2nd average after throwing out out of range readings.

If there's nothing on the scale, this is the zero, tare, whatever. the number itself is a 24bit representation that comes off the converter.

Running it like so:

root@bare-rasberripi:~/hx711# ./hx711 8038
-66
root@bare-rasberripi:~/hx711# ./hx711 8038
-87
root@bare-rasberripi:~/hx711# ./hx711 8038
-138

With the argument being the average, removes the debug info, and subtracts the 1st argument from the 2nd average. 
Effectively, this is the actual weight reading around 0

In the example above, it is negative, which is entirely normal. The load cell will drift 
depending on temperature and humidity.

To actually be able to convert to any usable reading, you would have to calibrate the scale 
using a known weight. Easiest thing to do is to use water and a measurement cup. Once you have a few readings at uniform increments
you can determine how heavy "1" is. 


some code has been borrowed from the gertboard distribution to define the memory locations for GPIO_SET, CLR, and IN0, gpio setup, etc. 

at the top of HX711.c there two defines:

 #define CLOCK_PIN       31
 #define DATA_PIN        30
 #define N_SAMPLES       64
 #define SPREAD          10

CLOCK pin goes to PD_CLK (pin 11 on HX711, and SCK pin on shield)
DATA  pin goes to DOUT (pin 12 on HX711, and DT pin on shield)
N_SAMPLES is the number of samples read from the board
SPREAD is percentage around the average that defines a valid reading, the readings are averaged twice, first time to get a basic average,
       and 2nd time to get a better average that's within the SPREAD % (over/under).

on my raspberry PI, i have this hooked up to the unpopulated P5 header (+5v, GND, GPIO30, GPIO31)

I tested this with Etekcifty 5kg scale (http://www.amazon.com/gp/product/B00DGLU1SG = $10)
open up the scale, gut the electronics, and the battery crap from the case, and you end up with the load cell element, nicely positioned with a base, etc)
This will work for basically any weight scale you can find, most of the work on the same principle.
The load cell has 4 wires coming out of, they are colored: red, black, white, green.

The header on HX711 shield are, hook the load cell wires up to 
E+ - red
E- - black

A- - green
A+ - white

I have no idea how to hook it up to 3 wire load cells, normally found in the bathroom floor scales. But there are only 3 wires, so it can't be too difficult.

One note to keep in mind, this code is designed to run at 80 samples per second, the shield comes defaulted to 10 samples per second, This will manifest itself as
the whole process taking a fairly long time to complete. 

To switch to 80 samples per second, one has to desolder the RATE pin from the pad it's soldered to, lift it and solder it to +vcc on HX711 shield (pin 15). 
By default, this pin is grounded, which sets HX711 to run at 10 samples per second

Inherent problem with raspberry PI's gpio and talking to something that requires strict timing like the HX711 chip, is that linux running on the raspberry pi 
is not a realtime operating system. This can cause errors here and there, so there's a few safeguards in the code to reset the chip, etc etc.
