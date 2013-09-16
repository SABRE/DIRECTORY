<div class="list-title-link-block">
	<div class="listing-title">
		<?=$article_title;?>
	</div>
	<div class="images-link">
		<div id="forward">
			<?=$shareTemplate;?>
		</div>
		<div id="favourites">
			<?=$favoriteTemplate;?>
		</div>
		<div id="social-links">
			<?=$facebookL;?>
		</div>
		<div id="social-links">
			<?=$twitterL;?>
		</div>
	</div>
</div>
<div style="clear:both;"></div>
<div class="listing-info-box">
	<div class="category-tree">
		<? if ($article_category_tree) { ?>
			<?=$article_category_tree?>
		<? } ?>
	</div>
	<div style="clear:both;"></div>
	<?if($article_facebook_buttons){?>
		<div style="clear:both;"></div>
		<div class="listing-facebook-button">
			<img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/check_icon.png';?>"  style="display:inline;"/>
			<span id="facebook-link">
				<?=$article_facebook_buttons?>
			</span>
		</div>
	<?}?>
</div>
<div class="gallery-box">
	<? if ($imageTag) { ?>
    	<div class="image">
			<?=$imageTag?>
        </div>
	<? } ?>
	<? if($articleGallery) { ?>
    	<div class="gallery">
    		<?=$articleGallery?>
        </div>
	<? } ?>
</div>
<div class="content">
	<div class="content-main">
		<?if($article_publicationDate || $article_author || $article_name ){?>	
		<div class="overview-hour-operation">
			<div class="overview-box">
					<? if ($event_summarydesc) { ?>
						<span id="overview-title"><?=system_showText(LANG_LABEL_OVERVIEW);?></span>
						<div class="content-box">
							<? if ($article_author){?>
								<?=$article_authorStr?>	
							<? } elseif ($article_name){?>
								<?=$article_name?>
							<?}?>
						</div>
					<? }?>
			</div>
			<div class="operation-hour-box">
				<? if ($article_publicationDate) { ?>
					<span id="operation-hour-box-title">Publication Date</span>
					<div class="content-box">			
						<?=$article_publicationDate?>
					</div>
				<? } ?>
			</div>
		</div>
		<?}?>
		<div style="clear:both;"></div>
		<? if ($article_content) { ?>
			<div class="detail-description">
				<span id="description-title"><?=system_showText(LANG_LABEL_DESCRIPTION);?></span>
				<div class="content-box">
				<?=$article_content?>
				</div>
			</div>
		<? } ?>
	</div>
	<div style="clear:both;"></div>
	<? 
		$url = $_SERVER['REQUEST_URI'];
		$fileName = basename($url,".php");
		if($fileName != 'advertise'){
	?>
	<div class="upper-section" style="border-top:1px solid #BFBFBF;border-bottom:none;margin-top:20px;padding-top:20px;">
	<? include(system_getFrontendPath("detail_upper_section.php", "frontend", false, ARTICLE_EDIRECTORY_ROOT)); ?>
	</div>
	<? include(system_getFrontendPath("banner_bottom.php")); ?>
	<?}?>
</div>
			

	