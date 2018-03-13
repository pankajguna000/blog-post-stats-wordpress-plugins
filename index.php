<?php
/*
Plugin Name: Blog Post Stats
Plugin URI: http://inkthemes.com
Description: Show thumbs-up and thumbs-down count for post. 
Version: 1.1
Author: InkThemes
Author URI: http://inkthemes.com
*/

// Define Constant Variable
define('INKTHEMES_PLUGIN_PATH', plugins_url());
// incuding javascript File thumb-count.js
wp_enqueue_script('inkthemes-blog', plugins_url( '/js/thumbs-count.js' , __FILE__ ) , array( 'jquery' ) );

wp_enqueue_style('inkthemes-style-blog', plugins_url( '/css/inktheme-dashboard.css' , __FILE__ ));
// including ajax script in the plugin Myajax.ajaxurl 
wp_localize_script( 'inkthemes-blog', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

//include views to post 
add_action('wp_head', 'blogpage_post_view');

global $wpdb;
$table_prefix=$wpdb->prefix;
define('TABLE_PREFIX', $table_prefix);

register_activation_hook( __FILE__, 'keyword_search_activate' );
register_uninstall_hook( __FILE__, 'keyword_search_uninstall' );

function keyword_search_activate( ) {
	global $wpdb;
	$table = TABLE_PREFIX."total_count";
    $structure = "CREATE TABLE $table (
	   count_id INT PRIMARY KEY AUTO_INCREMENT, 
	   post_id INT(11) NOT NULL, 
	   thumbs_up INT(11) NOT NULL, 
	   thumbs_down INT(11) NOT NULL, 
	   view_with_ip INT(11) NOT NULL, 
	   all_blog_views INT(11) NOT NULL
	   );";
    $wpdb->query($structure); 
	$table1 = TABLE_PREFIX."count_likes";
    $structure1 = "CREATE TABLE $table1 (
	   id INT PRIMARY KEY AUTO_INCREMENT, 
	   post_id INT(11) NOT NULL, 
	   ip_address varchar(255) NOT NULL, 
	   date DATETIME, 
       up_down varchar(255) NOT NULL
       );";
     $wpdb->query($structure1); 
	}

function keyword_search_uninstall( ) {
	global $wpdb;
	$table = TABLE_PREFIX."count_likes";
	$table1 = TABLE_PREFIX."total_count";
    $structure = "drop table IF EXISTS $table, $table1";
    $wpdb->query($structure);  
}

/********** Add sub-menu inside settings menu ***************/ 
function add_menu()
{
add_options_page( 'Blog Post Stats Setting', 'Blog-post-stats', 'manage_options', 'blog-help', 'blog_setting');
}

add_action('admin_menu', 'add_menu');

/*********** Dashboard  page for template function *************/
function blog_setting()
{
$thumb_image =  plugins_url( '/images/thumbs_image1.png' , __FILE__ );
$stats_image =  plugins_url( '/images/table-image1.png' , __FILE__ );
$first = "<h2>Blog Post Stats</h2><div class=\"thumbs_display_div\"><div class=\"thumbs_heading\"><span class=\"thumbs_span\">Thumbs up and down Count</span></div><div class=\"thumbs_text\">To display thumbs-up and thumbs-down count in your blog post copy the below function";
$first .= "<div id=\"thumbs_function\"><b>&#60;&#63;php show_like_image(get_the_ID())&#59; &#63;&#62;</b></div>";
$first .= "and paste it in Single Post.php template that you get from dashboard->Appearance->Editor->Template list(right side)->select 'Single Post'.<div class=\"thumb_screen\"><img src=\"$thumb_image\"/></div></div></div>";

$first1 .= "<div class=\"thumbs_display_div\"><div class=\"thumbs_heading\"><span class=\"thumbs_span\">Post Statistics</span></div><div class=\"thumbs_text\">To display blog post statistic as views and visitor count for particular post, word count, number of links and images in particular post then copy the below function";
$first1 .= "<div id=\"thumbs_function\"><b>&#60;&#63;php display_post_analytics(get_the_ID())&#59; &#63;&#62;</b> </div>";
$first1 .= "and paste it in Single Post.php template that you get from dashboard->Appearance->Editor->Template list(right side)->select 'Single Post' before or after the the_content(). This function display 'View stats' link and it click display two tables as Content and Traffic summary.<div class=\"thumb_screen\"><img src=\"$stats_image\" width=\"850px\"/></div></div></div>";
$first2 ="<div class=\"thumbs_display_div\"><div class=\"thumbs_heading\">Blog Post Stats is powered by inkthemes.com. In order to get Premium WordPress Themes <a href=\"http://www.inkthemes.com\">Click here</a>.</div></div>";
echo $first;
echo $first1;
echo $first2;
}




/********** function that provide visit and view count of post **********/
function blogpage_post_view() {
	global $post;
	if(is_int($post))
	{
	$post = get_post($post);
	}
	if(!wp_is_post_revision($post)) {
		if(is_single() || is_page()) {
			$id = intval($post->ID);
			global $wpdb;
			$ip=$_SERVER['REMOTE_ADDR'];
			$views="views";
			global $wpdb;
			$query=$wpdb->get_row($wpdb->prepare("select * from ".$wpdb->prefix."count_likes where post_id=$id AND ip_address='$ip' AND date(date)=CURDATE() AND up_down='$views'", null));
			if(!$query)
			{
				$wpdb->insert($wpdb->prefix."count_likes", 
	           array( 
		          'post_id'=>$id,
				  'ip_address'=>$ip,
		          'date'=> date('Y-m-d H:i:s'),
				  'up_down'=>'views'
				   ));
           
            $post_count = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."total_count WHERE post_id =%d", $id));
            $count=$post_count->view_with_ip;
            if($post_count)
            {
			   $wpdb->update($wpdb->prefix."total_count", 
	           array( 
		          'view_with_ip'=>$count+1
		         ), 
	           array('post_id' => $id)
	                 );
            }
           else
            {
                $wpdb->insert($wpdb->prefix."total_count", 
                array( 	
				'post_id' =>$id,
		        'view_with_ip'=> 1
		             ));
            }
			}
			
			$post_count = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."total_count WHERE post_id =%d", $id));
            $count=$post_count->all_blog_views;
            if($post_count)
            {
			   $wpdb->update($wpdb->prefix."total_count", 
	           array( 
		          'all_blog_views'=>$count+1
		         ), 
	               array('post_id' => $id)
	                 );
            }
           else
            {
                $wpdb->insert($wpdb->prefix."total_count", 
                array( 	
				'post_id' =>$id,
		        'all_blog_views'=> 1
		             ));
            }
			
		}
	}
}

// Image in the content blog post 
function show_like_image($postid){	
global $wpdb;
$query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."total_count where post_id=$postid");
$thumbsup =  plugins_url( '/images/thumbs-up.png' , __FILE__ );
$thumbsdown =  plugins_url( '/images/thumbs-down.png' , __FILE__ );
$image.= "<div><ul class=\"thumb_status\">";
$image .= "<li class=\"thumbs_li\"><img id=\"thumbsup\" src=\"$thumbsup\" height=\"20px\" width=\"20px\" data_postid=$postid /></li>";
$image .= "<li class=\"thumbs_up_count\">$query->thumbs_up</li>";
$image .= "<li class=\"thumbs_li\">|</li>";
$image .= "<li class=\"thumbs_li\"><img id=\"thumbsdown\" src=\"$thumbsdown\" height=\"20px\" width=\"20px\" data_postid=$postid /></li>";	
$image .= "<li class=\"thumbs_down_count\">$query->thumbs_down</li>";
$image .= "</ul></div>";
echo $image;	
}


/**** Ajax function for updating and selecting updated thumb up and down count ****/
function count_blog_like(){
global $wpdb;
$id=$_POST['id'];
$condition=$_POST['up_down'];
$ip=$_SERVER['REMOTE_ADDR'];
if($condition=="up")
{
$string_comp="up";
$query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."count_likes where post_id=$id  AND ip_address='$ip' AND date(date)=CURDATE() AND up_down='$string_comp'");
if(!$query)
{
	$wpdb->insert($wpdb->prefix."count_likes", 
	           array( 
		          'post_id'=>$id,
				  'up_down'=>"up",
				  'ip_address'=>$ip,
		          'date'=> date('Y-m-d H:i:s')
				     ));
$post_likes = $wpdb->get_row( $wpdb->prepare("SELECT * FROM ". $wpdb->prefix."total_count where post_id=%d", $id));
$count=$post_likes->thumbs_up;
 if($post_likes)
            {
			   $wpdb->update($wpdb->prefix."total_count", 
	           array( 
		         'thumbs_up'=>$count+1
		         ), 
	           array('post_id' => $id)
	                 );
            }
           else
            {
                $wpdb->insert($wpdb->prefix."total_count", 
	           array( 
		         'thumbs_up'=>1,
				 'post_id'=>$id
		         ));
            }
			
}
$query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."total_count where post_id=$id");
echo $query->thumbs_up;
}
else
{
$string_comp="down";
$query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."count_likes where post_id=$id  AND ip_address='$ip' AND date(date)=CURDATE() AND up_down='$string_comp'");
if(!$query)
{
	$wpdb->insert($wpdb->prefix."count_likes", 
	           array( 
		          'post_id'=>$id,
				  'up_down'=>"down",
				  'ip_address'=>$ip,
		          'date'=> date('Y-m-d H:i:s')
				     ));
$post_likes = $wpdb->get_row( $wpdb->prepare("SELECT * FROM ". $wpdb->prefix."total_count where post_id=%d", $id));
$count=$post_likes->thumbs_down;
 if($post_likes)
            {
			   $wpdb->update($wpdb->prefix."total_count", 
	           array( 
		         'thumbs_down'=>$count+1
		         ), 
	           array('post_id' => $id)
	                 );
            }
           else
            {
                $wpdb->insert($wpdb->prefix."total_count", 
	           array( 
		         'thumbs_down'=>1,
				 'post_id'=>$id
		         ));
            }
}

$query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."total_count where post_id=$id");
echo $query->thumbs_down;
}
die();
}
// Ajax Request for the files
add_action( 'wp_ajax_count_blog_like', 'count_blog_like' );
add_action( 'wp_ajax_nopriv_count_blog_like', 'count_blog_like' );

/**** Ajax function for display tables of view stats ****/
function post_word_count()
{   global $wpdb;
	$word_count=$_POST['word_count'];
	$link_count=$_POST['link_count'];
	$img_count=$_POST['img_tag'];
	$id=$_POST['post_id'];
	$query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."total_count where post_id=$id");
	$view=$query->view_with_ip;
	$visit=$query->all_blog_views;
	$total=$view+$visit;
    /*** form divs for stats of post ***/
     $content_stats .= "<div class=\"outer_div\"><div id=\"content_div\" style=\"display:inline-block; margin-right:30px;\"><table class=\"cont_tab\" width=\"280\" style=\"table-layout: fixed;\"><tr><th class=\"cont_th_head\" colspan=\"2\">Content</th></tr><tr><td class=\" tab1_col1\">Word Count</td><td class=\" tab1_col1\">";
	$content_stats .= $word_count;
	$content_stats .= "</td><tr><td class=\" tab1_col1\">Links / Images</td><td class=\" tab1_col1\">";
	$content_stats .= $link_count."/".$img_count;
	$content_stats .= "</td>";
	$content_stats .= "</tr></table></div>";
	$content_stats .= "<div id=\"traffic_div\" style=\"display:inline-block;\"><table class=\"view_tab\" width=\"280\" style=\"table-layout: fixed;\"><tr><th class=\"cont_th_head\" colspan=\"2\">Traffic Summary</th></tr><tr><td class=\"tab2_col2\">Visits</td><td class=\"tab2_col2\">$query->view_with_ip</td></tr>";
	$content_stats .= "<tr><td class=\" tab2_col2\">Views</td><td class=\"tab2_col2\">$query->all_blog_views</td></tr></table></div></div>";
	echo $content_stats;
	
	die();
	
}
add_action( 'wp_ajax_post_word_count', 'post_word_count' );
add_action( 'wp_ajax_nopriv_post_word_count', 'post_word_count' );

/**** Display View Stats link on particular post ****/
function display_post_analytics($postid)
{
$queried_post = get_post($post_id);
$post_content=$queried_post->post_content;
$word_count=sizeof(explode(" ", $post_content));
echo "<div class=\"stats_parent\"><div class=\"stats\" count=\"$word_count\" post_id=\"$postid\" style=\"cursor:pointer; color:green;\">View Stats</div><div id=\"content_detail\"></div></div>";
}
?>