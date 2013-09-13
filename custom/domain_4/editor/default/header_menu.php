
<li <?=($activeMenuHome ? "class=\"menuActived\"" : "")?>><a href="<?=NON_SECURE_URL?>">Home</a></li>


<li <?=(ACTUAL_MODULE_FOLDER == LISTING_FEATURE_FOLDER ? "class=\"menuActived\"" : "")?>><a href="<?=LISTING_DEFAULT_URL?>/">Listings</a></li>

<? if (EVENT_FEATURE == "on" && CUSTOM_EVENT_FEATURE == "on") { ?>
<li <?=(ACTUAL_MODULE_FOLDER == EVENT_FEATURE_FOLDER ? "class=\"menuActived\"" : "")?>><a href="<?=EVENT_DEFAULT_URL?>/">Events</a></li>
<? } ?>
<? if (PROMOTION_FEATURE == "on" && CUSTOM_HAS_PROMOTION == "on" && CUSTOM_PROMOTION_FEATURE == "on") { ?>
<li <?=(ACTUAL_MODULE_FOLDER == PROMOTION_FEATURE_FOLDER ? "class=\"menuActived\"" : "")?>><a href="<?=PROMOTION_DEFAULT_URL?>/">Deals</a></li>
<? } ?>

<li <?=((string_strpos($_SERVER["REQUEST_URI"], "/".ALIAS_ADVERTISE_URL_DIVISOR.".php") !== false) ? "class=\"menuActived\"" : "")?>><a href="<?=NON_SECURE_URL?>/<?=ALIAS_ADVERTISE_URL_DIVISOR?>.php">Advertise With Us</a></li>


<li <?=((string_strpos($_SERVER["REQUEST_URI"], "/".ALIAS_CONTACTUS_URL_DIVISOR.".php") !== false) ? "class=\"menuActived\"" : "")?>><a href="<?=NON_SECURE_URL?>/<?=ALIAS_CONTACTUS_URL_DIVISOR?>.php">Contact Us</a></li>


<li <?=((string_strpos($_SERVER["REQUEST_URI"], "content/chamber-welcome.html") !== false) ? "class=\"menuActived\"" : "")?>><a href="http://theduncandirectory.com/content/chamber-welcome.html">Chamber Info</a></li>

