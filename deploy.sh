#!/bin/bash

mkdir /tmp/$USER/
mkdir /tmp/$USER/web/
cp -R ~/rendu/webpg/* /tmp/$USER/web/
if ~/rendu/webpg/apachectl start ; then
	echo "Apache started..."
else
	echo "Apache failed..."
fi
if mysql_install_db ; then
	echo "Installing started..."
else
	echo "Installing DB failed..."
fi
if mysqld_safe&  then
	echo "mysqld_safe started..."
else
	echo "mysqld_safe failed..."
fi
mysqladmin -u root password 'toto'

qry=$(cat webpg24042011.sql)

echo "Executing the following query"
echo $qry

/usr/bin/mysql -u root -p << eof
$qry
eof
