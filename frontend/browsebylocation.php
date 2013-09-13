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
	# * FILE: /frontend/browsebylocation.php
	# ----------------------------------------------------------------------------------------------------

	# ----------------------------------------------------------------------------------------------------
    # CODE
    # ----------------------------------------------------------------------------------------------------
    include_once(EDIRECTORY_ROOT."/includes/code/browsebylocation.php");

    // CREATING CACHE
    $objCache = new cache($cacheType);

    if ($found_featLocations) {
         if ($objCache->caching) { ?>

            <h2><?=system_showText(LANG_FEATUREDLOCATIONS)?></h2>
            <ul id="accordion">
                <?
                $count_locationFeatLevels = 0;

                foreach($locationsToShow as $_location_level) {

                    $count_locationFeatLevels++;
                    if (count(${"locations".$_location_level}) && $count_locationFeatLevels <= FEATUREDLOCATION_LEVEL_AMOUNT) { ?>
                        <li class="accordion-item">
                            <h3>
                                <a href="javascript:void(0);">
                                    <?=system_showText((constant("LANG_LABEL_".constant("LOCATION".$_location_level."_SYSTEM")."_PL")))?>
                                </a>
                            </h3>
                            
                            <ul class="list <?=$count_locationFeatLevels == 1 ? "current" : ""?>">
                                <?
                                foreach (${"locations".$_location_level} as $each_location) {

                                    $i=0;
                                    $location_path["friendly"] = false;
                                    $location_path["nonfriendly"] = false;
                                    while ($_location_level > $_locations[$i]) {
                                        $location_path["friendly"][] = $each_location["location".$_locations[$i]."_friendly_url"];
                                        $location_path["nonfriendly"][] = "location_".$_locations[$i]."=".$each_location["location_".$_locations[$i]];
                                        $i++;
                                    }
                                ?>

                                    <li><a href="<?=$moduleURL?>/<?=ALIAS_LOCATION_URL_DIVISOR?>/<?=($location_path["friendly"] === false ? '' : implode("/",$location_path["friendly"])."/" )?><?=$each_location["friendly_url"];?>"><?=$each_location["name"];?></a></li>
                                <?
                                    unset ($location_path);
                                }
                                ?>
                            </ul>
                        </li>
                        <?
                    }
                }
                ?>
                    <li class="view-all"><a href="<?=$moduleURL?>/<?=ALIAS_ALLLOCATIONS_URL_DIVISOR?>.php"><?=system_showText(LANG_VIEWALLLOCATIONSCATEGORIES)?></a></li>
            </ul>		
            <?
        }
        $objCache->close();
        unset ($count_locationFeatLevels);
        unset ($found_featLocations);
        unset ($found_featLocations);
        unset ($defaultLocationsToShow);
        unset ($nonDefaultLocationsToShow);
        unset ($flag_showNonFeatures);
        unset ($last_default_level);
        unset ($last_default_id);
        
    }
?>