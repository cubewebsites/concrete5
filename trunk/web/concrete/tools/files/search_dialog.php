<?
defined('C5_EXECUTE') or die(_("Access Denied."));

$cp = FilePermissions::getGlobal();
if (!$cp->canAccessFileManager()) {
	die(_("Unable to access the file manager."));
}

Loader::model('file_list');

$cnt = Loader::controller('/dashboard/files/search');
$fileList = $cnt->getRequestedSearchResults();
$files = $fileList->getPage();
$pagination = $fileList->getPagination();
$searchRequest = $fileList->getSearchRequest();
?>

<div id="ccm-search-overlay" >
	
		<table id="ccm-search-form-table" >
			<tr>
				<td valign="top" class="ccm-search-form-advanced-col">
					<? Loader::element('files/search_form_advanced', array('searchRequest' => $searchRequest)); ?>
				</td>		
				<? /* <div id="ccm-file-search-advanced-fields-gutter">&nbsp;</div> */ ?>		
				<td valign="top" width="100%">	
					
					<div id="ccm-search-advanced-results-wrapper">
					
						<? Loader::element('files/upload_single'); ?>
						
						<div id="ccm-file-search-results">
						
							<? Loader::element('files/search_results', array('files' => $files, 'fileList' => $fileList, 'pagination' => $pagination)); ?>
						
						</div>
					
					</div>
				
				</td>	
			</tr>
		</table>		

</div>

<?
print '<script type="text/javascript">
$(function() {
	ccm_activateFileManager();
});
</script>';
?>