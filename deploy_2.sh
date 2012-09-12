#!/bin/bash

if [ -n "${1+x}" ] && [ -n "${2+x}" ]; then
   rm -rf /tmp/$USER/
   mkdir /tmp/$USER
   cp -r ~/rendu/webpg/ /tmp/$USER/
   /tmp/$USER/webpg/apachectl start && cp ~/rendu/webpg/my.cnf ~/.my.cnf
   mysql_install_db
   mysqld_safe &
   sleep 1
   mysqladmin -u root password $1
   echo "\. $2" > __script__
   mysql -u root -p < __script__
   rm __script__
else
   echo "Missing arguments."
   echo -e "Syntax is : .sh \033[4mpwd_to_database\033[0m \033[4msql_conf_file\033[0m"
fi
