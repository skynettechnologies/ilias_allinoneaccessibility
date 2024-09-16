<?php

/**
 * Class ilAllinoneaccessibilityUIHookGUI
 * @author            Skynet Technologies USA LLC <hello@skynettechnologies.com>
 * @version $Id$
 * @ingroup ServicesUIComponent
 * @ilCtrl_isCalledBy ilAllinoneaccessibilityUIHookGUI: ilUIPluginRouterGUI, ilAdministrationGUI,ilAllinoneaccessibilityGUI
 */

class ilAllinoneaccessibilityUIHookGUI extends ilUIHookPluginGUI {

  protected $plugin;

  public function __construct()
  {
    $this->plugin = ilAllinoneaccessibilityPlugin::getInstance();
  }
  
  function getHTML(string $a_comp, string $a_part, array $a_par = []): array
  {
    if($a_part == "template_get" && strpos($a_par["tpl_id"], "standardpage.html") !== false) 
    {
      $html = $a_par["html"];
      $html = str_replace('</head>', '<script id="aioa-adawidget" src="https://www.skynettechnologies.com/accessibility/js/all-in-one-accessibility-js-widget-minify.js?aioa_reg_req=true&colorcode=&token=&position=bottom_right" async=""async=""></script>' . "\n" .'</head>', $html);
      
      return ["mode" => ilUIHookPluginGUI::REPLACE, "html" => $html];
    } 
    return ["mode" => ilUIHookPluginGUI::KEEP, "html" => ""];
  }

  function modifyGUI(string $a_comp, string $a_part, array $a_par = []): void
	{
	}
}