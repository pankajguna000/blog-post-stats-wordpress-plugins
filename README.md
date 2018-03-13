# Blog Post Stats WordPress Plugins


The Blog Post Stats for WordPress plugin allows you to track like and dislike count to particular blog post in form of thumbs-up and thumbs-down count. 
Along with these it also provide particular post statistic as word count, links ,images, views and visitor count.
Full list of features:

* Show count of like and dislike to particular post thus help you to update your post accordingly. 
* This plugin provide Content stats as word count, hyperlinks and number of images used in a post.
* Along with these it provide traffic Summary as views to post and visitor count with unique ip.
* This plugin comes with shortcode to place thumbs-up/thumbs-down count and post stats template inside post content.

Blog Post Stats is powered by inkthemes.com.
Click here to get [Premium Wordpress Themes](https://www.inkthemes.com).



# == Installation ==

This section describes how to install the plugin and get it working.

1. Upload `blog-post-stats` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. In order to get thumbs-up and thumbs-down count in your Blog post copy this code `<?php show_like_image(get_the_ID()); ?>`
1. And then paste it in Single Post.php template that you get from dashboard->Appearance->Editor->Template list(right side)->select "Single Post"
1. In order to get post statistic as word-count, number of links and images, views and visitor count copy the code `<?php display_post_analytics(get_the_ID()); ?>`
1. Paste this copied function in same "Single Post" before or after the_content function
1. For more information go to dashboard Settings->Blog-post-stats.

# == Changelog ==

= 1.1 =
* Wordpress issues fixed.

= 1.0  =
* Initial release

== Upgrade Notice ==
