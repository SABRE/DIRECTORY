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
	# * FILE: /includes/code/language_center.php
	# ----------------------------------------------------------------------------------------------------
	
    if ($actionFrom == "changeLang") {
        
        if (isset($active) && $id) {
            $langObj = new Lang($id);
            $langObj->changeDefaultLang();
            $langObj->writeLanguageFile();
            
            if (CACHE_FULL_FEATURE == "on"){
                cachefull_forceExpiration();
            }

            $url_redirect .= "?message=2";
            header("Location: $url_redirect");
            exit; 
        } elseif (isset($activeAdmin) && $id) {
            if (!setting_set("sitemgr_language", $id)) {
                if (!setting_new("sitemgr_language", $id)) {
                    $error = true;
                }
            }

            $url_redirect .= "?message_admin=2";
            header("Location: $url_redirect");
            exit; 
        }
        
    } elseif ($actionFrom == "editLang") {
        
        if ($_SERVER['REQUEST_METHOD'] == "POST" && !DEMO_LIVE_MODE) {

            if ($action == "ajax") {

                if ($clean) {
                    @unlink($file);
                    @unlink($temp_file);
                } else {
                    $newFile = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/lang/".$file_name;
                    copy($file, $newFile);
                    if (string_strpos($file_name, "_sitemgr") === false) {
                        language_createJSFiles($newFile, str_replace(".php", "", $file_name));
                    }
                    @unlink($temp_file);
                    @unlink($file);
                    if (CACHE_FULL_FEATURE == "on"){
                        cachefull_forceExpiration();
                    }
                }
                exit;
            } else {
                $file_name = $lang.($area == "sitemgr" ? "_sitemgr" : "").".php";
                $checkTempFile = false;

                if (!$_FILES["lang_edited_file"]["tmp_name"])  {
                    $error = system_showText(LANG_SITEMGR_LANGUAGE_REQUIRED);
                } elseif ($file_name != $_FILES["lang_edited_file"]["name"]) {
                    $error = system_showText(LANG_SITEMGR_LANGUAGE_INVALIDFILE);
                } elseif (file_exists($_FILES['lang_edited_file']['tmp_name']) && filesize($_FILES['lang_edited_file']['tmp_name'])) {

                    //Uploaded file to be renamed later, if valid
                    $fileTempOriginal = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/lang/temp_".(md5($file_name.date("h:i:s")))."_".$file_name;
                    copy($_FILES['lang_edited_file']['tmp_name'], $fileTempOriginal);

                    //Temp file to be validated
                    $filePrefix = "<? define(\"EDIR_CHARSET\", \"".EDIR_CHARSET."\");\n include_once(\"".EDIRECTORY_ROOT."/functions/string_funct.php\"); \n\n ini_set(\"display_errors\", \"1\");\n\n?>\n\n";
                    $fileTempValidateName = "validate_temp_".(md5($file_name.date("h:i:s"))).$file_name;
                    $fileTempValidatePath = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/lang/".$fileTempValidateName;
                    file_put_contents($fileTempValidatePath, $filePrefix.file_get_contents($_FILES['lang_edited_file']['tmp_name']));

                    $checkTempFile = true;
                } else {
                    $error = system_showText(LANG_SITEMGR_LANGUAGE_REQUIRED);
                }
            }
        }
    
    } elseif ($actionFrom == "editName") {
        
        if ($_SERVER['REQUEST_METHOD'] == "POST" && !DEMO_LIVE_MODE) {
            
            //Validate form
            unset($errorArray);
            unset($error);
            unset($uploadFlag);
             
            //Required Fields
            if (!$language_name) $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_LANGNAME_REQUIRED);
            
            //Validate flag image
            if ($_FILES["flag_image"]["tmp_name"] && file_exists($_FILES['flag_image']['tmp_name']) && filesize($_FILES['flag_image']['tmp_name'])) {
                
                $uploadFlag = true;
                $array_allowed_types = array('jpeg', 'jpg', 'gif', 'png');
                $arr_flag = explode(".",$_FILES['flag_image']['name']);
                $flag_extension = $arr_flag[count($arr_flag)-1];

                if (!in_array(string_strtolower($flag_extension),$array_allowed_types)) {
                    $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_FLAG_INVALIDFILE);
                }

//                $width = image_getWidth($_FILES["flag_image"]["tmp_name"]);
//                $height = image_getHeight($_FILES["flag_image"]["tmp_name"]);
//
//                if ($width != 63 || $height != 43) {
//                    $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_FLAG_INVALIDDIMENSIONS);
//                }
            }
            
            if (is_array($errorArray) && $errorArray[0]) {
                $error = "<b>".system_showText(LANG_MSG_FIELDS_CONTAIN_ERRORS)."</b><br />".implode("<br />", $errorArray);
            }

            if (!$error) {

                //Update language name
                $langObj = new Lang($clang);
                $langObj->setString("name", $language_name);
                $langObj->Save();
                
                //Upload flag
                if ($uploadFlag) {
                    $flagFolder = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/lang/flags";
                    $flagPath = $flagFolder."/$clang.png";
                    @unlink($flagPath); //remove old flag
                    copy($_FILES['flag_image']['tmp_name'], $flagPath);
                }
                
                $url_redirect .= "?message=0";
                header("Location: $url_redirect");
                
            }
            
        }
        
    } elseif ($actionFrom == "addLang") {

        if ($_SERVER['REQUEST_METHOD'] == "POST" && !DEMO_LIVE_MODE) {
        
            if ($action == "ajax") {

                if ($clean) {
                    @unlink($fileFront);
                    @unlink($temp_fileFront);
                    @unlink($fileSitemgr);
                    @unlink($temp_fileSitemgr);
                    @unlink($flagPath);
                } else {
                    //Move Lang Files
                    $newFileFront = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/lang/".$file_nameFront;
                    copy($fileFront, $newFileFront);
                    language_createJSFiles($newFileFront, str_replace(".php", "", $file_nameFront));
                    @unlink($temp_fileFront);
                    @unlink($fileFront);

                    $newFileSitemgr = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/lang/".$file_nameSitemgr;
                    copy($fileSitemgr, $newFileSitemgr);
                    @unlink($temp_fileSitemgr);
                    @unlink($fileSitemgr);     

                    //Create lang record
                    $langArray["id"] = $lang_id;
                    $langArray["name"] = $lang_name;
                    $langArray["lang_enabled"] = "n";
                    $langArray["lang_default"] = "n";

                    $langObj = new Lang($langArray);
                    $langObj->Save($domain_id);
                }
                exit;
            } else {

                $checkTempFile = false;

                //Validate form
                unset($errorArray);
                unset($error);

                //Required Fields
                if (!$language_name || $language_name == LANG_SITEMGR_LANGUAGE_NAMELANG) $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_LANGNAME_REQUIRED);
                if (!$language_abbr || $language_abbr == LANG_SITEMGR_LANGUAGE_ABBRLANG) $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_LANGABBR_REQUIRED);
                if (!$_FILES["flag_image"]["tmp_name"] || !file_exists($_FILES['flag_image']['tmp_name']) || !filesize($_FILES['flag_image']['tmp_name'])) $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_FLAG_REQUIRED);
                if (!$_FILES["lang_edited_front"]["tmp_name"] || !file_exists($_FILES['lang_edited_front']['tmp_name']) || !filesize($_FILES['lang_edited_front']['tmp_name'])) $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_REQUIRED)." (".system_showText(LANG_SITEMGR_LANGUAGE_AREA_FRONT).")";
                if (!$_FILES["lang_edited_sitemgr"]["tmp_name"] || !file_exists($_FILES['lang_edited_sitemgr']['tmp_name']) || !filesize($_FILES['lang_edited_sitemgr']['tmp_name'])) $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_REQUIRED)." (".system_showText(LANG_SITEMGR_LANGUAGE_AREA_SITEMGR).")";

                //Abbreviation
                $validAbbreviation = true;
                if ($language_abbr && $language_abbr != LANG_SITEMGR_LANGUAGE_ABBRLANG) {
                    if ($language_abbr[2] != "_" || string_strlen($language_abbr) < 5) {
                        $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_LANGABBR_INVALID);
                        $validAbbreviation = false;
                    } else {
                        $langAux = new Lang($language_abbr);
                        if ($langAux->getString("id")) {
                            $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_LANGABBR_EXISTS);
                            $validAbbreviation = false;
                        }
                    }
                }

                //Flag
                if ($_FILES["flag_image"]["tmp_name"] && file_exists($_FILES['flag_image']['tmp_name']) && filesize($_FILES['flag_image']['tmp_name'])) {

                    $array_allowed_types = array('jpeg', 'jpg', 'gif', 'png');
                    $arr_flag = explode(".",$_FILES['flag_image']['name']);
                    $flag_extension = $arr_flag[count($arr_flag)-1];

                    if (!in_array(string_strtolower($flag_extension),$array_allowed_types)) {
                        $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_FLAG_INVALIDFILE);
                    }

    //                $width = image_getWidth($_FILES["flag_image"]["tmp_name"]);
    //                $height = image_getHeight($_FILES["flag_image"]["tmp_name"]);
    //
    //                if ($width != 63 || $height != 43) {
    //                    $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_FLAG_INVALIDDIMENSIONS);
    //                }
                }

                //Front File
                if ($validAbbreviation && $_FILES["lang_edited_front"]["tmp_name"] && file_exists($_FILES['lang_edited_front']['tmp_name']) && filesize($_FILES['lang_edited_front']['tmp_name'])) {
                    $fileName = $language_abbr.".php";
                    if ($_FILES["lang_edited_front"]["name"] != $fileName) {
                        $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_INVALIDFILE2)." (".system_showText(LANG_SITEMGR_LANGUAGE_AREA_FRONT).")";
                    }
                }

                //Sitemgr File
                if ($validAbbreviation && $_FILES["lang_edited_sitemgr"]["tmp_name"] && file_exists($_FILES['lang_edited_sitemgr']['tmp_name']) && filesize($_FILES['lang_edited_sitemgr']['tmp_name'])) {
                    $fileName_sitemgr = $language_abbr."_sitemgr.php";
                    if ($_FILES["lang_edited_sitemgr"]["name"] != $fileName_sitemgr) {
                        $errorArray[] = "&#149;&nbsp;".system_showText(LANG_SITEMGR_LANGUAGE_INVALIDFILE2)." (".system_showText(LANG_SITEMGR_LANGUAGE_AREA_SITEMGR).")";
                    }
                }

                if (is_array($errorArray) && $errorArray[0]) {
                    $error = "<b>".system_showText(LANG_MSG_FIELDS_CONTAIN_ERRORS)."</b><br />".implode("<br />", $errorArray);
                }

                if (!$error) {

                    $filePrefix = "<? define(\"EDIR_CHARSET\", \"".EDIR_CHARSET."\");\n include_once(\"".EDIRECTORY_ROOT."/functions/string_funct.php\"); \n\n ini_set(\"display_errors\", \"1\");\n\n?>\n\n";
                    $language_abbr = string_strtolower($language_abbr);

                    //Upload flag
                    $flagFolder = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/lang/flags";
                    $flagPath = $flagFolder."/$language_abbr.png";
                    if (!is_dir($flagFolder)) {
                        //create folder custom/domain_x/lang/editor
                        if (!mkdir($flagFolder)) {
                            $errorFolder = true;
                        }
                    }
                    copy($_FILES['flag_image']['tmp_name'], $flagPath);

                    //Uploaded file to be renamed later, if valid - FRONT
                    $fileTempOriginalFront = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/lang/tempFront_".(md5($fileName.date("h:i:s")))."_".$fileName;
                    copy($_FILES['lang_edited_front']['tmp_name'], $fileTempOriginalFront);

                    //Temp file to be validated - FRONT
                    $fileTempValidateNameFront = "validate_tempFront_".(md5($fileName.date("h:i:s"))).$fileName;
                    $fileTempValidatePathFront = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/lang/".$fileTempValidateNameFront;
                    file_put_contents($fileTempValidatePathFront, $filePrefix.file_get_contents($_FILES['lang_edited_front']['tmp_name']));

                    //Uploaded file to be renamed later, if valid - SITEMGR
                    $fileTempOriginalSitemgr = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/lang/tempSitemgr_".(md5($fileName_sitemgr.date("h:i:s")))."_".$fileName_sitemgr;
                    copy($_FILES['lang_edited_sitemgr']['tmp_name'], $fileTempOriginalSitemgr);

                    //Temp file to be validated - SITEMGR
                    $fileTempValidateNameSitemgr = "validate_tempSitemgr_".(md5($fileName_sitemgr.date("h:i:s"))).$fileName_sitemgr;
                    $fileTempValidatePathSitemgr = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/lang/".$fileTempValidateNameSitemgr;
                    file_put_contents($fileTempValidatePathSitemgr, $filePrefix.file_get_contents($_FILES['lang_edited_sitemgr']['tmp_name']));

                    $checkTempFile = true;
                }
            }

        } elseif ($_GET["download"] && !DEMO_LIVE_MODE) {

            unset($files);
            $files[0]["file"] = language_getFilePath("en_us", false, false, false, SELECTED_DOMAIN_ID, false, true);
            $files[0]["name"] = "en_us.php";
            $files[1]["file"] = language_getFilePath("en_us", false, false, true, SELECTED_DOMAIN_ID, false, true);
            $files[1]["name"] = "en_us_sitemgr.php";
            system_downloadFile($files, "en_us", "");

        }
        
    }
    
    # ----------------------------------------------------------------------------------------------------
    # FORMS DEFINES
    # ----------------------------------------------------------------------------------------------------
    $allLanguages = unserialize(LANGUAGE_INFORMATION);
    
    if ($actionFrom == "changeLang") {
        
        setting_get("sitemgr_language", $sitemgr_language);
        $flagFolder = EDIRECTORY_ROOT."/custom/domain_".SELECTED_DOMAIN_ID."/lang/flags";
        
    } elseif ($actionFrom == "editName") {
        
        if (!$clang) {
            $clang = EDIR_LANGUAGE;
        }
        
        $langObj = new Lang($clang);
        
        if ($langObj->getNumber("id_number")) {
            if ($_SERVER["REQUEST_METHOD"] != "POST") {
                $language_name = $langObj->getString("name");
            }
        } else {
            header("Location: ".$url_redirect);
            exit;
        }
    }

?>