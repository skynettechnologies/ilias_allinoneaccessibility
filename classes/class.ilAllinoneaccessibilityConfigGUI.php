<?php

/**
 * Class ilAllinoneaccessibilityConfigGUI
 * @author            Skynet Technologies USA LLC <hello@skynettechnologies.com>
 * @ilCtrl_IsCalledBy ilAllinoneaccessibilityConfigGUI: ilObjComponentSettingsGUI
 */

class ilAllinoneaccessibilityConfigGUI extends ilPluginConfigGUI {

    const PLUGIN_CLASS_NAME = ilAllinoneaccessibilityPlugin::class;
    const CMD_CONFIGURE = "configure";
    const CMD_UPDATE_CONFIGURE = "updateConfigure";
    const LANG_MODULE = "config";

    protected $dic;
    protected $plugin;
    protected $lng;
    protected $request;
    protected $user;
    protected $ctrl;
    protected $object;
    protected $tpl;
    protected $ui;
  
    public function __construct()
    {
      global $DIC;
      $this->dic = $DIC;
      $this->plugin = ilAllinoneaccessibilityPlugin::getInstance();
      $this->lng = $this->dic->language();
      $this->request = $this->dic->http()->request();
      $this->user = $this->dic->user();
      $this->ctrl = $this->dic->ctrl();
      $this->object = $this->dic->object();
      $this->ui = $this->dic->ui();
      $this->tpl = $this->dic['tpl'];
    }
    
    public function performCommand(string $cmd) :void
    {
        $this->plugin = $this->getPluginObject();

        switch ($cmd)
		{
			case self::CMD_CONFIGURE:
			case self::CMD_UPDATE_CONFIGURE:
                $this->{$cmd}();
                break;
            default:
                break;
		}
    }

    protected function configure($htmlMessage = ""): void
    {
        
        $aioa_website_hostname = $_SERVER['SERVER_NAME'];
		
		$curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://ada.skynettechnologies.us/check-website',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('domain' =>  $aioa_website_hostname),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        
        $settingURLObject = json_decode($response);
		
		$style = "<style>@import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');@import url('https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');body{ font-family: 'Roboto', sans-serif; }h1{font-family: 'Rubik', sans-serif;}.aioa-cancel-button {text-decoration: none;display: inline-block;vertical-align: middle;border: 2px solid #420083;border-radius: 4px 4px 4px 4px;background-color: #420083;box-shadow: 0px 0px 2px 0px #333333;color: #ffffff;text-align: center;box-sizing: border-box;padding: 10px;}.aioa-cancel-button:hover {border-color: #420083;background-color: white;box-shadow: 0px 0px 2px 0px #333333;color:black;}.aioa-cancel-button:hover .mb-text {color: #420083;}</style>";
        
        $Title ='<h1>All in One Accessibility™</h1>';
        $Title .='<hr>';
        $Title .="<h3 style='width:100%;'>All in One Accessibility widget improves website ADA compliance and browser experience for ADA, WCAG 2.1 & 2.2, Section 508, California Unruh Act, Australian DDA, European EAA EN 301 549, UK Equality Act (EA),Israeli Standard 5568, Ontario AODA, Canada ACA, German BITV, France RGAA, Brazilian Inclusion Law (LBI 13.146/2015), Spain UNE 139803:2012, JIS X 8341 (Japan), Italian Stanca Act and Switzerland DDA Standards without changing your website's existing code.</h3>";
        
        $iframe = '';
        if(isset($settingURLObject->status) && $settingURLObject->status == 3){
            $iframe .= '<h3 style="color: #aa1111">It appears that you have already registered! Please click on the "Manage Subscription" button to renew your subscription.<br> Once your plan is renewed, please refresh the page.</h3>';
            $iframe .= '<div style="text-align: left; width:100%; padding-bottom: 10px;"><a target="_blank" class="aioa-cancel-button"  href="'.$aioa_website_hostname.'" >Manage Subscription</a></div>';
        }else if(isset($settingURLObject->status) && $settingURLObject->status > 0 && $settingURLObject->status < 3){
            $iframe .= '<div style="width:100%; margin-top: 50px; padding-bottom: 10px;display:flex;justify-content:space-between;">';
            $iframe .= '<h3  style="width: 50%;">Widget Preferences:</h3>';
            $iframe .= '<div><a target="_blank" class="aioa-cancel-button"  href="'.$settingURLObject->manage_domain.'">Manage Subscription</a></div>';
            $iframe .= '</div>';
            $iframe .= '<iframe id="aioamyIframe" width="100%" style="max-width: 1920px; border: none;" height="1100px"  src="'.$settingURLObject->settinglink.'"></iframe>';
        }else{
            $iframe = '<iframe src="https://ada.skynettechnologies.us/trial-subscription?isframe=true&platform=jumpseller&website='.$aioa_website_hostname.' " height="1100px;" width="100%" style="border: none;"></iframe>';
        }
        $this->tpl->setContent($style.$Title.$iframe);
    }

    
}
