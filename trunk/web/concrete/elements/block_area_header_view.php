<?
defined('C5_EXECUTE') or die(_("Access Denied."));

$c = Page::getCurrentPage();
$areaStyle = $c->getAreaCustomStyleRule($a);

if (is_object($areaStyle)) { ?>
	<div id="<?=$areaStyle->getCustomStyleRuleCSSID(true)?>" class="<?=$areaStyle->getCustomStyleRuleClassName() ?> ccm-area-styles" >
<? } ?>