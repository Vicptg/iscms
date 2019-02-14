<?php

$robots = "# For more information about the robots.txt standard, see:
# http://www.robotstxt.org/orig.html
# For syntax checking, see:
# http://tool.motoricerca.info/robots-checker.phtml

User-agent: *
Disallow: /" . NAME_ADMINISTRATOR . "/
Disallow: /" . NAME_ARTICLES . "/
Disallow: /" . NAME_CACHE . "/
Disallow: /" . NAME_INCLUDES . "/
Disallow: /" . NAME_LIBRARIES . "/
Disallow: /" . NAME_MODULES . "/
Disallow: /" . NAME_TEMPLATES . "/
Disallow: /" . NAME_UPLOAD . "/
Disallow: /" . NAME_PERSONAL . "/
Disallow: /" . NAME_PRIVATE . "/
Disallow: /.*/
Host: " . $template -> site . "
Crawl-delay: 10

Sitemap: " . $template -> url . "/sitemap.xml";

if (file_put_contents(PATH_SITE . DIRECTORY_SEPARATOR . 'robots.txt', $robots)) {
	echo 'completed!';
} else {
	echo 'uncompleted - error...';
}

?>