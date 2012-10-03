#!/bin/bash
cd /var/www/web2/web/emails/
for i in `cat create-lists.txt`;
do
  echo "Processing list $i"
  /usr/lib/mailman/bin/newlist -q $i zzzabhi@gmail.com abhi1986
done
rm /var/www/web2/web/emails/create-lists.txt
cd /var/www/web2/web/emails/configs/
for i in `ls`;
do
  echo "Processing file $i"
  /usr/lib/mailman/bin/config_list -i $i $i
done
cd /var/www/web2/web/emails/configs_pass/
for i in `ls`;
do
  echo "Processing file $i"
  /usr/lib/mailman/bin/config_list -i $i $i
done

