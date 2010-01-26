<?
defined('C5_EXECUTE') or die(_("Access Denied."));
$uc = Page::getByPath("/dashboard/users");
$ucp = new Permissions($uc);
if (!$ucp->canRead()) {
	die(_("You have no access to users."));
}

$cnt = Loader::controller('/dashboard/users/search');
$userList = $cnt->getRequestedSearchResults();
$users = $userList->getPage();
$pagination = $userList->getPagination();
if (!isset($mode)) {
	$mode = $_REQUEST['mode'];
}
?>

<div id="ccm-search-overlay" >
	
		<table id="ccm-search-form-table" >
			<tr>
				<td valign="top" class="ccm-search-form-advanced-col">
					<? Loader::element('users/search_form_advanced', array('mode' => $mode)) ; ?>
				</td>		
				<? /* <div id="ccm-file-search-advanced-fields-gutter">&nbsp;</div> */ ?>		
				<td valign="top" width="100%">	
					
					<div id="ccm-search-advanced-results-wrapper">
					
						<div id="ccm-user-search-results">
						
							<? Loader::element('users/search_results', array('mode' => $mode, 'users' => $users, 'userList' => $userList, 'pagination' => $pagination)); ?>
						
						</div>
					
					</div>
				
				</td>	
			</tr>
		</table>		

</div>

<script type="text/javascript">
$(function() {
	ccm_setupAdvancedSearch('user');
});
</script>