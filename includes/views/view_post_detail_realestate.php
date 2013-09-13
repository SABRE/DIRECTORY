<?
	/*==================================================================*\
	######################################################################
	#                                                                    #
	# Copyright 2005 Arca Solutions, Inc. All Rights Reserved.           #
	#                                                                    #
	# This file may not be redistributed in whole or part.               #
	# eDirectory is licensed on a per-domain basis.                      #
	#                                                                    #
	# ---------------- eDirectory IS NOT FREE SOFTWARE ----------------- #
	#                                                                    #
	# http://www.edirectory.com | http://www.edirectory.com/license.html #
	######################################################################
	\*==================================================================*/

	# ----------------------------------------------------------------------------------------------------
	# * FILE: /includes/views/view_post_detail_realestate.php
	# ----------------------------------------------------------------------------------------------------

	include(INCLUDES_DIR."/views/view_comment.php");
	extract($_GET);

?>

	<div class="detail">
	
		<h1><?=$title;?></h1>
		
		<div class="info">
			<p><?=system_showText(LANG_BY);?> <strong><?=EDIRECTORY_TITLE;?></strong> <?=$postOn;?></p>
			<p><?=$postCategoryTree;?></p>
		</div>
        
        <div class="content-social-box">
            <?=$post_facebook_buttons;?>
            <?=$post_googleplus_button;?>
            <?=$post_pinterest_button;?>
            <br clear="all" />
        </div>
		
		<? if ($wp_enabled != "on" || ($force_blog_module && $postImage)){ ?>
        	<div class="image-shadow">
                <div class="image">
                    <?=$imageTag?>
                </div>
			</div>
		<? } ?>
        
        <div class="share">
			<?=$icon_navbar;?>
        </div>
	
		<? if ($content) { ?>
			<div class="content-custom">
				<?=($content)?>
			</div>
		<? } ?>
        
        <div class="pnt-0">
			
		<?
		if ($commenting_fb){
			$showLabel = false;
		?>
			<span class="clear" style="display:block;"></span>
            <a name="info"></a>
			<h2><?=system_showText(LANG_BLOG_COMMENTS)?></h2>

			<script language="javascript" type="text/javascript">
				//<![CDATA[
				(function(d){
				  var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
				  js = d.createElement('script'); js.id = id; js.async = true;
				  js.src = "//connect.facebook.net/<?=EDIR_LANGUAGEFACEBOOK?>/all.js#xfbml=1";
				  d.getElementsByTagName('head')[0].appendChild(js);
				}(document));

				document.write('<div class="fb-comments" data-href="<?=$detailLink?>" data-num-posts="<?=$commenting_fb_number_comments?>" data-width="<?=FB_COMMENTWIDTH_BLOG?>"></div>');      
				//]]>
			</script>

		<? } ?>
		
		<? if ($detail_comment && $commenting_edir && $review_blog_enabled) { ?>
        	<span class="clear" style="display:block;"></span>
			<? if ($showLabel) { ?>
                <a name="info"></a>
				<h2><?=system_showText(LANG_BLOG_COMMENTS)?></h2>
			<? } ?>
                                    
             <? if ($success_message){ ?>
                <p class="successMessage" id="messageSucess"><?=$success_message?></p>
            <? } ?>   
                
			<?=$detail_comment?>
                
		<? } ?>
        
        </div>

		<? if ($success_message || $success_approve_message) { ?>
			<script type="text/javascript">
					$('html, body').animate({
						scrollTop: $('#messageSucess').offset().top
					}, 500);
			</script>
		<?
			unset($success_message);
		}?>
		
	</div>
	
	<? if ($user && $commenting_edir && $review_blog_enabled) { 
		include(EDIRECTORY_ROOT."/includes/forms/form_comment.php");
	} ?>