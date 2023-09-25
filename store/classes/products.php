<?php
class products extends WebFunctions
{
	function ManageCategory($Data)
	{
		extract($Data);
		if($PageType=='Delete')
		{
			$this->DeleteCategoryProducts($RecordID);
			$this->query("delete from p_category where ParentID='$RecordID'");
			$this->query("delete from p_category where TableID='$RecordID'");
		}
		else if($PageType=='Edit')
		{
			$UrlKeyword = $this->CheckUrlKeyword($Title, 'p_category', $RecordID);
			$this->query("update p_category set 
			ParentID='$ParentID', 
			Title='$Title', 
			Status='$Status', 
			UrlKeyword='$UrlKeyword' 
			where TableID='$RecordID'");
			$_SESSION[CONFIRMATION_MESSAGE] = "Category Updated Successfully";
		}
		else
		{
			$this->query("select max(Sequence) as Sequence from p_category where ParentID='$ParentID'");
			$this->next_Record();
			if(!is_numeric($this->f('Sequence')))
				$Sequence = 1;
			else
				$Sequence = 1 + $this->f('Sequence');
			$UrlKeyword = $this->CheckUrlKeyword($Title, 'p_category');
			$this->query("insert into p_category set 
			ParentID='$ParentID', 
			Title='$Title', 
			Status='$Status', 
			Sequence='$Sequence', 
			UrlKeyword='$UrlKeyword'");
			$_SESSION[CONFIRMATION_MESSAGE] = "Category Added Successfully";
		}
	}
	
	function ManageColor($Data)
	{
		extract($Data);
		if($PageType=='Delete')
		{
			$this->query("delete from p_color where TableID='$RecordID'");
		}
		else if($PageType=='Edit')
		{
			$this->query("update p_color set 
			Title='$Title', 
			Status='$Status', 
			ColorCode='$ColorCode' 
			where TableID='$RecordID'");
			$_SESSION[CONFIRMATION_MESSAGE] = "Color Updated Successfully";
		}
		else
		{
			$this->query("insert into p_color set 
			Title='$Title', 
			ColorCode='$ColorCode', 
			Status='$Status'");
			$_SESSION[CONFIRMATION_MESSAGE] = "Color Added Successfully";
		}
	}
	
	function ManageSize($Data)
	{
		extract($Data);
		if($PageType=='Delete')
		{
			$this->query("delete from p_size where TableID='$RecordID'");
		}
		else if($PageType=='Edit')
		{
			$this->query("update p_size set 
			Title='$Title', 
			Status='$Status' 
			where TableID='$RecordID'");
			$_SESSION[CONFIRMATION_MESSAGE] = "Size Updated Successfully";
		}
		else
		{
			$this->query("insert into p_size set 
			Title='$Title', 
			Status='$Status'");
			$_SESSION[CONFIRMATION_MESSAGE] = "Size Added Successfully";
		}
	}
	
	function ManageBrand($Data)
	{
		extract($Data);
		if($PageType=='Delete')
		{
			$File = $this->getFieldData("Image", "TableID", $RecordID, "p_brands");
			if($File!='')
			{
				unlink("../".IMAGES_FOLDER."/".$File);
				unlink("../".ORIGINAL_IMAGES."/".$File);
			}
			$this->query("delete from p_brands where TableID='$RecordID'");
		}
		else if($PageType=='Edit')
		{
			$this->query("update p_brands set 
			Title='$Title', 
			Status='$Status' 
			where TableID='$RecordID'");
			$_SESSION[CONFIRMATION_MESSAGE] = "Brand Updated Successfully";
		}
		else
		{
			$this->query("insert into p_brands set 
			Title='$Title', 
			Status='$Status'");
			$_SESSION[CONFIRMATION_MESSAGE] = "Brand Added Successfully";
		}
	}
	
	function ManageProduct($Data)
	{
		extract($Data);
		if($PageType=='Delete')
		{
			$this->DeleteProduct($RecordID);
		}
		else if($PageType=='Edit')
		{
			$UrlKeyword = $this->CheckUrlKeyword($Title, 'products', $RecordID);
			$this->query("update products set 
			BrandID='$BrandID',
			CategoryID='$CategoryID', 
			Title='$Title', 
			ShortDesc='$ShortDesc', 
			Description='$Description', 
			Price='$Price', 
			Stock='$Stock', 
			Status='$Status', 
			InHome='$InHome', 
			InFeatured='$InFeatured', 
			UrlKeyword='$UrlKeyword' 
			where TableID='$RecordID'");
			
			$this->UpdateProductColors($RecordID, $Colors);
			$this->UpdateProductSize($RecordID, $Sizes);
			
			$_SESSION[CONFIRMATION_MESSAGE] = "Product Updated Successfully";
		}
		else
		{
			$Code = $this->ProductCode();
			$UrlKeyword = $this->CheckUrlKeyword($Title, 'products', '');
			$this->query("select max(Sequence) as Sequence 
			from products where CategoryID='$CategoryID'");
			$this->next_Record();
			if(!is_numeric($this->f('Sequence')))
				$Sequence = 1;
			else
				$Sequence = 1 + $this->f('Sequence');
			$this->query("insert into products set 
			BrandID='$BrandID',
			CategoryID='$CategoryID', 
			Title='$Title', 
			ShortDesc='$ShortDesc', 
			Code='$Code', 
			Description='$Description', 
			Price='$Price', 
			Stock='$Stock', 
			Status='$Status', 
			InHome='$InHome', 
			InFeatured='$InFeatured', 
			UrlKeyword='$UrlKeyword', 
			Sequence='$Sequence'");
			
			$ProductID = $this->MysqlInsertID();
			
			$this->UpdateProductColors($ProductID, $Colors);
			$this->UpdateProductSize($ProductID, $Sizes);
			
			$_SESSION[CONFIRMATION_MESSAGE] = "Product Added Successfully";
		}
	}
	
	function ProductCode()
	{
		$Code = date("YmdHis").rand(1, 1000);
		$Code = md5($Code);
		$Code = substr($Code, 0, 6);
		return $Code;
	}
	
	function UpdateProductColors($ProductID, $Colors)
	{
		$this->query("delete from product_color where ProductID='$ProductID'");
		if(is_array($Colors) && count($Colors)>0)
		{
			foreach($Colors as $Color)
			{
				$this->query("insert into product_color set 
				ProductID='$ProductID', 
				ColorID='$Color'");
			}
		}
		else
		{
			$this->query("insert into product_color set 
			ProductID='$ProductID', 
			ColorID='-1'");
		}
	}
	
	function UpdateProductSize($ProductID, $Sizes)
	{
		$this->query("delete from product_size where ProductID='$ProductID'");
		if(is_array($Sizes) && count($Sizes)>0)
		{
			$Count=0;
			foreach($Sizes as $Size)
			{
				$StockField = 'SizesStock_'.$Size;
				//$Stock = $_REQUEST[$StockField];
				$this->query("insert into product_size set 
				ProductID='$ProductID', 
				SizeID='$Size'");
				$Count++;
			}
		}
		else
		{
			$this->query("insert into product_size set 
			ProductID='$ProductID', 
			SizeID='-1'");
		}
	}
	
	function ProductPrice($InPromotion, $InWholeSale, $Sale_StartDate, $Sale_EndDate, $OriginalPrice, $SalePrice)
	{
		$Today = date("Y-m-d");
		if($InPromotion==ACTIVE && $Sale_StartDate<=$Today && $Sale_EndDate>=$Today)
		{
			$ProductPrice = '<p class="price"><span class="line-through">'.$this->ShowPrice($OriginalPrice).'</span> '.$this->ShowPrice($SalePrice).'</p>';
			return $ProductPrice;
		}
		else
		{
			$Price = $this->ShowPrice($OriginalPrice);
			$ProductPrice = '<p class="price">'.$Price.'</p>';
			return $ProductPrice;
		}
	}
	
	function ProductPriceForCart($InPromotion, $InWholeSale, $Sale_StartDate, $Sale_EndDate, $OriginalPrice, $SalePrice, $Quantity=1)
	{
		$Today = date("Y-m-d");
		
		if($InPromotion==ACTIVE && $Sale_StartDate<=$Today && $Sale_EndDate>=$Today)
			return $this->ShowPrice($SalePrice, $Quantity);
		else
			return $this->ShowPrice($OriginalPrice, $Quantity);
	}
	
	function ShowPrice($Price, $Quantity=1)
	{
		if(!isset($_SESSION['Currency']) || $_SESSION['Currency']==DEFAULT_CURRENCY)
		{
			return '<span class="dollar">'.DEFAULT_CURRENCY.'</span> '. ($Price * $Quantity);
		}
		else
		{
			$Convert = $Price * $_SESSION['Currency_Rate'];
			return $_SESSION['Currency']." ".($Convert * $Quantity);
		}
	}
	
	function ConvertAmount($from, $to)
	{
		$url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $from . $to .'=X';
		$handle = @fopen($url, 'r');
		 
		if ($handle) {
			$result = fgets($handle, 4096);
			fclose($handle);
		}
		
		$allData = explode(',',$result);
		$dollarValue = $allData[1];
		 
		return number_format($dollarValue, 3);
	}
	
	function UpdateCurrency()
	{
		if(isset($_REQUEST['ChangeCurrency']) && $_REQUEST['ChangeCurrency']!='')
		{
			if($_REQUEST['ChangeCurrency']!=DEFAULT_CURRENCY)
			{
				$_SESSION['Currency_Rate'] = $this->ConvertAmount(DEFAULT_CURRENCY, $_REQUEST['ChangeCurrency']);
				$_SESSION['Currency'] = $_REQUEST['ChangeCurrency'];
			}
			else
			{
				$_SESSION['Currency'] = DEFAULT_CURRENCY;
			}
		}
		else
		{
			if(!isset($_SESSION['Currency']))
				$_SESSION['Currency'] = DEFAULT_CURRENCY;
		}
	}
	
	function CountCart()
	{
		$TotalItems = $this->CountCartProduct();
		return $TotalItems;
	}
	
	function CountCartProduct()
	{
		if(!isset($_SESSION[USER_CART]))
			$_SESSION[USER_CART]=array();
					
		$TotalItems = 0;
		foreach($_SESSION[USER_CART] as $Cart)
		{
			$TotalItems = $TotalItems + $Cart["Quantity"];
		}
		
		return $TotalItems;
	}
	
	function UpdateStatus($Data)
	{
		extract($Data);
		$this->query("update orders set OrderStatus='$OrderStatus' where TableID='$OrderID'");
		$Return["Error"]=0;
		$Return["Msg"]='Order Status Updated Successfully.';
		echo json_encode($Return);
	}
	
	function DeleteOrder($RecordID)
	{
		$this->query("delete from order_detail where OrderID='$RecordID'");
		$this->query("delete from orders where TableID='$RecordID'");
	}
	
	function DeleteProduct($RecordID='')
	{
		$ImageName = $this->getFieldData("Image", "TableID", $RecordID, "products");
		unlink('../'.IMAGES_FOLDER."/".$ImageName);
		unlink('../'.ORIGINAL_IMAGES."/".$ImageName);
		$this->query("select * from web_images 
		where 
		Type='".IMAGE_TYPE_PRODUCT_GALLERY."' and 
		ParentID='$RecordID'");
		if($this->num_rows()>0)
		{
			while($this->next_Record())
			{
				$ImageName = $this->f('FileName');
				unlink('../'.IMAGES_FOLDER."/".$ImageName);
				unlink('../'.ORIGINAL_IMAGES."/".$ImageName);
			}
		}
		$this->query("delete from product_size where ProductID='$RecordID'");
		$this->query("delete from product_color where ProductID='$RecordID'");
		$this->query("delete from products where TableID='$RecordID'");
	}
	
	function DeleteCategoryProducts($RecordID='')
	{
		if($RecordID!='')
		{
			$this->query("select * from products where CategoryID='$RecordID'");
			if($this->num_rows()>0)
			{
				while($this->next_Record())
				{
					$ProductIDs[] = $this->f('TableID');
				}
				
				foreach($ProductIDs as $ProductID)
				{
					$this->DeleteProduct($ProductID);
				}
			}
		}
	}
	
	function ShowProducts($Query='')
	{
		if($Query!='')
		{
			$this->query($Query);
			if($this->num_rows()==0)
			{
				echo '<div align="center"><br /><br />No products found</div>';
			}
			else
			{
				$Sno=0;
				echo '<div class="listing"><ul id="FeaturedProducts">';
				while($this->next_Record())
				{
				$Sno++;
				$NoMargin='';
				if($Sno==5)
				{
				$NoMargin=' margin-left-0';
				$Sno=0;
				}
				
				$ProductID = $this->f('TableID');
				$Title = $this->f('Title');
				$ProductLink = WEB_URL.'product/'.$this->f('UrlKeyword').".html";
				if($this->f('Image')!='')
				$Image = WEB_URL.IMAGES_FOLDER."/".$this->f('Image');
				else
				$Image = WEB_URL."images/no-product.jpg";
				
				$Price = $this->f('Price');
				$Title = $this->f('Title');
				?>
				<li class="productdetaillink" data-link="<?php  echo $ProductLink; ?>">
                    <div class="item" style="background-image:url(<?php echo $Image; ?>);">
                        <div class="item-content">
                            <div class="item-top-content">
                                <div class="item-top-content-inner">
                                    <div class="item-product">
                                    <div class="item-top-title">
                                    <h2><?php echo $Title; ?></h2>
                                    </div>
                                    </div>
                                    <div class="item-product-price">
                                    <span class="price-num"><?php echo DEFAULT_CURRENCY." ".$Price; ?></span>
                                    </div>
                                </div>	
                            </div>
                            <div class="item-add-content">
                                <div class="item-add-content-inner">
									<?php if($this->f('Stock')>0){ ?>
                                <div class="section"><a href="<?php echo $ProductLink; ?>" class="btn buy expand">Buy now</a></div>
								<?php } else { ?> 
									<div class="section btn buy expand">Out Of Stock</div>
									<?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
				</li>
				<?php
				}
				echo '</ul><div class="clear"></div></div>';
			}
		}
	}
	
	function ProductSubCategories($CategoryID)
	{
		$this->query("select * from p_category 
		where Status='".ACTIVE."' and 
		ParentID='$CategoryID' 
		order by Sequence asc");
		
		if($this->num_rows()>0)
		{
			echo '<ul>';
			while($this->next_Record())
			{
				$Link = WEB_URL."products/sub-category/".$this->f('UrlKeyword').".html";
				$Title = $this->f('Title');
				echo '<li><a class="tooltip" title="'.$Title.'" href="'.$Link.'">'.$this->SubStrT($Title, 25).'</a>';
			}
			echo '</ul>';
		}
	}
	
	function GenerateCodes($Data)
	{
		extract($Data);
		for($a=1; $a<=$Loop; $a++)
		{
			$Code = $this->ValidateCode();
			$this->query("insert into discounts set 
			Code='$Code', 
			Discount='$Discount',
			CDate='".date("Y-m-d")."'");
		}
		$_SESSION[CONFIRMATION_MESSAGE] = "Codes Generated Successfully";
	}
	function ValidateCode()
	{
		$Code = $this->GeneratePassword(6);
		$this->query("select * from discounts where Code='$Code'");
		if($this->num_rows()==0)
			return $Code;
		else
			$this->ValidateCode();
	}
}
?>