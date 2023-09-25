<div class="header">
<div class="heading"><h1>Welcome <?php echo $_SESSION[ADMIN_LOGGED_IN]["Name"]; ?></h1></div>
<div class="last-login">You logged in last time on <?php echo $Web->FormatDate($_SESSION[ADMIN_LOGGED_IN]["LastLogin"], "F d Y"); ?></div>
</div>
<div class="clearfix"></div>
<div class="ddsmoothmenu" id="TopMenu">
<ul>
    <li><a href="index.php?page=content">Content</a></li>
    <li><a href="index.php?page=banners">Banners</a></li>
    <li><a href="#">Products</a>
    	<ul>
            <li><a href="index.php?page=product-categories">Product Categories</a></li>
            <li><a href="index.php?page=product-brands">Product Brands</a></li>
            <li><a href="index.php?page=product-colors">Product Color</a></li>
            <li><a href="index.php?page=product-size">Product Size</a></li>
            <li><a href="index.php?page=products">Products</a></li>
        </ul>
    </li>
    <li><a href="index.php?page=product-orders">Orders</a></li>
    <li><a href="#">Discount Codes</a>
    	<ul>
        	<li><a href="index.php?page=generate-code">Generate New Codes</a></li>
            <li><a href="index.php?page=view-code">View Codes</a></li>
        </ul>
    </li>
    <li><a href="index.php?page=subscribers">Subscribers</a></li>
    <li><a href="index.php?page=users">Users</a></li>
    <li><a href="index.php?page=settings">Settings</a></li>
    <li><a href="index.php?page=change-password">Change Password</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
</div>