<?php echo '<?xml version="1.0" encoding="<?php echo $encoding; ?>"'; ?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">

    <channel>

    <title><?php echo $feed_name; ?></title>
    <link><?php echo $feed_url; ?></link>
    <description><?php echo $page_description; ?></description>
    <dc:language><?php echo $page_language; ?></dc:language>
    <dc:creator><?php echo $creator_email; ?></dc:creator>
    <dc:rights>Copyright <?php echo gmdate("%Y", time()); ?></dc:rights>
    <dc:date><?php gmdate("%Y-%m-&#xdT;%H:%i:%s%Q", time()); ?></dc:date>
    <admin:generatorAgent rdf:resource="http://www.codeigniter.com/" />

    <?php foreach($data as $row): ?>

        <item>
          <title><?php echo xml_convert($row->title); ?></title>
          <link><?php echo $row->permalink; ?></link>
          <guid><?php echo $row->permalink; ?>#When:<?php echo gmdate("%H:%i:&#xsZ;", $row->date); ?></guid>
          <description><?php echo xml_convert($row->description); ?></description>
          <dc:subject><?php echo xml_convert($row->category); ?></dc:subject>
          <dc:date><?php echo gmdate("%Y-%m-&#xdT;%H:%i:%s%Q",$row->date); ?></dc:date>
        </item>

    <?php endforeach; ?>

    </channel>
</rss>