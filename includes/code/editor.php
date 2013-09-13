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
	# * FILE: /includes/code/editor.php
	# ----------------------------------------------------------------------------------------------------

    extract($_POST);
	extract($_GET);

	# ----------------------------------------------------------------------------------------------------
	# SUBMIT
	# ----------------------------------------------------------------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST" && !DEMO_LIVE_MODE) {

        $errorFolder = false;
        $errorBlackWord = false;
        $errorMessage = "";
        $blackWord = array();
        $preview = false;

        if ($htmleditorPreview){
            $preview = true;
            $previewHash = md5("sitemgrPreview");
            $auxDomainObj = new Domain(SELECTED_DOMAIN_ID);
            $previewURL = "http://".$auxDomainObj->getString("url").(EDIRECTORY_FOLDER ? EDIRECTORY_FOLDER : "");
        }
        
        if ($fileType == "lang"){
            if (!is_dir(HTMLEDITOR_LANG_FOLDER)) {
                //create folder custom/domain_x/lang/editor
                if (!mkdir(HTMLEDITOR_LANG_FOLDER)) {
                    $errorFolder = true;
                }
            }
            $filePath = HTMLEDITOR_LANG_FOLDER;
        } elseif ($fileType == "header_footer") {
            if (!is_dir(HTMLEDITOR_FOLDER)) {
                //create folder custom/domain_x/editor
                if (!mkdir(HTMLEDITOR_FOLDER)) {
                    $errorFolder = true;
                }
            }
            $filePath = HTMLEDITOR_FOLDER."/".EDIR_THEME;
        } elseif ($fileType == "css") {
            $filePath = HTMLEDITOR_THEMEFILE_DIR."/".EDIR_THEME;
            $file = "editor_".$file;
        } else {
            $filePath = HTMLEDITOR_FOLDER;
        }
        
        if (!is_dir($filePath)) {
            if (!mkdir($filePath)){
                $errorFolder = true;
            }
        }
        
        if ($preview && ($fileType == "header_footer" || ($fileType == "lang"))){
            $filePathLang = HTMLEDITOR_LANG_FOLDER;
            $filePathHeaderFootter = HTMLEDITOR_FOLDER."/".EDIR_THEME;
            $previewFiles = glob("$filePathLang/preview_*.*");
            if (is_array($previewFiles) && $previewFiles[0]){
                foreach($previewFiles as $previewFile){
                    @unlink($previewFile);
                }
            }
            unset($previewFiles);
            $previewFiles = glob("$filePathHeaderFootter/preview_*.*");
            if (is_array($previewFiles) && $previewFiles[0]){
                foreach($previewFiles as $previewFile){
                    @unlink($previewFile);
                }
            }
        }
        
        $blackWords = array();
        $blackWords[] = "mysql_";
        $blackWords[] = "drop";
        $blackWords[] = "truncate";
        $blackWords[] = "shell_exec(";
        $blackWords[] = "exec(";
        $blackWords[] = "system(";
        $blackWords[] = "fopen(";
        $blackWords[] = "fread(";
        $blackWords[] = "fwrite(";
        $blackWords[] = "wget";
        
        if ($from_ajax) {
            $text = $text_temp;
            if (file_exists($filePath."/temp_".$file)){
                unlink($filePath."/temp_".$file);
            }
        }
        
        if (get_magic_quotes_gpc()){
            $text = stripslashes($text);
        }
        
        $lowText = string_strtolower($text);
        
        if ($fileType == "header_footer"){
            foreach($blackWords as $word){
                if (string_strpos($lowText, $word) !== false){
                    $errorBlackWord = true;
                    $blackWord[] = "\"".$word."\"";
                }
            }
        }
        
        if ($errorFolder){
            $errorMessage = system_showText(LANG_SITEMGR_EDITOR_PERMERROR);
        } elseif ($errorBlackWord){
            $errorMessage = system_showText(LANG_SITEMGR_EDITOR_WORDERROR)." ".implode(", ", $blackWord).".";
        } elseif (!$text){
            $errorMessage = system_showText(LANG_SITEMGR_EDITOR_EMPTYERROR);
        }
       
        if (!$errorMessage || $revert){
            
            if ($revert){
                if (file_exists($filePath."/".$file)){
                    unlink($filePath."/".$file);
                }
                
                if (CACHE_FULL_FEATURE == "on"){
                    cachefull_forceExpiration();
                }
                
                $message = 1;
            } else {
                $message = 0;
                
                if (!$preview && $cleanPreview == "on"){
                    if (file_exists($filePath."/preview_".$file)){
                        unlink($filePath."/preview_".$file);
                    }
                }
                
                if ($send_backup && $email_backup && !$preview){

                    $domainObj = new Domain(SELECTED_DOMAIN_ID);
                    $domain_url = "http://".$domainObj->getString("url");
                    setting_get("sitemgr_email", $sitemgr_email);
                    
                    $attachPath = $filePath."/".$file;
                    if (!file_exists($attachPath)){
                        if ($fileType == "header_footer"){
                            $attachPath = system_getFrontendPath($file, "layout", true);
                        } elseif ($fileType == "lang"){
                            $attachPath = language_getFilePath(str_replace(".php", "", $file), false, false, false, SELECTED_DOMAIN_ID, true, true);
                        } elseif ($fileType == "css"){
                            $attachPath = system_getStylePath(str_replace("editor_", "", $file), EDIR_THEME, true, true);
                        }
                    }
                    
                    $sitemgr_msg = "
                                <html>
                                    <head>
                                        <style>
                                            .email_style_settings{
                                                font-size:12px;
                                                font-family:Verdana, Arial, Sans-Serif;
                                                color:#000;
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class=\"email_style_settings\">
                                            Site Manager,<br /><br />
                                            The file ".str_replace("editor_", "", $file)." was edited at ".$domain_url.(EDIRECTORY_FOLDER ? EDIRECTORY_FOLDER : "").".<br />
                                            Here is a copy of the last version submitted to the site.
                                        </div>
                                    </body>
                                </html>";

                    $return = system_mail($email_backup, "[".EDIRECTORY_TITLE."] HTML Editor Backup", $sitemgr_msg, EDIRECTORY_TITLE." <".$sitemgr_email.">", "text/html", "", "", $error, $attachPath, str_replace("editor_", "", $file));
                    if (!$return) {
                        $errorMessage .= system_showText(LANG_CONTACTMSGFAILED).($error ? '<br />'.$error : '');
                        $error = "";
                    }
                }
                
                if (!$errorMessage) {
                    file_put_contents($filePath."/".($preview ? "preview_" : "").$file, $text);

                    if (CACHE_FULL_FEATURE == "on"){
                        cachefull_forceExpiration();
                    }
                }
            }

            $file = str_replace("editor_", "", $file);
            
            if ($from_ajax) {
                $returnAjax = DEFAULT_URL."/".SITEMGR_ALIAS."/content/htmleditor.php?file=$file&message=$message";
                echo $returnAjax; 
                exit;
            } else {
                if (!$preview && !$errorMessage){
                    header("Location: ".DEFAULT_URL."/".SITEMGR_ALIAS."/content/htmleditor.php?file=$file&message=$message");
                    exit;
                } else {
                    unset($message);
                }
            }
        } elseif ($from_ajax){
            $returnAjax = DEFAULT_URL."/".SITEMGR_ALIAS."/content/htmleditor.php?file=$file&errorMessage=".url_encode($errorMessage);
            echo $returnAjax;
            exit;
        } else {
            $file = str_replace("editor_", "", $file);
        }
	} elseif($_GET["download"] && $_GET["file"] && $_GET["fileType"] && !DEMO_LIVE_MODE){
        if ($fileType == "lang"){
            $filePath = language_getFilePath(str_replace(".php", "", $file), false, false, false, SELECTED_DOMAIN_ID, true, true);
            $fileExt = "php";
            $fileName = str_replace(".php", "", $file);
        } elseif ($fileType == "header_footer") {
            $filePath = system_getFrontendPath($file, "layout", true);
            $fileExt = "php";
            $fileName = str_replace(".php", "", $file);
        } elseif ($fileType == "css") {
            $filePath = system_getStylePath(str_replace("editor_", "", $file), EDIR_THEME, true, true);
            $fileExt = "css";
            $fileName = str_replace(".css", "", $file);
        } else {
            $filePath = HTMLEDITOR_FOLDER;
            $fileExt = "php";
            $fileName = str_replace(".php", "", $file);
        }
        system_downloadFile($filePath, $fileName, $fileExt);
    } elseif($_SERVER['REQUEST_METHOD'] == "POST" && DEMO_LIVE_MODE){
        $errorMessage = system_showText(LANG_SITEMGR_THEME_DEMO_MESSAGE2);
    }
    
    # ----------------------------------------------------------------------------------------------------
	# FORMS DEFINES
	# ----------------------------------------------------------------------------------------------------
    
    setting_get("htmleditor_first_change", $htmleditor_first_change);
    $langPart = explode("_", EDIR_LANGUAGE);
    $editorLang = $langPart[0];
    if ($editorLang == "ge") {
        $editorLang = "de";
    }
    $editorSyntax = "html";
    $availableFiles = system_getEditorAvailable();
    $nameArray = array();
    $valueArray = array();
    
//    $nameArray[] = "-- ".system_showText(LANG_SITEMGR_EDITOR_FRONTFILES)." --";
//    $valueArray[] = "";
    $legend = true;
    $legendLang = true;
    
    foreach($availableFiles as $avlFile){
        if (string_strpos($avlFile, ".css") === false){
                if ($legendLang && string_strpos($avlFile, "_") !== false){
                    $nameArray[] = "-- ".system_showText(LANG_SITEMGR_EDITOR_LANGFILES)." --";
                    $valueArray[] = "";
                    $legendLang = false;
                }
        } else {
            if ($legend){
                $nameArray[] = "-- ".system_showText(LANG_SITEMGR_EDITOR_CSSFILES)." --";
                $valueArray[] = "";
                $legend = false;
            }
        }
        $nameArray[] = $avlFile;
        $valueArray[] = $avlFile;
    }

    if (!in_array($file, $availableFiles) || !$file){
//        $file = "header.php";
        $file = "advertise.css";
    }

    if ($_SERVER['REQUEST_METHOD'] != "POST"){
        if ($file == "header.php" || $file == "footer.php"){
            $fileType = "header_footer";
            $text = file_get_contents(system_getFrontendPath($file, "layout"));
            $text = str_replace("# ----------------------------------------------------------------------------------------------------", "", $text);
            $text = str_replace("# * FILE: /layout/$file", "", $text);
            $text = str_replace("# * FILE: /theme/realestate/layout/$file", "", $text);
            $text = str_replace("# ----------------------------------------------------------------------------------------------------", "", $text);
        } elseif (string_strpos($file, "_") !== false && string_strpos($file, "css") === false){
            $editorSyntax = "php";
            $fileType = "lang";
            $text = file_get_contents(language_getFilePath(str_replace(".php", "", $file), false, false, false, SELECTED_DOMAIN_ID, true));
            $text = str_replace("# ----------------------------------------------------------------------------------------------------", "", $text);
            $text = str_replace("# * FILE: /lang/$file", "", $text);
            $text = str_replace("# ----------------------------------------------------------------------------------------------------", "", $text);
        } else {
            $fileType = "css";
            $text = file_get_contents(system_getStylePath($file, EDIR_THEME, true));
            $editorSyntax = "css";
        }
    }
    
    $editorSelect = html_selectBox("selectFile", $nameArray, $valueArray, $file, "onchange=\"setEditor(this.value);\"");
    
    setting_get("sitemgr_email", $sitemgr_email);
    
?>