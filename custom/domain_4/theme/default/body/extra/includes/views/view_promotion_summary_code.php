<a name="<?=$friendly_url;?>"></a>
<div class="summary">
    <div class="left">
        <div class="image">
            <?=$imageTag;?>
        </div>
    </div>
    <div class="right">
        <div class="deal" style="border-bottom:none;">
            <div class="deal-details">
                <div class="deal-details-info">
                    <div class="deal-title">
                        <? if ($show_map) { ?>
                            <span id="summaryNumberID<?=$mapNumber;?>" class="map <?=(($_COOKIE['showMap'] == 0) ? ('isVisible') : ('isHidden'))?>">
                                <a class="map-link" href="javascript:void(0)" onclick="javascript:myclick(<?=($mapNumber);?>);scrollPage();">
                                    <?=$mapNumber;?>.
                                </a>
                            </span>
                        <? } 
                            $promotionTitle = $promotion->getString("name", true, false).$promotionDistance;
                            if(strlen($promotionTitle) > 20){
                                $promotionTitle = substr($promotionTitle,0,20).'...';	
                        }?>
                        <a href="<?=$promotionLink?>" <?=$promotionStyle?> title="<?=$promotion->getString("name")?>"><?=$promotionTitle?></a>
                    </div>
                    <? if ($listingTitle) {?>
                        <div class="deal-url">
                            <?=system_showText(LANG_BY)?> <a href="<?=$listing_link?>" <?=$promotionStyle?> title="<?=string_htmlentities($listingTitle)?>"><?=$listingTitle?></a>
                        </div>
                    <? } ?>
                    <? if($promotion_review){?>
                        <div class="deal-review">
                             <?=$promotion_review;?>
                        </div>
                    <?}?>
                </div>
                <div class="image-links" style="float:right;">
                    <div class="images-link-upper">
                        <div id="social-links">
                            <?=$deal_icon_navbar;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="deal-description">
                <?=$promotion_desc;?>
            </div>
        </div>
        <div class="deal-info">
             <div class="sold-out-box">
                <? if ($sold_out) { ?>
                    <div class="deal-tag deal-tag-sold-out"><?=system_showText(DEAL_SOLDOUT);?></div>
                <? } else { ?>
                    <div class="deal-tag"><?=CURRENCY_SYMBOL.$deal_price.($deal_cents ? "<span class=\"cents\">".$deal_cents."</span>" : "");?></div>
                <? } ?>
                <div class="deal-discount"><?=$offer." ".OFF?></div>
            </div>
        </div>
    </div>
</div>
