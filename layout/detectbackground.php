			<?			
			$thispagetitle=$_SERVER['SERVER_NAME'];
			if ( strstr( $thispagetitle, "192.168.1.85" ) )
			{ ?>
				<script type="text/javascript">
				$(document).ready(function(){
                                    var myNameData='<img src="<?=DEFAULT_URL."/custom/domain_2/theme/default/images/structure/nhdbackground.jpg";?>" id="bg" alt="" />';
                                    //$("body").prepend(myNameData);
                                    <?php $bg_img = DEFAULT_URL."/custom/domain_2/theme/default/images/structure/nhdbackground.jpg"; ?>
                                    var bg_url = '<?php echo $bg_img; ?>';
                                    //$('body').attr('style', "background-image:url('"+bg_url+"') center top fixed !important;background-size:100% auto;");
                                    //$('body').attr('style', "background:url('"+bg_url+"') no-repeat scroll center top transparent !important;");
                                    //$('body').css({'background-size': '100%'});
                                   //alert("asd");
                                    $('body').css({'background': 'url('+bg_url+') no-repeat center top fixed','background-size': '100% 100%'});
                                 });  
				</script>
			<? } 
			elseif ( strstr( $thispagetitle, "theduncandirectory" ) )
			{ ?>
				<script type="text/javascript">
				$(document).ready(function(){
				var myNameData='<img src="http://theduncandirectory.com/custom/domain_4/theme/default/schemes/default/images/structure/COCBG50.jpg" id="bg" alt="" />';
				$("body").prepend(myNameData);
				});
				</script>
			<? } ?>