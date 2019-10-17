<?php 
	use \franca\PageAdm;
	use \franca\Model\User;
	use \franca\Model\Order;
	use \franca\Model\OrderStatus;
	
	$app->get("/adm/orders/:idorder/status", function($idorder){
		User::verifyLogin();
		$order = new Order();
		$order->get((int)$idorder);
		$page = new PageAdm();
		$page->setTpl("order-status", [
			'order'=>$order->getValues(),
			'status'=>OrderStatus::listAll(),
			'msgSuccess'=>Order::getSuccess(),
			'msgError'=>Order::getError()
		]);
	});
	$app->post("/adm/orders/:idorder/status", function($idorder){
		User::verifyLogin();
		if (!isset($_POST['idstatus']) || !(int)$_POST['idstatus'] > 0) {
			Order::setError("Informe o status atual.");
			header("Location: /adm/orders/".$idorder."/status");
			exit;
		}
		$order = new Order();
		$order->get((int)$idorder);
		$order->setidstatus((int)$_POST['idstatus']);
		$order->save();
		Order::setSuccess("Status atualizado.");
		header("Location: /adm/orders/".$idorder."/status");
		exit;
	});
	$app->get("/adm/orders/:idorder/delete", function($idorder){
		User::verifyLogin();
		$order = new Order();
		$order->get((int)$idorder);
		$order->delete();
		header("Location: /adm/orders");
		exit;
	});
	$app->get("/adm/orders/:idorder", function($idorder){
		User::verifyLogin();
		$order = new Order();
		$order->get((int)$idorder);
		$cart = $order->getCart();
		$page = new PageAdm();
		$page->setTpl("order", [
			'order'=>$order->getValues(),
			'cart'=>$cart->getValues(),
			'products'=>$cart->getProducts()
		]);
	});
	$app->get("/adm/orders", function(){
		User::verifyLogin();
		$search = (isset($_GET['search'])) ? $_GET['search'] : "";
		$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
		if ($search != '') {
			$pagination = Order::getPageSearch($search, $page);
		} else {
			$pagination = Order::getPage($page);
		}
		$pages = [];
		for ($x = 0; $x < $pagination['pages']; $x++)
		{
			array_push($pages, [
				'href'=>'/adm/orders?'.http_build_query([
					'page'=>$x+1,
					'search'=>$search
				]),
				'text'=>$x+1
			]);
		}
		$page = new PageAdm();
		$page->setTpl("orders", [
			"orders"=>$pagination['data'],
			"search"=>$search,
			"pages"=>$pages
		]);
	});
 ?>
