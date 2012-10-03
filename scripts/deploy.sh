#!/bin/sh
today=`date +%Y-%m-%0e`;
now=`date +%s`;

# Backup database.
$HOME/scripts/backup.sh;

# Checkout new code
rm -rf latest_crm1
svn co http://subversion.hssus.org/crmhss/trunk latest_crm1;

# Setup
cd "$HOME/latest_crm1/";
cp .htaccess-default .htaccess;
cd "$HOME/latest_crm1/system/application/config";
cp config.php.default config.php;
cp database.php.default database.php;
chmod 0777 -R "$HOME/latest_crm1/emails";
chmod 0777 "$HOME/latest_crm1/system/logs";
chmod 0777 "$HOME/latest_crm1/system/cache";

# Move Folders
cd $HOME
rm -rf old_crm;
mv latest_crm old_crm;
mv latest_crm1 latest_crm;
