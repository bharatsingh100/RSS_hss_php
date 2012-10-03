#!/bin/bash
for i in `cat /var/www/web2/web/emails/elists.txt`
do
  /usr/lib/mailman/bin/list_members -o /var/www/web2/web/emails/bounced/$i --nomail=bybounce $i
done

