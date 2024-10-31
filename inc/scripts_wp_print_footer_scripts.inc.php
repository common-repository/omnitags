<!-- Code generated by Omnitags plugin - BEGIN -->
<?php
if (isset($omnitags_piwik_url) && $omnitags_piwik_url != "" && isset($omnitags_piwik_site_id) && $omnitags_piwik_site_id != "") {
echo '<!-- Piwik --><noscript><p><img src="'.$omnitags_piwik_url.'/piwik.php?idsite='.$omnitags_piwik_site_id.'&rec=1" style="border:0;" alt="" /></p></noscript><!-- End Piwik Code -->';
}

if (isset($omnitags_HubSpot_Account_Number) && $omnitags_HubSpot_Account_Number != "") {
    echo '<!-- Start of HubSpot Embed Code --><script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/'.$omnitags_HubSpot_Account_Number.'.js"></script><!-- End of HubSpot Embed Code -->';
}
else
{
    if (isset($omnitags_HubSpot_script) && $omnitags_HubSpot_script != "") {
        echo $omnitags_HubSpot_script;
    }
}

if (isset($omnitags_GetSiteControl_site_ID) && $omnitags_GetSiteControl_site_ID != "") {
    echo '<script>(function (w,i,d,g,e,t,s) {w[d] = w[d]||[];t= i.createElement(g);t.async=1;t.src=e;s=i.getElementsByTagName(g)[0];s.parentNode.insertBefore(t, s);})(window, document, \'_gscq\',\'script\',\'//widgets.getsitecontrol.com/'.$omnitags_GetSiteControl_site_ID.'/script.js\');</script>';
}
else
{
    if (isset($omnitags_GetSiteControl_script) && $omnitags_GetSiteControl_script != "") {
        echo $omnitags_GetSiteControl_script;
    }
}

if (isset($omnitags_Smartsupp_key) && $omnitags_Smartsupp_key != "") {
    echo '<!-- Start of Smartsupp Live Chat script --><script type="text/javascript">var _smartsupp = _smartsupp || {};_smartsupp.key = \''.$omnitags_Smartsupp_key.'\';window.smartsupp||(function(d) {var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];s=d.getElementsByTagName(\'script\')[0];c=d.createElement(\'script\');c.type=\'text/javascript\';c.charset=\'utf-8\';c.async=true;c.src=\'https://www.smartsuppchat.com/loader.js?\';s.parentNode.insertBefore(c,s);})(document);</script>';
}
else
{
    if (isset($omnitags_Smartsupp_script) && $omnitags_Smartsupp_script != "") {
        echo $omnitags_Smartsupp_script;
    }
}

if (isset($omnitags_custom_tag_2_script) && $omnitags_custom_tag_2_script != "") {
    echo $omnitags_custom_tag_2_script;
}
?>
<!-- Code generated by Omnitags plugin - END -->
