
POUR DÉMARRER "LTCROUTER"

	nohup /usr/bin/php -f /var/www/vhosts/trenqr.com/www/product/entities/mod.wamp/LtcRouterStart.php > /dev/null 2>&1 &

OU (Si on veut conserver les LOG) 

	nohup /usr/bin/php -f /var/www/vhosts/trenqr.com/www/product/entities/mod.wamp/LtcRouterStart.php > my_ltc_server.log 2>&1 &

PUIS 

	echo $! > my_ltc_ps_pid.txt

POUR TUER LE PROCESS

	kill -9 `cat my_ltc_ps_pid.txt`

POUR RETROUVER LE PID EN SE BASANT SUR TCP

fuser 8888/tcp

-----------------------------------------------------------------------------

Le fichier tqr_servic_ltc.conf est obselete !!!