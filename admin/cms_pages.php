<?php
/******************************************
* @Modified on Mar 07, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php"; 

$cmsPageObj=new CmsPages();
$pages=array();
$cmsPages=$cmsPageObj->allCmsPages();
$parentPages=$cmsPageObj->allCmsPages();
//delete page
	if(isset($_GET['delete']))
	{
		if($cmsPageObj->deletePage(base64_decode($_GET['p_id'])))
		{
			$_SESSION['msgsuccess'] = 'page has been deleted successfully';
			header('Location:'.URL_SITE.'/admin/cms_pages.php');
		}
	}
//end

//change status
if(isset($_GET['status']) && isset($_GET['p_id']))
{
	if($cmsPageObj->changeStatus(base64_decode($_GET['p_id']),$_GET['status'])){
		$_SESSION['msgsuccess'] = 'Page status has been changed successfully.';
		header('Location:'.URL_SITE.'/admin/cms_pages.php');
	}//else{}
}
//end
if(count($cmsPages))
{
	 $pages=$cmsPages;
}else
{
	$pages=$pages;
}
//echo "<pre>";print_R($pages);echo "</pre>";
$pagesArray= $pages;

/*$objPage = new PS_Pagination($pages, $rows_per_page = 10, $links_per_page = 5, $append="");
$pagesArray= $objPage->paginate();
$pagesArray= $pages;
$smarty->assign('pagesArray',$pagesArray);
if(is_array($parentPages))
{
	$smarty->assign('parentPages',$parentPages);
}*/

?>
<!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">

		<aside class="containerRadmin">
			<?php include_once $basedir."/include/adminLeft.php"; ?>
		</aside>

		<!-- left side -->
		<div class="containerLadmin">

		<h3>Cms Pages</h3>

		<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
			<tbody>
				<tr>
					<th bgcolor="#eeeeee">Page Name</th>
					<th bgcolor="#eeeeee">Url</th>
					<th bgcolor="#eeeeee">Parent Page</th>
					<th bgcolor="#eeeeee">Description</th>
					<th bgcolor="#eeeeee">Tag</th>
					<th bgcolor="#eeeeee">Actions</th>
				</tr>
				<?php
				if(isset($pagesArray) && count($pagesArray) >0){
					//print_r($pagesArray);die;

					foreach($pagesArray as $key =>$pages){?>
					<tr>
						<td><?php echo $pages['page_name'];?></td>
						<td><?php echo $pages['url'];?></td>
						<td><?php if(isset($parentPages) && !empty($pages['parent_page_id'])){
							
							foreach($parentPages as $key => $parentpage){
							
								if($pages['parent_page_id'] == $parentpage['id']){
									echo $parentpage['page_name'];
								}
							}
							}?>
						</td>
						<td><?php echo substr(stripslashes($pages['description']),0,50);?></td>
						<td><?php echo $pages['tag'];?></td>
						<td><?php if($pages['is_active'] =='Y'){?>
						<a href="<?php URL_SITE;?>/admin/cms_pages.php?status=N&p_id=<?php echo base64_encode($pages['id']);?>">Unpublish</a><?php }else{ ?><a href="<?php echo URL_SITE;?>/admin/cms_pages.php?status=Y&p_id=<?php echo base64_encode($pages['id']);?>">Publish</a><?php } ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="<?php echo URL_SITE;?>/admin/add_new_page.php?edit&p_id=<?php echo base64_encode($pages['id']);?>">Edit</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo URL_SITE;?>/admin/cms_pages.php?delete&p_id=<?php echo base64_encode($pages['id']);?>" onclick="return confirm('Do you want to  delete this page?');">Delete</a></td>
					</tr>
				<?php }
				}
				else{ ?>
					<tr>
						<td>No Record Found.</td>
					</tr>
			<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>