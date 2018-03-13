jQuery(document).ready(function(){
	jQuery('img#thumbsup').click(function(){
	var post_id=jQuery(this).attr('data_postid');
    jQuery.ajax({
			type: 'POST',
			url: MyAjax.ajaxurl,
			data: {"action": "count_blog_like", "id":post_id, "up_down":"up"},
			success: function(data){
				jQuery('li.thumbs_up_count').html(data);
				}
		});

});
jQuery('img#thumbsdown').click(function(){
	var post_id=jQuery(this).attr('data_postid');
jQuery.ajax({
			type: 'POST',
			url: MyAjax.ajaxurl,
			data: {"action": "count_blog_like", "id":post_id, "up_down":"down"},
			success: function(data){
			jQuery('li.thumbs_down_count').html(data);
			
			}
		});
});
jQuery('div.stats').on("click", function(){
    	
	if(jQuery('div.stats_parent').hasClass("display_stat"))
	{
		jQuery(this).parent().removeClass("display_stat");
		jQuery(this).next('div#content_detail').hide();
	}
	else
	{
		jQuery(this).parent().addClass("display_stat");
		jQuery(this).next('div#content_detail').show();
	}
var wc=jQuery(this).attr('count');
var id=jQuery(this).attr('post_id');

var img = jQuery('div.post_content').find('img').size();
var link1 = jQuery('div.post_content').find('a').size();
var link2 = jQuery('div.post_content').find('link').size();
link3=link1+link2;

jQuery.ajax({
			type: 'POST',
			url: MyAjax.ajaxurl,
			data: {"action": "post_word_count", "word_count":wc, "img_tag":img, "link_count":link3, "post_id":id},
			success: function(data){
			jQuery('div#content_detail').html(data);
			}
});
});
});