<?php
    // JMC 20140902
        $productName 	= 'pulse';
        $productEdition = 'mandriva';
        $oem_file = '/etc/pulse-licensing/oem';
        if (file_exists($oem_file)){
            $oem = trim(file_get_contents($oem_file));
            if ($oem != '')
                $productEdition = $oem;
        }
        $productVersion = '3';
    // JMC 20140902
    
    // ==================================================
    // Check if the product is activated or not
    // ==================================================

    if (file_exists('/usr/share/pulse-first-run/includes/functions.php')){
       include '/usr/share/pulse-first-run/includes/functions.php';

       $ldata = get_license_data();

       // If not activated redirect to first-run
       if (!$ldata){
          $server = $_SERVER['HTTP_HOST'];
          header("Location: http://$server/pulse-first-run/");
       }
    }
    
    // ==================================================
    // Detect browser language
    // ==================================================
    include_once '/usr/share/mmc/includes/modules.inc.php';
    include_once '/usr/share/mmc/includes/i18n.inc.php';
    $langDesc = getArrLocale();
    $languages = list_system_locales('/usr/share/mmc/modules/base/locale/');

    // Get browser lang
    $lang_1 = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $lang_2 = str_replace('-','_',substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5));

    // If lang1 = en => LANG =  C
    if ($lang_1 == 'en')
        $browser_lang = 'C';
    else // We check other languages
        // Searching with xx_XX pattern
        if (in_array($lang_2, $languages))
            $browser_lang = $lang_2;
        else
            // Searching with xx pattern
            foreach ($languages as $lang)
                if (substr($lang, 0, 2) == $lang_1)
                    $browser_lang = $lang;

    // JMC 20140902
    $productLanguage = ($browser_lang != '') ? strtolower($browser_lang) : 'en';
    $dynamicContentURL =        'http://productslanding.mandriva.com/?product='
                                .$productName.'&edition='
                                .$productEdition.'&version='
                                .$productVersion.'&language='
                                .$productLanguage;
    // JMC 20140902

    // ====================================================
    // Include corresponding html page in homepages dir
    // ====================================================
    ?>
<div style='display: none;' id='staticContent'>
   	<?php
    if (file_exists("homepages/hp_$browser_lang.html"))
        include "homepages/hp_$browser_lang.html";
    else
        include "homepages/hp_C.html";
    ?>
</div>
<script>
        $(document).ready(function() {
                if(navigator.onLine){
                        document.location.href="<?php echo $dynamicContentURL;?>";
                }else{
                        $('#staticContent').css('display','block');
                }
        });
</script>
