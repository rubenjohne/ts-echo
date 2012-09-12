<?php
	class Tadashishoji_Commercebug_IndexController extends Mage_Core_Controller_Front_Action
	{	
		protected function _profileCollection()
		{
			//get the profiler
			$profiler = Mage::getSingleton('core/resource')
			->getConnection('core_read')
			->getProfiler();
						
			//enable the profiler
			$profiler->setEnabled(true);			
			
			//fetch a product collection
			$product = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('*')
			->getLastItem();
			
			//dump our id for later use
			echo(
				'ID:' . $product->getId() . "\n\n"
			);
					
			//fetch queries from profiler
			$queries = $profiler->getQueryProfiles();
		
			header('Content-Type: text/plain');
			foreach($queries as $profile)
			{
				echo '--------------------------------------------------' . "\n";
				echo $profile->getQuery() . "\n";
				echo '--------------------------------------------------' . "\n\n";
			}
			exit;		
		}
		
		protected function _profileLoad()
		{
			//get the profiler
			$profiler = Mage::getSingleton('core/resource')
			->getConnection('core_read')
			->getProfiler();
			
			//enable the profiler
			$profiler->setEnabled(true);			
			
			//fetch a product collection
			$product = Mage::getModel('catalog/product')
			->load(167);
			
			//dump our id for later use
			echo(
				'ID:' . $product->getId() . "\n\n"
			);
					
			//fetch queries from profiler
			$queries = $profiler->getQueryProfiles();
		
			header('Content-Type: text/plain');
			foreach($queries as $profile)
			{
				echo '--------------------------------------------------' . "\n";
				echo $profile->getQuery() . "\n";
				echo '--------------------------------------------------' . "\n\n";
			}
			exit;		
		}		
		
		protected function _profileLoadAndSave()
		{
			//work around weird behavior that prevents saving products
			//Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
			//get the profiler
			$profiler = Mage::getSingleton('core/resource')
			->getConnection('core_read')
			->getProfiler();
			
			//enable the profiler
			$profiler->setEnabled(true);			
			
			//fetch a product collection
			$product = Mage::getModel('catalog/product')
			->load(167);
			
			//dump our id for later use
			echo(
				'ID:   ' . $product->getId() . "\n" . 
				'Name: ' . $product->getName() . "\n\n" 
			);
					
			$product->setName('Awesome -- ' . $product->getName())
			->save();
			
			//fetch queries from profiler
			$queries = $profiler->getQueryProfiles();

			header('Content-Type: text/plain');
			foreach($queries as $profile)
			{
				echo '--------------------------------------------------' . "\n";
				echo $profile->getQuery() . "\n";
				if($params = $profile->getQueryParams())
				{
					echo "Params: [" . implode("],[",$params) . "]\n";
				}
				
				echo '--------------------------------------------------' . "\n\n";
			}
			exit;				
		}
		
		public function indexAction()
		{	
		    // Mage::app()->saveCache(false,'config_global_stores');
		    
		    $xml = Mage::getConfig()->getNode()->asXml();
		    var_dump($xml);
		    exit;
// 		    $nodes = $xml->xpath('//*');
// 		    foreach($nodes as $node)
// 		    {
// 		        var_dump((string) $node);
// 		    }
// 		    var_dump(count($nodes));
// 		    exit;
// 		    var_dump($xml->asXml());
// 		    exit;
		    foreach($xml as $node)
		    {
		        var_dump($node->getName());
		    }
		    exit(__METHOD__);
		    
		    // header('Content-Type: text/xml');
		    $xml = Mage::getConfig()->getNode()->asXml();
		    file_put_contents('/tmp/cache-new-group-and-website.xml', $xml);
		    $test = Mage::getStoreConfig('foo/baz/bar');
            var_dump($test);
            exit;
//             $block = $this->getLayout()->createBlock('curebit_socialreferrals/success');            		
//             header('Content-Type: text/plain');
//             echo $block->toHtml();
//             exit("there");
//     		Mage::dispatchEvent('sales_order_shipment_track_save_after');		    
// 		    exit("here");
//             require_once '/Users/alanstorm/Documents/github_private/GeneralCode/bin/magento-bootstrap.php';
//             $product = Mage::getModel('catalog/product')->load(93);
//             foreach($product->getTypeInstance()->getUsedProductIds() as $item)
//             {
//                 var_dump($item);
//             }
            
            
// 		    $model  = Mage::getModel('eav/entity_attribute_set')
// 		    ->load(68);
// 		    
// 		    $modelGroup = Mage::getModel('eav/entity_attribute_group')
//             ->setAttributeGroupName('Test Group')
//             ->setAttributeSetId($model->getId());
// 		    
// 		    $groups = array($modelGroup);
// 		    
// 		    $model->setGroups($groups)->save();
// 		    
// 		    var_dump('');
		    exit("here");
// 		    $item = Mage::getModel('sellsimply_seller/campaign')->getCollection()
// 		    ->getFirstItem();
// 		    $data = $item->getData();
// 		    $data['api_key']    = '43DE590B-C0CA-491B-847E-E0E796DF6501';
// 		    $data['api_key']    = '43DE590B-C0CA-491B-847E-E0E796DF6501';
// 		    $data['url_image']  = 'http://foo.example.com/some.png';
// 		    var_dump($data);
// 		    exit;
// 		    $collection = Mage::getModel('sales/quote_address_rate')->getCollection()
// 		    ->getSelect();
// 		    
// 		    echo (string) $collection;
// 		    foreach($collection as $item)
// 		    {
// 		        var_dump($item->getData());
// 		    }
		    
// 		    $customer = Mage::getModel('customer/customer')
// 		    ->load(2);
// 		    
// 		    $customer->setData('sellsimply_twitter_uid','ladeda');
// 		    $customer->setFirstname('Jack P.');
// 		    $customer->save();
// 		    var_dump($customer->getData());
		    //
		    exit;
		    //$session = Mage::getModel('core/session');
		    $session = Mage::getModel('pulsestorm_mercuryapi/session');
		    
		    $page = $session->getCurPage();
		    var_dump($page);
		    $page = $page ? $page : 1;
		    $page++;
		    $session->setCurPage($page);
		    return;
		    
		    $o = Pulsestorm_Mercuryapi_App::getService('loadcollection');		    
		    
		    $results = $o->loadCollection(array(
		    'collection_resource_model'=>'cms/page_collection',
		    'order_by'=>'title',
		    'order_by_direction'=>'ASC'
// 		    'page_size'=>'2',
// 		    'current_page'=>'3',
		    ));
		    
		    foreach($results as $result)
		    {
		        var_dump($result->getTitle());
		    }
		    
		    
// 		    $o = Pulsestorm_Mercuryapi_App::getService('savemodel');		    
// 		    $args = array(
// 		    'model'=>'catalog/product',
// 		    'load_by'=>'sku',
// 		    'load_by_value'=>'500gb7200',
// 		    'data'=>array(
// 		        'name'=>'Western Digital 500GB HD - 7200RPM',
// 		        'hardrive'=>'501 GB'
// 		    ));		    
// 		    $result = $o->loadAndSave($args);
// 		    var_dump($result);
// 		    var_dump('done');
		    
// 		    $o = Pulsestorm_Mercuryapi_App::getService('savemodel');		    
// 		    $args = array(
// 		    'model'=>'catalog/product',
// 		    'load_by'=>'sku',
// 		    'load_by_value'=>'500gb7200',
// 		    'data'=>array(
// 		        'name'=>'Western Digital 500GB HD - 7200RPM',
// 		        'hardrive'=>'501 GB'
// 		    ));		    
// 		    $result = $o->loadAndSave($args);
// 		    var_dump($result);
// 		    var_dump('done');
		    
		    
		    
		    exit;
// 		    var_dump("jhere");
// 		    $m = Mage::getModel('salesrule/rule');
// 		    var_dump($m);
// 		    
// 		    $cache = Mage::getSingleton('core/cache');
// 		    $imageFiles = unserialize($cache->load("importImageFiles"));
// 		    var_dump($imageFiles);
// 		    
            $p = Mage::getModel('catalog/product')->load(55332);
            var_dump($p->getData());

		    exit;
            //Mage::helper('pulsestorm_modelexplore/working')->main();
            //$block = $this->getLayout()->createBlock('pulsestorm_modelexplore/modelsandattributes');

//             $categories = Mage::getModel('catalog/category')
//             ->getCollection()
//             ->addAttributeToSelect('*')
//             ->addOrder('level')
//             ->getFirstItem();            
//             var_dump($categories->getData());
            exit;
//             $customer = Mage::getModel('customer/customer');            
//             $entity = $customer->getResource();
//             $attribute = Mage::getSingleton('eav/config')->getCollectionAttribute($entity->getType(), 'firstname');
//             var_dump($attribute->getId());
//             var_dump(get_class($customer));
            // var_dump($customer->load());
//             $customer->getCurPage(1);
//             $customer->setPageSize(5);
//             $customer->addAttributeToSelect('firstname');
//             $customer->load();
//             var_dump($customer->getFirstItem()->getData());
//             var_dump(__METHOD__);
//             $report = Mage::getModel('obey_paypaldebug/report')->reportAll(91);            
//             Mage::getModel('obey_paypaldebug/report')->outputBasic($report);
//             exit;
            return;
//             $test = new Obey_Paypaldebug_Model_Ipn();
//             $test = Mage::getModel('obey_paypaldebug/ipn');
//             var_dump($test);
//             return;
//             $block = $this->getLayout()->createBlock('pulsestorm_modelexplore/template')
//             ->setTemplate('key-graph.phtml');
// 
//             $this->getResponse()->setBody($block->toHtml());
//             return;
//             var_dump(__METHOD__);
//             $c = Mage::getModel('core/url_rewrite')->getCollection();
//             foreach($c as $item)
//             {
//                 var_dump($item->getData());
//             }
            // 			$searcher 	= Mage::getSingleton('pulsestorm_themedoctor/system_config_search');			
            // 			
            // 			$matches	= $searcher->exactMatchEmailtemplate('currency_import_error_email_template');
            // 			var_dump($matches);
            // 			exit;
            // 			
            // 			$c = 1;
            // 			foreach(Mage::getConfig()->getNode('global/template/email')->children() as $node)
            // 			{
            // 				$value = $node->getName();
            // 				$matches 	= $searcher->exactMatchOptions($value);
            // 				var_dump($value);
            // 				var_dump($matches);
            // 				
            // 				if($c > 1)
            // 				{
            // 					exit;
            // 				}
            // 				$c++;
            // 			}		
			
			
			
// 			$value      = 'customer_create_account_email_confirmed_template';

// 			exit("there");
			//var_dump($section_names);
// 			foreach($section_names as $section_name)
// 			{
// 				$config = Mage::getStoreConfig($section_name);
// 				var_dump($section_name);
// 				var_dump($config);
// 			}
			
			// var_dump(Mage::getStoreConfig('advanced/modules_disable_output'));
			//var_dump(array_keys($aSections));
			//var_dump($aSections);
			var_dump(array_keys($tabs));
			die("<br/>\n Died at " . __FILE__. ':' . __LINE__ . "<br/>\n");
			
			// var_dump($design->getPackageName());
			
			//gets configured "fallback" theme (i.e. default)
			//if none is set, this returns null
			// var_dump($design->getFallbackTheme());
			
			//gets constant set on design package class.
			//"always" the word "default"
			// var_dump($design->getDefaultTheme());
			
// 			$params = array();
// 			$design->updateParamDefaults($params);
// 			$params['_type'] = 'template';
// 			var_dump($design->getBaseDir($params));

			// var_dump($design->getTemplateFilename('page/3columns.phtml'));
			
// 			var_dump($design->getLocaleFileName('page/3columns.phtml'));
// 			
// 			
// 			$design_paths = Mage::getModel('pulsestorm_themedoctor/design_paths');
// 			var_dump($design_paths);
			
// 			var_dump($params);
			
// 			$this->loadLayout();
// 			$this->renderLayout();
// 			var_dump(Mage::getSingleton('core/layout')->getUpdate()->getHandles());
// 			exit;		
// 			$product = Mage::getModel('catalog/product')->load(166);		
// 			
// 			$price = $product->getTierPrice();
// 			var_dump($price);
// 			$product->getStockItem()->setQty(942);			
// 			$product->setName($product->getName().'###');
			// $product->getStockItem()->save();
// 			$product->save();
			
			
			// var_dump($product->getStockItem()->getData());
			#var_dump(  );
// 			var_dump(__METHOD__);
// 			exit;
// 			$c = Mage::getModel('catalog/product')->getCollection()
// 			->addAttributeToSelect('*');
// 			foreach($c as $item)
// 			{
// 				var_dump($item->getTypeInstance());
// 				exit;
// 			}
			// var_dump(Mage::getResourceModel('obey_adminhtml/order_grid_collection'));


// 			$a = Mage::getStoreConfig('trans_email/ident_newtrade/email');			
// 			var_dump($a);
// 			$address = Mage::getModel('customer/address')->load(181);
// 			var_dump($address->getData());
// 			exit;
			
// 			$c = Mage::getModel('customer/customer')->getCollection()
// 			->addAttributeToSelect('confirmation');
// 			
// 			foreach($c as $customer)
// 			{
// 				var_dump($customer->getData());
// 			}
			
			exit;
// 			$customer = Mage::getModel('customer/customer')->load(141);
// 			var_dump($customer->getData());
// 			exit;
			$customer->setName($customer->getName().' ');
			$customer->save();
			exit;
			
// 			$customer->setIsSubscribed(true)
// 			->setPassword('foobazbar')
// 			->save();
			
			var_dump($customer->getDefaultBilling());
			var_dump($customer->getAddressesCollection()->getFirstItem()->getData());
// 			var_dump($customer->getIsSubscribed());
			exit;		
			// 			$email_templates = Mage::getConfig()->getNode('global/template/email');
			// 			
			// 			$to_skip		 = array('sales_email_order_template','sales_email_order_guest_template');
			// 			$templates		 = array();
			// 			foreach($email_templates->children() as $node_template)
			// 			{
			// 				if(in_array($o->name, $to_skip)) { continue; }
			// 				$o					= new stdClass();
			// 				$o->name 			= $node_template->getName();
			// 				$o->label			= $node_template->label;
			// 				$o->file			= $node_template->file;
			// 				$o->type			= $node_template->type;
			// 								
			// 				$mailTemplate 		= Mage::getModel('core/email_template')->loadDefault($node_template->getName());
			// 				$processedTemplate 	= $mailTemplate->getProcessedTemplate(array());
			// 				$templates[] 		= $o;
			// 			}
			// 			
			// 			foreach($templates as $template)
			// 			{
			// 			}
			// 			
			// 			exit;
			// 			header('Content-Type: text/xml');
			// 			exit($node->asXml());
			// 			var_dump(__METHOD__);
			// 			exit;
			// 			var_dump('here');
			// 			$customer = Mage::getModel('customer/customer')->load(43);
			// 			$customer = Mage::getModel('customer/customer')->getCollection()
			// 			->addAttributeToSelect('*')
			// 			->addAttributeToSelect('trade_country')
			// 			->addFieldToFilter('entity_id',43)
			// 			->getFirstItem();
			// 			var_dump(get_class($this->getResponse()));
			$customer->setData('lastname',rand());
			//$customer->setData('trade_country','US');
			//$customer->setData('trade_company','Widgets Inc.');
			$customer->save();
			var_dump($customer->getData());
			exit;
// 			$customer->setData('trade_country','Portlandia');
// 			
// 			$customer = Mage::getModel('customer/customer')->load(43);
			
			exit;
// 			$collection = Mage::getModel('catalog/product')->getCollection()
// 				// ->setStore($this->getStore())
// 				// ->addAttributeToSelect($attributes)
// 				->addAttributeToSelect('sku')
// 				->addMinimalPrice()
// 				->addStoreFilter()
// 				->addAttributeToFilter('type_id', array_keys(
// 					Mage::getConfig()->getNode('adminhtml/sales/order/create/available_product_types')->asArray()
// 				))
// 				->addAttributeToSelect('gift_message_available')
// 				->addFieldToFilter('sku','OBEY_GIFT_CARD_PHYSICAL');
// 	
// 			Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($collection);
			
// 			$collection = Mage::getModel('catalog/product')->getCollection()			
// 			->addAttributeToSelect('sku')			
// 			->addFieldToFilter('sku','OBEY_GIFT_CARD_PHYSICAL');
			foreach($collection as $item)
			{
				var_dump($item->getSku());
			}
			var_dump("done");
// 			$customer = Mage::getModel('customer/customer')->load(37);
// 			Mage::helper('obey_tradepartners')->sendEmailToStoreOwnerForNewTradePartner($customer);
// 			
// 			
// 			exit('done');
// 			Mage::helper('obey_tradepartners')->getTradepartnerGroupId();		
// 			$email_template = Mage::getModel('core/email_template')
// 			->loadDefault('customer_create_account_email_template');			
// 			
// 			$processedTemplate = $email_template->getProcessedTemplate(array());
// 			var_dump($processedTemplate);
// 			
			//var_dump( Mage::getConfig()->getNode(Mage_Core_Model_Email_Template::XML_PATH_TEMPLATE_EMAIL)->asArray() );
// 			$ids = array(275);
// 			$orders = Mage::getModel('sales/order')->getCollection()
// 			->addFieldToFilter('entity_id',array('in'=>$ids));
// 			foreach($orders as $order)
// 			{				
// 				foreach($order->getItemsCollection() as $item)
// 				{
// 					var_dump($item->getData());
// 				}
// 			}
			//$order = Mage::getModel('sales/order')->load('272');
// 			header("Content-Type: text/plain");
// 			Mage::helper('instrument_utility')->generateCardNumbers('G','P');
// 			exit;
// 			$h = Mage::helper('ugiftcert');
// 			for($i=0;$i<500;$i++)
// 			{
// 				//string start length
// 				$n = $h->processRandomPattern('G[AN*8]P'); 
// 				$num = substr($n,0,2) . '-' .
// 				substr($n,2,4) . '-' .
// 				substr($n,6);
// 				echo $num . "," . rand(1000,9999) . "\n";
// 			}
			
			
			exit;
// 			$c = Mage::getModel('customerservice/giftcard_email')->getCollection();
// 			
// 			foreach($c as $item)
// 			{
// 				var_dump($item->getData());
// 			}
			
// 			$c = Mage::getModel('ugiftcert/cert')->getCollection()
// 			->addFieldToFilter('main_table.cert_id',116)
// 			->addHistory()
// 			->getFirstItem();
// 			
// 			$order_id = $c->getOrderId();
// 			
// 			$order = Mage::getModel('sales/order')->load($order_id);
// 			
// 			var_dump($order->getShippingAddress()->getFormated(true));
			
			// 			foreach($order->getItemsCollection() as $item)
			// 			{
			// 				var_dump($item);
			// 			}
			
			// var_dump($order->getData());
			// var_dump($order_id);
			
// 			$c = Mage::getModel('customerservice/giftcard_email')
// 			->getCollection()->sendEmailsForQuoteItemIds(372);
		
// 			$model = Mage::getModel('customerservice/giftcard_email');		
// 			$model->setQuoteItemId(22);
// 			$model->setJsonPostData(json_encode('foo'));
// 			$model->save();		
			// echo $this->getFullActionName();
			// 			Mage::getModel('customerservice/session')->setData('test','Hello');
// 			$info = Mage::getModel('customerservice/session')->getData();
// 			var_dump($info);
			// 			var_dump('Here We Go');
			
			// 			$a = array();
			// 			$quote = Mage::getSingleton('checkout/cart')->getQuote();
			// 			foreach($quote->getAllItems() as $item)
			// 			{
			// 				$data = $item->getData();
			// 				foreach($data as $key=>$value)
			// 				{			
			// 					if(is_object($value))
			// 					{
			// 						$data[$key] = $value->getData();
			// 					}	
			// 					
			// 				   foreach(
			// 					var_dump($data[$key]
			// 				}
			// 			}
			// 			var_dump($data);
			// var_dump($quote->getAllItems());
			exit(__METHOD__);
			Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
			
			//load a product
			$product = Mage::getModel('catalog/product')->load(166);
			
// 			var_dump($product->getSpecialFromDate());
			
			$product->setSpecialFromDate('');
			$product->setSpecialPrice('');
// 			var_dump($product->getSpecialFromDate());
			$product->save();
			exit("done");
// 			exit("done");
// 			//init data array for price
// 			$data = array();			
// 			
// 			//create first price (viw source on admin for each field name)
// 			$price_1			= array(
// 			'website_id' =>'0',
// 			'cust_group' =>'32000',
// 			'price_qty'  =>'3',
// 			'price'      =>'95.00',
// 			);
// 			
// 			//create second price (viw source on admin for each field name)
// 			$price_2			= array(
// 			'website_id' =>'0',
// 			'cust_group' =>'32000',
// 			'price_qty'  =>'1',
// 			'price'      =>'25.00',	
// 			);
// 			
// 			//init empty array over any current data
// 			$data['tier_price'] = array();
// 			
// 			//add our price arrays
// 			$data['tier_price'][] = $price_1;
// 			$data['tier_price'][] = $price_2;			
// 			
// 			//let magento know it needs to change the tier price 
// 			$data['tier_price_changed'] = 1;			
// 			
// 			//ADD the data (as opposed to set)
// 			$product->addData($data);
// 			
// 			//save the product
// 			$product->save();
// 			
// 			
// 			exit("saved product");
			// var_dump($product->getTierPrice());
			
			// $price_model = $product->getPriceModel();
			
			// var_dump($product->getTierPrice());
			
// 			$attribute = $product->getResource()->getAttribute('tier_price');
// 			var_dump($attribute->getBackend());
			
			#####$this->_profileCollection();
			#####$this->_profileLoad();
			#####$this->_profileLoadAndSave();
// 			$collection = Mage::getModel('catalog/product')			
// 			->getCollection()
// 			->addAttributeToSelect('*')
// 			->addOrder('entity_id','DESC')
// 			->addAttributeToFilter('name',array('like'=>'%camera%'));
// 			
// 			
// 			$profiler = Mage::getSingleton('core/resource')
// 			->getConnection('core_read')
// 			->getProfiler();
// 			
// 			//enable the profiler
// 			$profiler->setEnabled(true);						
// 			header('Content-Type: text/plain');
// 			//->addAttributeToFilter('name',array('like','%camera%'))			
// 			foreach($collection as $item)
// 			{
// 				//var_dump($item->getId());
// 				//var_dump($item->getName());
// 			}
// 
// 			foreach($profiler->getQueryProfiles() as $profile)
// 			{
// 				echo '--------------------------------------------------' . "\n";
// 				echo $profile->getQuery() . "\n";
// 				if($params = $profile->getQueryParams())
// 				{
// 					echo "Params: [" . implode("],[",$params) . "]\n";
// 				}
// 				
// 				echo '--------------------------------------------------' . "\n\n";
// 			}
// 			
// 			var_dump("here");
// 			exit;
			
			
			
			
// 			$c = Mage::getModel('pulsestorm_topnav/nav_item')->getCollection();
			
// 			$c->getSelect()->joinLeft('pulsestorm_topnav_nav_list_nav_items',
// 			'main_table.pulsestorm_topnav_nav_item_id=pulsestorm_topnav_nav_list_nav_items.pulsestorm_topnav_nav_item_id');
// 			
// 			echo (string) $c->getSelect();
// 			exit;
// 			foreach($c as $item)
// 			{
// 				var_dump($item->getData());
// 				var_dump($item->getResource()->getMainTable());
// 			}
// 			
// 			var_dump("done");
// 			$currentStore = Mage::app()->getStore()->getStoreId();
// 	
// 			$designChange = Mage::getSingleton('core/design')
// 				->loadChange($currentStore);

// 			$designChange = Mage::getSingleton('core/design')
// 			->loadChange(Mage::app()->getStore()->getStoreId());	
// 			
// 			var_dump($designChange->getData());
// 			if ($designChange->getData()) {
// 				$designPackage->setPackageName($designChange->getPackage())
// 					->setTheme($designChange->getTheme());
// 			}
				
		}
	}