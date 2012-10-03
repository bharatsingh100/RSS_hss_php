#!/bin/sh
today=`date +%Y-%m-%0e`;
now=`date +%s`;
export HOME=/var/www/web2
#mysqldump --skip-quick -ucrmhss_crm -pcrm crmhss_crm -C | gzip > "/home/crmhss/backups/$today-$now.gz";
mysqldump --skip-quick --skip-extended-insert --ignore-table=web2db1.ci_sessions -uweb2u1 -pmanu0420 web2db1 -C > "/var/www/web2/backups/svndb/crmhss_db.sql";
/usr/bin/svn ci /var/www/web2/backups/svndb/ -m "Adding Database @ $today-$now"
/usr/bin/svn up /var/www/web2/backups/svndb/
