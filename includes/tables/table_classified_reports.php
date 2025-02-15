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
    # * FILE: /includes/tables/table_classified_reports.php
    # ----------------------------------------------------------------------------------------------------
?>

<?
    # ----------------------------------------------------------------------------------------------------
    # * HEADER
    # ----------------------------------------------------------------------------------------------------
?>
<style type="text/css">
    .dataTR     { background-color: #FFF; cursor: pointer; }
    .dataOver   { background-color: #EEE; cursor: pointer; }
    .dataActive { background-color: #CCC; cursor: pointer; }
</style>

<?
    # ----------------------------------------------------------------------------------------------------
    # * HEADER
    # ----------------------------------------------------------------------------------------------------
?>

    <table border="0" cellpadding="2" cellspacing="2" class="standard-tableTOPBLUE">

<?
    # ----------------------------------------------------------------------------------------------------
    # * CHART
    # ----------------------------------------------------------------------------------------------------
?>
        <tr>
            <td  colspan="7">
                <div id="reportChart" style="widht:700px; height:200px; background: #FFF url(<?=DEFAULT_URL?>/images/img_loading.gif) 50% 50% no-repeat;">&nbsp;</div>
            </td>
        </tr>

<?
    # ----------------------------------------------------------------------------------------------------
    # * CLASSIFIED 
    # ----------------------------------------------------------------------------------------------------
?>
        <tr>
            <th colspan="3">
                <?
                    if(string_strpos($_SERVER['REQUEST_URI'], "/".SITEMGR_ALIAS."") !== false) {
                        if ($classified->getNumber("account_id")) {
                            $account = db_getFromDB("account", "id", db_formatNumber($classified->getNumber("account_id")));
                            $username = system_showTruncatedText(system_showAccountUserName($account->getString("username")) ,15);
                            echo system_showText(LANG_LABEL_ACCOUNT), ": <span title = ".$account->getString("username").">", $username, "</span><br />";
                        } else {
                            echo system_showText(LANG_LABEL_ACCOUNT), ":<span> " . system_showText(LANG_SITEMGR_NOOWNER) . "</span><br />";
                        }
                    }
                ?>
                <?=system_showText(LANG_LABEL_NAME)?>: <span title="<?=($classified->getString('title', true));?>"><?=$classified->getString('title', true, 35);?></span>
                <br />
                <?=system_showText(LANG_LABEL_LEVEL)?>: <?=$levelName?>
                <br />
                <?=system_showText(LANG_LABEL_STATUS)?>: <?=$statusName?>
                <span><?=$owner?></span>
            </th>
        </tr>

<?
    # ----------------------------------------------------------------------------------------------------
    # * REPORT DATA
    # ----------------------------------------------------------------------------------------------------
?>
        <tr>
            <td width="160">
                <b><?=system_showText(LANG_LABEL_DATE)?></b>
            </td>
            <td width="270">
                <b style="color: #CE9C52;"><?=system_showText(LANG_LABEL_SUMMARY)?></b>
            </td>
            <td width="270">
                <b style="color: #D3CD83;"><?=system_showText(LANG_LABEL_DETAIL)?></b>
            </td>
        </tr>

        <?
            $idx = 0;
            foreach($reports AS $key => $report) {
                $idx++;
                list($year, $month) = explode('-', $key);
        ?>
                <tr id="dataTR<?=$idx;?>" class="<?=(($idx == 1) ? 'dataActive' : 'dataTR');?>" onmouseover="dataTRMouseOver(<?=$idx;?>)" onmouseout="dataTRMouseOut(<?=$idx;?>)" onclick="javascript:deactivateAll();changeChart(<?=($idx) ? $idx : 0;?>,<?=($report['summary']) ? $report['summary'] : 0;?>,<?=($report['detail']) ? $report['detail'] : 0;?>);">
                    <td><?=system_showDate('F', mktime(0, 0, 0, $month, 1, $year));?> / <?=$year;?></td>
                    <td><?=($report['summary']) ? $report['summary'] : 0;?></td>
                    <td><?=($report['detail']) ? $report['detail'] : 0;?></td>
                </tr>
        <? } ?>

    </table>

<?
    # ----------------------------------------------------------------------------------------------------
    # * SCRIPT
    # ----------------------------------------------------------------------------------------------------
?>
    <script language="JavaSCRIPT">
        function changeChart(idx, value1, value2) {
            var label1 = '<?=system_showText(system_showText(LANG_LABEL_SUMMARY));?>: ' + value1;
            var label2 = '<?=system_showText(system_showText(LANG_LABEL_DETAIL));?>: ' + value2;
           
            var total = value1 + value2;
            value1 = ((value1 * 100) / total);
            value2 = ((value2 * 100) / total);
            
            document.getElementById('dataTR'+idx).className = "dataActive";
            document.getElementById("reportChart").innerHTML = "<img src='http://chart.apis.google.com/chart?chs=700x200&amp;chf=bg,s,ffffff|c,s,ffffff&amp;chxt=x,y&amp;chxl=1:||0:|||&amp;cht=bhg&amp;chd=t:"+value1+"|"+value2+"&amp;chdl="+label1+"|"+label2+"&amp;chco=ce9c52,d3cd83&amp;chbh=25' alt='Report Chart'/>";
        }

        function dataTRMouseOver(idx) {
            if(document.getElementById('dataTR'+idx).className != 'dataActive')
                document.getElementById('dataTR'+idx).className = 'dataOver';
        }

        function dataTRMouseOut(idx) {
            if(document.getElementById('dataTR'+idx).className != 'dataActive')
                document.getElementById('dataTR'+idx).className = 'dataTR';
        }
        
        function deactivateAll() {
            <? for($x=1; $x<=$idx; $x++) { ?>
                document.getElementById('dataTR<?=$x?>').className = "dataTR";
            <? } ?>
        }
        
        document.getElementById('dataTR1').onclick();
        
    </script>