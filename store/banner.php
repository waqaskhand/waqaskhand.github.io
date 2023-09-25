<?php
	$Web->query("select * from web_images 
	where Type='".IMAGE_TYPE_BANNER."' 
	order by TableID asc");
	if($Web->num_rows()>0)
	{
?>
<section class="banners hidden">
<div class="skdslider">
    <ul id="pageBanners" class="slides">
    <?php
		while($Web->next_Record())
		{
			$ImagePath = WEB_URL.IMAGES_FOLDER."/".$Web->f('FileName');
			if($Web->f('TitleOne')!='' && $Web->f('TitleTwo')!='')
			{
				$CatUrl = $Web2->getFieldData('UrlKeyword', 'TableID', $Web->f('TitleOne'), 'p_category');
				$BannerLink=WEB_URL.'products/'.$Web->f('TitleTwo').'/'.$CatUrl.'.html';
	?>
    <li><a href="<?php echo $BannerLink; ?>"><img src="<?php echo $ImagePath; ?>" alt="" /></a></li>
    <?php			
			}
			else
			{
	?>
    <li><img src="<?php echo $ImagePath; ?>" alt="" /></li>
    <?php
			}
		}
	?>
    </ul>
</div>
</section>
<?php
	}
?>