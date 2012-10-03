#!/bin/sh
#today=`date +%Y-%m-%0e`;
#now=`date +%s`;
#rm -rf old_crm;
#mv latest_crm old_crm;
#svn co http://svn.hssus.org/shakha/trunk latest_crm;
#rm public_html;
#ln -s latest_crm public_html
#cd "$HOME/www/system/application/config";
#cp config.php.default config.php;
#cp database.php.default database.php;
#chmod 0777 -R "$HOME/www/emails";
#mysqldump -ucrmhss_crm -pcrm crmhss_crm -C | gzip > "$HOME/backups/$today-$now.gz";
$HOME/scripts/backup.sh;
cd latest_crm;
svn update;
