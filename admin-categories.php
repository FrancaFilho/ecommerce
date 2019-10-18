<?php 
	use \franca\PageAdm;
	use \franca\Model\User;
	use \franca\Model\Category;
	use \franca\Model\Product;
	
	$app->get("/adm/categories", function(){
		User::verifyLogin();
		$search = (isset($_GET['search'])) ? $_GET['search'] : "";
		$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
		if ($search != '') {
			$pagination = Category::getPageSearch($search, $page);
		} else {
			$pagination = Category::getPage($page);
		}
		$pages = [];
		for ($x = 0; $x < $pagination['pages']; $x++)
		{
			array_push($pages, [
				'href'=>'/adm/categories?'.http_build_query([
					'page'=>$x+1,
					'search'=>$search
				]),
				'text'=>$x+1
			]);
		}
		$page = new PageAdm();
		$page->setTpl("categories", [
			"categories"=>$pagination['data'],
			"search"=>$search,
			"pages"=>$pages
		]);	
	});
	$app->get("/adm/categories/create", function(){
		User::verifyLogin();
		$page = new PageAdm();
		$page->setTpl("categories-create");	
	});
	$app->post("/adm/categories/create", function(){
		User::verifyLogin();
		$category = new Category();
		$category->setData($_POST);
		$category->save();
		header('Location: /adm/categories');
		exit;
	});
	$app->get("/adm/categories/:idcategory/delete", function($idcategory){
		User::verifyLogin();
		$category = new Category();
		$category->get((int)$idcategory);
		$category->delete();
		header('Location: /adm/categories');
		exit;
	});
	$app->get("/adm/categories/:idcategory", function($idcategory){
		User::verifyLogin();
		$category = new Category();
		$category->get((int)$idcategory);
		$page = new PageAdm();
		$page->setTpl("categories-update", [
			'category'=>$category->getValues()
		]);	
	});
	$app->post("/adm/categories/:idcategory", function($idcategory){
		User::verifyLogin();

		$category = new Category();
		$category->get((int)$idcategory);
		$category->setData($_POST);
		$category->save();	
		header('Location: /adm/categories');
		exit;
	});

	$app->get("/adm/categories/:idcategory/products", function($idcategory){
		User::verifyLogin();

		$category = new Category();
		$category->get((int)$idcategory);
		$page = new PageAdm();
		$page->setTpl("categories-products", [
			'category'=>$category->getValues(),
			'productsRelated'=>$category->getProducts(),
			'productsNotRelated'=>$category->getProducts(false)
		]);
	});

	$app->get("/adm/categories/:idcategory/products/:idproduct/add", function($idcategory, $idproduct){
		User::verifyLogin();

		$category = new Category();
		$category->get((int)$idcategory);
		$product = new Product();
		$product->get((int)$idproduct);
		$category->addProduct($product);
		header("Location: /adm/categories/".$idcategory."/products");
		exit;
	});
	
	$app->get("/adm/categories/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct){
		User::verifyLogin();
		$category = new Category();
		$category->get((int)$idcategory);
		$product = new Product();
		$product->get((int)$idproduct);
		$category->removeProduct($product);
		header("Location: /adm/categories/".$idcategory."/products");
		exit;
	});
 ?>
