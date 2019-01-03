<style type="text/css">
	.form{
		height: 34px;
		border: 1px solid gray;
		padding-left: 10px;
		border-radius: 5px;
	}
</style>
<div style="margin-bottom:30px;">
	<form method="GET" action="searchResult.php">
		<input type="text" name="search_item" class="form" placeholder="<?php if($_SESSION['parent']=='home'){echo 'Search Articles..'; }else if($_SESSION['parent'] =='profile'){echo 'Search Profiles..';} else{echo 'Search';} ?>" style="width:65%;" value="" pattern=".{2,}" required/>&nbsp
		<button type="search_submit" class="btn" ><span class="glyphicon glyphicon-search"></span> Search</button>
	</form>
</div>
<?php if ($_SESSION['parent']=='home') {
	echo "<hr style='width:150%';>";
} ?>


