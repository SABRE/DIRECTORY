<div class="list-title-link-block">
    <div class="listing-title">
        <?=$deal_name;?>
    </div>
    <div class="images-link">
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
        <? if ($deal_category_tree) { ?>
                <?=$deal_category_tree?>
        <? } ?>
    </div>
    <div style="clear:both;"></div>
    <div class="review-detail">
        <?=$promotion_review;?>
    </div>
    <div style="clear:both;"></div>
    <div class="map-box">
       <?if($map_link)?>
                <span id="map-link"><a href="javascript: void(0);" <?=$map_link?> <?=$map_style?>>Show on map</a></span>
    </div>
    <div style="clear:both;"></div>
    <div class="info">
        <ul class="counter">
            <li id="timeLeft">
                <? if (!$user) { ?>
                    <span class="countdown_row countdown_show3">
			<li class="countdown_section">
                            <span class="countdown_amount">00</span>
                            <strong><?=string_ucwords(system_showText(LANG_LABEL_DAY));?></strong>
                        </li>
                        <li class="countdown_section">
                            <span class="countdown_amount">00</span>
                            <strong><?=string_ucwords(system_showText(LANG_LABEL_HOUR));?></strong>
                        </li>
                        <li class="countdown_section">
                            <span class="countdown_amount">00</span>
                            <strong><?=string_ucwords(system_showText(LANG_LABEL_MINUTE));?></strong>
                        </li>
                    </span>
                <? } ?>
            </li>
        </ul>
        <div class="action">
            <? if ($dealsDone) { ?>
                <p><strong><?=system_showText(DEAL_VALUE)?>:</strong> <?=$deal_real_value;?></p>
                <p><strong><?=system_showText(DEAL_WITHTHISCOUPON)?>:</strong> <?=CURRENCY_SYMBOL.format_money($promotion->getNumber("dealvalue"),2)?><p>
                <p><strong><?=system_showText(DEAL_DEALSDONE_PLURAL)?>:</strong> <?=$deal_sold;?></p>
            <? } else { ?>
                <p><strong><?=system_showText(DEAL_VALUE)?>:</strong> <?=$deal_real_value;?></p>
                <p><strong><?=system_showText(LANG_PROMOTION_FEATURE_NAME_PLURAL)." ".system_showText(DEAL_LEFTAMOUNT)?>:</strong> <?=$deal_left;?></p>
                <p><strong><?=system_showText(DEAL_DEALSDONE_PLURAL)?>:</strong> <label id="updateDeals"><?=$deal_sold;?></label></p>
            <? } ?>
            <div class="facebookConnect">
                <? if (!$dealsDone) {
                    if ($redeemLink) { ?>
                        <div <?=$buttomClass;?>>
                            <h2>
                                <?$linkFBRedeem = "<a href=\"".$redeemLink."\" ".(FACEBOOK_APP_ENABLED != "on" ? "class=\"$linkRedeemClass\"" : "")." $promotionStyle>".addslashes($buttonText)."</a>";?>
                                <script language="javascript" type="text/javascript">
                                        //<![CDATA[
                                        document.write('<?=$linkFBRedeem?>');
                                        //]]>
                                </script>
                            </h2>
                        </div>
                    <? } ?>
                    <? if ($linkText) { ?>
                        <p class="redeem-option">
                            <a class="<?=$linkRedeemClass?>" href="<?=$redeemWFB;?>" <?=$promotionStyle?>><?=$linkText;?></a>
                        </p>
                    <? } ?>
                <? } ?>
                <? if ($_SESSION["ITEM_ACTION"] == "redeem" && $_SESSION["ITEM_TYPE"] && (is_numeric($_SESSION["ITEM_ID"]) && $_SESSION["ITEM_ID"] == htmlspecialchars($promotion->getNumber('id'))) && sess_isAccountLogged()) { ?>
                    <a href="<?=$_SESSION["fb_deal_redirect"]? $_SESSION["fb_deal_redirect"]: $linkRedeem;?>" id="redeem_window" class="iframe fancy_window_redeem" style="display:none"></a>
                        <script type="text/javascript">
                            //<![CDATA[                               
                                $("a.fancy_window_redeem").fancybox({
                                    'overlayShow'     : true,
                                    'overlayOpacity'  : 0.75,
                                    'width'           : <?=FANCYBOX_DEAL_WIDTH?>,
                                    'height'          : <?=FANCYBOX_DEAL_HEIGHT?>,
                                    'autoDimensions'  : false
                                });
                                
								jQuery(document).ready(function() {
                                    $("#redeem_window").trigger('click');
                                });
                            //]]>
                        </script>
                    <? unset($_SESSION["ITEM_ACTION"], $_SESSION["ITEM_TYPE"], $_SESSION["ITEM_ID"], $_SESSION["ACCOUNT_REDIRECT"], $_SESSION["fb_deal_redirect"]);
                } ?>
            </div>
        </div>
    </div>
    <div class="sold-out-box">
        <? if ($dealsDone) { ?>
            <div class="deal-tag deal-tag-sold-out">
                <span class="price"><?=system_showText(DEAL_SOLDOUT);?></span>
                <span class="discount"><?=$deal_offer?> OFF</span>
            </div>
            <? } else { ?>
            <div class="deal-tag">
                <span class="price"><?=$deal_value.($deal_cents ? "<span class=\"cents\">".$deal_cents."</span>" : "")?></span>
                <span class="discount"><?=$deal_offer?> OFF</span>
            </div>
        <? } ?>
    </div>
    <?if($deal_facebook_buttons){?>
        <div style="clear:both;"></div>
        <div class="listing-facebook-button">
                <img src="<?=DEFAULT_URL.'/custom/domain_2/theme/default/schemes/default/images/newImages/check_icon.png';?>"  style="display:inline;"/>
                <span id="facebook-link">
                        <?=$deal_facebook_buttons?>
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
</div>
<div class="content">
    <div class="content-main">	
       <?if($deal_conditions ){?>	
            <div class="deal-condition-box">
                <span id="condition-title"><?=system_showText(LANG_LABEL_DEAL_CONDITIONS);?></span>
                <div class="content-box">
                    <?=nl2br($deal_conditions);?>
                </div>    
            </div>
        <?}?>
        <div style="clear:both;"></div>
        <? if ($deal_description) { ?>
            <div class="detail-description">
                    <span id="description-title"><?=system_showText(LANG_LABEL_DESCRIPTION);?></span>
                    <div class="content-box">
                    <?=nl2br($deal_description)?>
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
            <? include(system_getFrontendPath("detail_upper_section.php", "frontend", false, LISTING_EDIRECTORY_ROOT)); ?>
    </div>
    <? include(system_getFrontendPath("banner_bottom.php")); ?>
    <?}?>	
</div>
<script language="javascript" type="text/javascript">
    //<![CDATA[
    function updateDeals(value){
        $("#updateDeals").text(value);
    }
    //]]>
</script>

<script>
$(document).ready(function(){
    var height = $('.listing-info-box').height();
    if(height > $('.gallery-box').height()){
            $('.gallery-box').height(height+4);
    }	
});
</script>