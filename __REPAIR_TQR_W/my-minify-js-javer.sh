#!/bin/sh

# Ce fichier permet de lancer une operation de minification des fichiers js en utilisant JAVA et l'API "Closure Compiler Service API" de Google
# Le script compile la liste des fichiers listés afin de les compiler puis d'enregistrer le resultat dans un autre fichier.


# Liste des chaines de recherche pour chaque type de fichiers (c.c, d,csam, s)
js_wlc_cc_dir=/var/www/vhosts/trenqr.com/www/deploy/raw/js/w/c.c/*.js
js_wlc_d_dir=/var/www/vhosts/trenqr.com/www/deploy/raw/js/w/d/*.js
js_wlc_csam_dir=/var/www/vhosts/trenqr.com/www/deploy/raw/js/w/csam/*.js
js_wlc_s_dir=/var/www/vhosts/trenqr.com/www/deploy/raw/js/w/s/*.js

js_rstd_cc_dir=/var/www/vhosts/trenqr.com/www/deploy/raw/js/r/c.c/*.js
js_rstd_d_dir=/var/www/vhosts/trenqr.com/www/deploy/raw/js/r/d/*.js
js_rstd_csam_dir=/var/www/vhosts/trenqr.com/www/deploy/raw/js/r/csam/*.js
js_rstd_ix_dir=/var/www/vhosts/trenqr.com/www/deploy/raw/js/r/ix/*.js
js_rstd_s_dir=/var/www/vhosts/trenqr.com/www/deploy/raw/js/r/s/*.js

# (1) FOR TESTS USE : 
# test=/var/www/vhosts/trenqr.com/www/deploy/data/*.sql
# (2) FOR TESTS USE : 
# (2) Il faut au préalable créer un fichier test.js.
# java -jar compiler.jar test.js --js_output_file test.min.js


#On supprime tous les anciens fichiers
for fn in /var/www/vhosts/trenqr.com/www/bart1/ext/public/js/w/*/*.js
do
	rm -f $fn
done
for fn in /var/www/vhosts/trenqr.com/www/bart1/ext/public/js/r/*/*.js
do
	rm -f $fn
done

# ------------ Fichiers JS : Fichiers Commons ------------
for fn in $js_wlc_cc_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar compiler.jar --js "$fn" --js_output_file "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/w/c.c/$(basename $fn)" --compilation_level WHITESPACE_ONLY --language_in ECMASCRIPT5 --language_out ECMASCRIPT5 
	chown mrtrenqr "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/w/c.c/$(basename $fn)"
done
for fn in $js_rstd_cc_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar compiler.jar --js "$fn" --js_output_file "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/r/c.c/$(basename $fn)" --compilation_level WHITESPACE_ONLY --language_in ECMASCRIPT5 --language_out ECMASCRIPT5 
	chown mrtrenqr "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/r/c.c/$(basename $fn)"
done

# ------------ Fichiers JS : Fichiers DataViewTemplate ------------
for fn in $js_wlc_d_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar compiler.jar --js "$fn" --js_output_file "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/w/d/$(basename $fn)" --compilation_level WHITESPACE_ONLY --language_in ECMASCRIPT5 --language_out ECMASCRIPT5 
	chown mrtrenqr "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/w/d/$(basename $fn)"
done
for fn in $js_rstd_d_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar compiler.jar --js "$fn" --js_output_file "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/r/d/$(basename $fn)" --compilation_level WHITESPACE_ONLY --language_in ECMASCRIPT5 --language_out ECMASCRIPT5 
	chown mrtrenqr "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/r/d/$(basename $fn)"
done

# ------------ Fichiers JS : Fichiers CSAM ------------
for fn in $js_wlc_csam_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar compiler.jar --js "$fn" --js_output_file "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/w/csam/$(basename $fn)" --compilation_level WHITESPACE_ONLY --language_in ECMASCRIPT5 --language_out ECMASCRIPT5 
	chown mrtrenqr "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/w/csam/$(basename $fn)"
done
for fn in $js_rstd_csam_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar compiler.jar --js "$fn" --js_output_file "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/r/csam/$(basename $fn)" --compilation_level WHITESPACE_ONLY --language_in ECMASCRIPT5 --language_out ECMASCRIPT5 
	chown mrtrenqr "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/r/csam/$(basename $fn)"
done

# ------------ Fichiers JS : Fichiers INDEX ------------
for fn in $js_rstd_ix_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar compiler.jar --js "$fn" --js_output_file "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/r/ix/$(basename $fn)" --compilation_level WHITESPACE_ONLY --language_in ECMASCRIPT5 --language_out ECMASCRIPT5 
	chown mrtrenqr "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/r/ix/$(basename $fn)"
done

# ------------ Fichiers JS : Fichiers Skeleton ------------
for fn in $js_wlc_s_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar compiler.jar --js "$fn" --js_output_file "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/w/s/$(basename $fn)" --compilation_level WHITESPACE_ONLY --language_in ECMASCRIPT5 --language_out ECMASCRIPT5 
	chown mrtrenqr "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/w/s/$(basename $fn)"
done
for fn in $js_rstd_s_dir
do
	# FOR TESTS USE : 
	# echo "$(basename $fn)"
	java -jar compiler.jar --js "$fn" --js_output_file "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/r/s/$(basename $fn)" --compilation_level WHITESPACE_ONLY --language_in ECMASCRIPT5 --language_out ECMASCRIPT5 
	chown mrtrenqr "/var/www/vhosts/trenqr.com/www/bart1/ext/public/js/r/s/$(basename $fn)"
done