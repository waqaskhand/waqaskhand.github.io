<aside class="fleft categories-list">
	<div class="heading" id="ShowMobileCat"><h2>Browse by Category</h2></div>
    <div class="categories-ul">
    <ul id="menu">
    <?php
		foreach($pCategories as $pCategory)
		{
	?>
    	<li><a href="<?php echo $pCategory[2]; ?>"><?php echo $pCategory[1]; ?></a><?php echo $Products->ProductSubCategories($pCategory[0]); ?></li>
    <?php
		}
	?>    
    </ul>
    </div>
</aside>