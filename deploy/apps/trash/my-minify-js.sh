#!/bin/sh

# Ce fichier permet de lancer une operation de minification des fichiers js en utilisant Python et l'API "Closure Compiler Service API" de Google
# Le script compile la liste des fichiers listés afin de les compiler puis d'enregistrer le resultat dans un autre fichier.


# Liste des chaines de recherche pour chaque type de fichiers (c.c, d, csam, s)
js_rstd_cc_dir=/var/www/vhosts/trenqr.com/beta/deploy/raw/js/r/c.c/*.js
js_rstd_d_dir=/var/www/vhosts/trenqr.com/beta/deploy/raw/js/r/d/*.js
js_rstd_csam_dir=/var/www/vhosts/trenqr.com/beta/deploy/raw/js/r/csam/*.js
js_rstd_s_dir=/var/www/vhosts/trenqr.com/beta/deploy/raw/js/r/s/*.js

# (1) FOR TESTS USE : 
# test=/var/www/vhosts/trenqr.com/beta/deploy/data/*.sql
# (2) FOR TESTS USE : 
# (2) Il faut au préalable créer un fichier test.js.
# python compile.py "$(cat -s test.js)" > output.js 2>&1

#On supprime tous les anciens fichiers
for fn in /var/www/vhosts/trenqr.com/beta/bart1/ext/public/js/r/*/*.js
do
	rm $fn
done

# ----- Fichiers JS : Fichiers Commons
for fn in $js_rstd_cc_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	python compile.py "$(cat -s $fn)" > "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/js/r/c.c/$(basename $fn)" 2>&1
done

# ----- Fichiers JS : Fichiers DataViewTemplate
for fn in $js_rstd_d_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	python compile.py "$(cat -s $fn)" > "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/js/r/d/$(basename $fn)" 2>&1
done

# ----- Fichiers JS : Fichiers CSAM
for fn in $js_rstd_csam_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	python compile.py "$(cat -s $fn)" > "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/js/r/csam/$(basename $fn)" 2>&1
done

# ----- Fichiers JS : Fichiers Skeleton
for fn in $js_rstd_s_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	python compile.py "$(cat -s $fn)" > "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/js/r/s/$(basename $fn)" 2>&1
done