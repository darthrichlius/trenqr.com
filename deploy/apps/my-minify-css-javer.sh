#!/bin/sh

# Ce fichier permet de lancer une operation de minification des fichiers css en utilisant JAVA et l'application JAVA "YUI Compressor" de YAHOO!
# Le script compile la liste des fichiers listés afin de les compiler puis d'enregistrer le resultat dans un autre fichier.


# Liste des chaines de recherche pour chaque type de fichiers (c.c, d,csam, s)
css_wlc_cc_dir=/var/www/vhosts/trenqr.com/beta/deploy/raw/css/w/c.c/*.css
css_wlc_d_dir=/var/www/vhosts/trenqr.com/beta/deploy/raw/css/w/d/*.css
css_wlc_csam_dir=/var/www/vhosts/trenqr.com/beta/deploy/raw/css/w/csam/*.css
css_wlc_s_dir=/var/www/vhosts/trenqr.com/beta/deploy/raw/css/w/s/*.css

css_rstd_cc_dir=/var/www/vhosts/trenqr.com/beta/deploy/raw/css/r/c.c/*.css
css_rstd_d_dir=/var/www/vhosts/trenqr.com/beta/deploy/raw/css/r/d/*.css
css_rstd_csam_dir=/var/www/vhosts/trenqr.com/beta/deploy/raw/css/r/csam/*.css
css_rstd_s_dir=/var/www/vhosts/trenqr.com/beta/deploy/raw/css/r/s/*.css

# (1) FOR TESTS USE : 
# test=/var/www/vhosts/trenqr.com/beta/deploy/data/*.sql
# (2) FOR TESTS USE : 
# (2) Il faut au préalable créer un fichier test.css.
# java -jar compiler.jar test.css --css_output_file test.min.css


#On supprime tous les anciens fichiers
for fn in /var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/w/*/*.css
do
	rm -f $fn
done
for fn in /var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/r/*/*.css
do
	rm -f $fn
done

# ------------ Fichiers CSS : Fichiers Commons ------------
for fn in $css_wlc_cc_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar yuicompressor-2.4.8.jar "$fn" -o "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/w/c.c/$(basename $fn)" --charset utf-8
	chown mrtrenqr "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/w/c.c/$(basename $fn)"
done
for fn in $css_rstd_cc_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar yuicompressor-2.4.8.jar "$fn" -o "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/r/c.c/$(basename $fn)" --charset utf-8
	chown mrtrenqr "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/r/c.c/$(basename $fn)"
done

# ------------ Fichiers CSS : Fichiers DataViewTemplate ------------
for fn in $css_wlc_d_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar yuicompressor-2.4.8.jar "$fn" -o "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/w/d/$(basename $fn)" --charset utf-8
	chown mrtrenqr "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/w/d/$(basename $fn)"
done
for fn in $css_rstd_d_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar yuicompressor-2.4.8.jar "$fn" -o "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/r/d/$(basename $fn)" --charset utf-8
	chown mrtrenqr "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/r/d/$(basename $fn)"
done

# ------------ Fichiers CSS : Fichiers CSAM ------------
for fn in $css_wlc_csam_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar yuicompressor-2.4.8.jar "$fn" -o "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/w/csam/$(basename $fn)" --charset utf-8
	chown mrtrenqr "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/w/csam/$(basename $fn)"
done
for fn in $css_rstd_csam_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar yuicompressor-2.4.8.jar "$fn" -o "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/r/csam/$(basename $fn)" --charset utf-8
	chown mrtrenqr "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/r/csam/$(basename $fn)"
done

# ------------ Fichiers CSS : Fichiers Skeleton ------------
for fn in $css_wlc_s_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar yuicompressor-2.4.8.jar "$fn" -o "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/w/s/$(basename $fn)" --charset utf-8
	chown mrtrenqr "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/w/s/$(basename $fn)"
done
for fn in $css_rstd_s_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar yuicompressor-2.4.8.jar "$fn" -o "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/r/s/$(basename $fn)" --charset utf-8
	chown mrtrenqr "/var/www/vhosts/trenqr.com/beta/bart1/ext/public/css/r/s/$(basename $fn)"
done