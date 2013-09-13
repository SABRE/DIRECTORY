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
	# * FILE: /includes/forms/form_slider.php
	# ----------------------------------------------------------------------------------------------------

	if ($message_approval_options) { ?>
		<div id="warning" class="<?=$message_style?>">
			<?=$message_approval_options?>
		</div>
	<? } 
	
	for($slider_number=1;$slider_number<=TOTAL_SLIDER_ITEMS;$slider_number++){ ?>

		<table id="slider_setting_title_<?=$slider_number?>" class="standard-table nomargin">
			<tr class="standard-tabletitle-parent">
				<th class="standard-tabletitle standard-tabletitle2" style="cursor: pointer;">
					<?=LANG_SITEMGR_ITEM_SLIDER?> <?=$slider_number?>
				</th>
			</tr>
		</table>

		<div id="slider_setting_<?=$slider_number?>" class="noSpace"  style="display: none">

			<?
			/*
			 * Title of Slider
			 */
			?>
			<table class="standard-table nomargin">
				<tr>
					<th class="standard-tabletitle">
						<?=system_showText(LANG_LABEL_INFORMATION)?>
					</th>
                </tr>
           </table>
           <table class="standard-table">
                <tr>
                	<th>* <?=system_showText(LANG_SITEMGR_SLIDER_TITLE)?>:</th>
					<td colspan="2">
						<input type="text" id="<?=$slider_number?>_title" name="<?=$slider_number?>_title" value="<?=$array_slider[$slider_number]["title"]?>" maxlength="50" />
						<span><?=system_showText(LANG_SITEMGR_SLIDER_TITLE_EXPLAIN)?></span>
                        <input type="hidden"  id="<?=$slider_number?>_id" name="<?=$slider_number?>_id" value="<?=$array_slider[$slider_number]["id"]?>" />
					</td>
				</tr>
                <? if (SLIDER_HAS_PRICE) { ?>
                <tr>
                    <th>
                        <?=system_showText(LANG_LABEL_PRICE);?>:
                    </th>
                    <td>
                        <?
                        unset($price_value);
                        if ( $array_slider[$slider_number]["price"] != 'NULL' ) {
                            $price_value = explode(".", $array_slider[$slider_number]["price"]);	
                        }
                        echo CURRENCY_SYMBOL;
                        ?>
                        <input type="text" name="<?=$slider_number?>_price_int" value="<?=($price_value[0]) ? $price_value[0] : ${$slider_number."_price_int"} ?>" id="<?=$slider_number?>_price_int" maxlength="7" style="width:55px !important; text-align:right;" />
                        <strong> &nbsp;.&nbsp; </strong>
                        <input type="text" name="<?=$slider_number?>_price_cent" value="<?=($price_value[1] ? $price_value[1] : ${$slider_number."_price_cent"})?>" id="<?=$slider_number?>_price_cent" maxlength="2" style="width:20px !important;" />
                    </td>
                </tr>
                <? } ?>
			</table>

            <table border="0" cellpadding="0" rules="0" cellspacing="0" class="standard-table">

                <tr>
                    <th class="standard-tabletitle">
                        <?=system_showText(LANG_LABEL_SUMMARY_DESCRIPTION)?> <span style="display:inline">(<?=string_strtolower(system_showText(constant("LANG_MSG_MAX_".SLIDER_MAX_CHARS."_CHARS")))?>)</span>
                    </th>
                </tr>

                <tr>
                    <td>
                        <textarea id="<?=$slider_number?>_summary" name="<?=$slider_number?>_summary" rows="5" cols="1"><?=$array_slider[$slider_number]["summary"]?></textarea>
                    </td>
                </tr>

            </table>

			<table class="standard-table nomargin">
				<tr>
					<th class="standard-tabletitle" colspan="2">
						<?=system_showText(LANG_LABEL_IMAGE)?> <span>(<?=IMAGE_SLIDER_WIDTH?>px x <?=IMAGE_SLIDER_HEIGHT?>px) (JPG, GIF <?=system_showText(LANG_OR);?> PNG)</span>
                        <br />
                        <span class="note-desc"><?=system_showText(LANG_SITEMGR_SLIDER_ADVICE_IMAGE_SIZE)?></span>
                    </th>
				</tr>
           </table>
           <table class="standard-table nomargin">
                <tr>
                    <th>
                        * <?=system_showText(LANG_LABEL_IMAGE_SOURCE)?>:
                    </th>
                    <td>
                        <input size="38" type="file" name="<?=$slider_number?>_image" />
                        <input type="hidden" id="<?=$slider_number?>_image_id" name="<?=$slider_number?>_image_id" value="<?=$array_slider[$slider_number]["image_id"]?>" /><br />
                        <span><?=system_showText(LANG_SITEMGR_MSGERROR_MAXFILESIZEALLOWEDIS." ".UPLOAD_MAX_SIZE."MB.");?></span><br />
                    </td>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <td>
                        <div class="slideImage" id="<?=$slider_number?>_div_slider_image">
                            
								<? if ($array_slider[$slider_number]["image_id"]) {
                                        $imageObj = new Image($array_slider[$slider_number]["image_id"]);
                                        if ($imageObj->imageExists()) {

                                            echo $imageObj->getTag(true, 440, 248, ($array_slider[$slider_number]["alternative_text"]));

                                        } else {
                                            echo "<div class=\"imgDetail\" style=\"width:440px;\">";
                                            echo "<div class=\"noimage\" style=\"height:248px;\">&nbsp;</div>";
                                            echo "</div>";

                                        }
								} else { ?>
									<div class="slideImageText">
										<h6><?=LANG_LABEL_IMAGE?> <span><?=LANG_SITEMGR_SLIDER_NO_IMAGE_EXPLAIN?></span></h6>
									</div>
                                <? } ?>
                                
                            
                        </div>
                    </td>
                </tr>
			</table>
            
            <table border="0" cellpadding="0" cellspacing="0" class="standard-table">
                <tr>
                    <td style="padding:0">
                        <table class="addSlideImage" rules="0" border="0" cellpadding="0" cellspacing="0">
                            <? if (SLIDER_USE_NEWEST) { ?>
                            <tr>
                                <th class="wrapcaption">
                                    <?=system_showText(LANG_SITEMGR_SLIDER_ALTERNATIVE_TEXT)?>:
                                </th>
                                <td class="wrapcaption">
                                    <input id="<?=$slider_number?>_alternative_text" type="text" name="<?=$slider_number?>_alternative_text" value="<?=$array_slider[$slider_number]["alternative_text"]?>" class="inputExplode" maxlength="250" />
                                </td>
                            </tr>

                            <tr>
                                <th class="wrapcaption">
                                        <?=system_showText(LANG_SITEMGR_SLIDER_IMAGE_TITLE)?>:
                                </th>
                                <td class="wrapcaption">
                                    <input id="<?=$slider_number?>_title_text" type="text" name="<?=$slider_number?>_title_text" value="<?=$array_slider[$slider_number]["title_text"]?>" class="inputExplode" maxlength="250"/>
                                </td>
                            </tr>
                            <? } ?>

                            <tr>
                                <th class="wrapcaption">
                                    <?=system_showText(LANG_SITEMGR_SLIDER_LINK_LABEL)?>:
                                </th>
                                <td class="wrapcaption">
                                    <input id="<?=$slider_number?>_link" type="text" name="<?=$slider_number?>_link" value="<?=$array_slider[$slider_number]["link"]?>" class="inputExplode"/>
                                    <span><?=system_showText(LANG_SITEMGR_SLIDER_EXPLAIN_LINK)?></span>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
		
                <tr>
                    <td style="padding:0">
                        <table class="addSlideImage" rules="0" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <th>
                                    <?=system_showText(LANG_OPENNEWWINDOW);?>:
                                </th>
                                <td>
                                    <input type="radio" id="<?=$slider_number?>_target_self" name="<?=$slider_number?>_target_window" value="self" class="checkbox-slider" <?=($array_slider[$slider_number]["target"] || !$array_slider[$slider_number]["target"] == "self" ? "checked='checked'" : "")?> /><?=system_showText(LANG_NO);?>
                                    <input type="radio" id="<?=$slider_number?>_target_blank" name="<?=$slider_number?>_target_window" value="blank" class="checkbox-slider" <?=($array_slider[$slider_number]["target"] == "blank" ? "checked='checked'" : "")?> /><?=system_showText(LANG_YES);?>
                                </td>

                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
                
			<? if ($array_slider[$slider_number]["image_id"]) {
				$imageObj = new Image($array_slider[$slider_number]["image_id"]);
				if ($imageObj->imageExists() || $array_slider[$slider_number]["title"]) { ?>
					<input type="button" value="<?=system_showText(LANG_SITEMGR_CLEAR)?>" class="input-button-form" onclick="<?=DEMO_LIVE_MODE ? "livemodeMessage('".system_showText(LANG_SITEMGR_THEME_DEMO_MESSAGE2)."');" : "clearSlider($slider_number);"?>" style="margin: 10px 5px 30px;" />
                    
                <? }
			} ?>
			
		</div>
		<? } ?>		

	<script type="text/javascript">
		
		$(document).ready(function(){
	
			<? for ($slider_number = 1; $slider_number <= TOTAL_SLIDER_ITEMS; $slider_number++) { ?>
				$('#slider_setting_title_<?=$slider_number?>').click(function () {
					if ($('#slider_setting_<?=$slider_number?>').is(':hidden')) {
						$('#slider_setting_title_<?=$slider_number?>').css('cursor', 'pointer');
						$('#slider_setting_title_<?=$slider_number?> tr th').addClass('active');
						$('#slider_setting_<?=$slider_number?>').slideDown('slow');
					} else {
						$('#slider_setting_<?=$slider_number?>').slideUp('slow');
						$('#slider_setting_title_<?=$slider_number?> tr th').removeClass('active');
						$('#slider_setting_title_<?=$slider_number?>').css('cursor', 'pointer');

					}
				});


                var field_name = <?=$slider_number?>+'_summary';
                var count_field_name = <?=$slider_number?>+'_remLen';
                var options = {
                            'maxCharacterSize': <?=SLIDER_MAX_CHARS?>,
                            'originalStyle': 'originalTextareaInfo',
                            'warningStyle' : 'warningTextareaInfo',
                            'warningNumber': 40,
                            'displayFormat' : '<span><input readonly="readonly" type="text" id="'+count_field_name+'" name="'+count_field_name+'" size="3" maxlength="3" value="#left" class="textcounter" disabled="disabled" /> <?=system_showText(LANG_MSG_CHARS_LEFT)?> <?=system_showText(LANG_MSG_INCLUDING_SPACES_LINE_BREAKS)?></span>' 
                    };
                $('#'+field_name).textareaCount(options);

                $.mask.definitions['~']='[+-]';
                $("#<?=$slider_number?>_price_int").mask("9?999999",{placeholder:""});
                $("#<?=$slider_number?>_price_cent").mask("9?9",{placeholder:""});
                
			<? } ?>
                    
		});
				
		function clearSlider(id){
			
			if(confirm("<?=LANG_SITEMGR_SLIDER_CONFIRM_DELETE?>")){
				
				$.ajax({
				   type: "POST",
				   url: "<?=DEFAULT_URL?>/<?=SITEMGR_ALIAS?>/content/delete_slider_image.php",
				   data: "clear_slider=true&image_id="+document.getElementById(id+"_image_id").value+"&slider_id="+document.getElementById(id+"_id").value
				 });
				
				document.getElementById(id+"_title").value = "";
				document.getElementById(id+"_image_id").value = "";
                if (document.getElementById(id+"_price_int")){
                    document.getElementById(id+"_price_int").value = "";
                }
                if (document.getElementById(id+"_price_cent")){
                    document.getElementById(id+"_price_cent").value = "";
                }

                document.getElementById(id+"_summary").value = "";
                if (document.getElementById(id+"_alternative_text")){
                    document.getElementById(id+"_alternative_text").value="";
                }
                if (document.getElementById(id+"_title_text")){
                    document.getElementById(id+"_title_text").value="";
                }
                document.getElementById(id+"_link").value="";
							
				aux_text = "<div class=\"slideImageText\"><h6><?=LANG_LABEL_IMAGE?> <span><?=LANG_SITEMGR_SLIDER_NO_IMAGE_EXPLAIN?></span></h6></div>";
				document.getElementById(id+'_div_slider_image').innerHTML = aux_text;
			}
		}
	</script> 