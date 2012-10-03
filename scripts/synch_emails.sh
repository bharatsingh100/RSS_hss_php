#!/bin/bash
cd /var/www/web2/web/emails/synch/
for i in `ls`;
do
  echo "Processing file $i"
  /usr/lib/mailman/bin/sync_members -f $i $i
done

