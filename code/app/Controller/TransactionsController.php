<?php
App::uses('AppController', 'Controller');
/**
 * Transactions Controller
 *
 * @property Transaction $Transaction
 */
class TransactionsController extends AppController {

	
	public $paginate;
	public $HighCharts = null;
	public $components = array('HighCharts.HighCharts');
	
/**
 * index method
 *
 * @return void
 */
		
	public function index() {
		
		$this->paginate = array(
			'limit' => 20,
			'order' => array(
				'Transaction.post_date' => 'asc'
			),
			'conditions' => array(
				'Transaction.user_id' => $this->Session->read('User.id'),
			),
		);
		
		$this->Transaction->recursive = 0;
		$this->set('transactions', $this->paginate());
	}
		
	public function income() {
		
		$chartData = array();		
			
		$chartName = 'Area Chart';
		
		$mychart = $this->HighCharts->create( $chartName, 'area' );
				
		$this->HighCharts->setChartParams(
				$chartName,
				array(
						'renderTo'				=> 'areawrapper',  // div do ktoreho sa generuje graf
						'chartWidth'				=> 800,
						'chartHeight'				=> 400,
						'chartMarginTop' 			=> 60,
						'chartMarginLeft'			=> 90,
						'chartMarginRight'			=> 30,
						'chartMarginBottom'			=> 110,
						'chartSpacingRight'			=> 10,
						'chartSpacingBottom'			=> 15,
						'chartSpacingLeft'			=> 0,
						'chartAlignTicks'			=> FALSE,
						'chartBackgroundColorLinearGradient' 	=> array(0,0,0,300),
						'chartBackgroundColorStops'             => array(array(0,'rgb(217, 217, 217)'),array(1,'rgb(255, 255, 255)')),
		
						'title'					=> 'Príjmy',
						'titleAlign'				=> 'left',
						'titleFloating'				=> TRUE,
						'titleStyleFont'			=> '18px Metrophobic, Arial, sans-serif',
						'titleStyleColor'			=> '#0099ff',
						'titleX'				=> 20,
						'titleY'				=> 20,
		
						'legendEnabled' 			=> TRUE,
						'legendLayout'				=> 'horizontal',
						'legendAlign'				=> 'center',
						'legendVerticalAlign '			=> 'bottom',
						'legendItemStyle'			=> array('color' => '#222'),
						'legendBackgroundColorLinearGradient' 	=> array(0,0,0,25),
						'legendBackgroundColorStops'            => array(array(0,'rgb(217, 217, 217)'),array(1,'rgb(255, 255, 255)')),
		
						'tooltipEnabled' 			=> FALSE,
						'plotOptionsFillColor' =>  array(
								'linearGradient' => array(0, 0, 0, 300),
								'stops' => array(
										array(0, 'rgba(112, 138, 255, 1.0)'),  // Highcharts.getOptions().colors[0]
										array(1, 'rgba(2,0,0,0)')
								)
						),
						'xAxisLabelsEnabled' 			=> TRUE,
						'xAxisLabelsAlign' 			=> 'right',
						'xAxisLabelsStep' 			=> 2,
						//'xAxisLabelsRotation' 		=> -35,
						'xAxislabelsX' 				=> 5,
						'xAxisLabelsY' 				=> 20,
						'xAxisCategories'           		=> array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'),
						'yAxisTitleText' 			=> 'Suma',
						// autostep options
						'enableAutoStep' 			=> FALSE,
		
						// credits setting  [HighCharts.com  displayed on chart]
						'creditsEnabled' 			=> FALSE,
						'creditsText'  	 			=> 'Example.com',
						'creditsURL'	 			=> 'http://example.com'
				)
		);
		
		$series = $this->HighCharts->addChartSeries();
		
				
		if(!isset($this->request->data['Filter'])) {
			$data['from_date'] = '1970-01-01';
			$data['to_date'] = date('Y-m-d');
			$data['year_month_day'] = '2';
		} else {
			$data= $this->request->data['Filter'];
		}
		$this->paginate = array(
				'limit' => 20,
				'order' => array(
						'Transaction.post_date' => 'asc'
				),
				'conditions' => array(
						'Transaction.user_id' => $this->Session->read('User.id'),
						'Transaction.transaction_type_id' => '1',
						'Transaction.post_date >=' => $data['from_date'],
						'Transaction.post_date <=' => $data['to_date'],
				),
		);
		
		
	
		$this->Transaction->recursive = 0;
		$transactions = $this->paginate();
		$this->set('transactions', $transactions);
		
		$date_array = array();
		$xAxisCategories = array();
		
		
		if ($data['year_month_day'] == '1') {     // filtrovanie podla rokov ...zatial nefukcne
			$date_array[date('Y')] = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
				
			foreach ($transactions as $row) {
				$r_year = date('Y', strtotime($row['Transaction']['post_date']));
				$r_month = date('m', strtotime($row['Transaction']['post_date']));
				$date_array[$r_year][$r_month] += $row['Transaction']['amount'];
			}
				
			print_r($date_array);
				
			foreach ($date_array as $year => $val) {
				foreach ($val as $month => $val2) {
					$chartData[] = $val2;
					$xAxisCategories[] = $month;
				}
			}
		}
		else
		if ($data['year_month_day'] == '2') {		// filtrovanie podla mesiacov
	 		$date_array[date('Y')] = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
			
			foreach ($transactions as $row) {
				$r_year = date('Y', strtotime($row['Transaction']['post_date']));
				$r_month = date('m', strtotime($row['Transaction']['post_date']));
				$date_array[$r_year][$r_month] += $row['Transaction']['amount'];
			}
			
			print_r($date_array);
			
			foreach ($date_array as $year => $val) {
				foreach ($val as $month => $val2) {
					$chartData[] = $val2;
					$xAxisCategories[] = $month;
				}
			} 
		}
		else 
		if ($data['year_month_day'] == '3') {		// filtrovanie podla dni ...zatial nefukcne
			$date_array[date('m')] = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0, '24' => 0, '25' => 0, '26' => 0, '27' => 0, '28' => 0, '29' => 0, '30' => 0, '31' => 0);
	
			foreach ($transactions as $row) {
				//$r_year = date('Y', strtotime($row['Transaction']['post_date']));
				$r_month = date('m', strtotime($row['Transaction']['post_date']));
				$r_day = date('d', strtotime($row['Transaction']['post_date']));
				$date_array[$r_month][$r_day] += $row['Transaction']['amount'];
			}
			
			print_r($date_array);
			
			
				foreach ($date_array as $month => $val2) {
					foreach ($val2 as $day => $val3) {
						$chartData[] = $val3;
						$xAxisCategories[] = $day;
					}
				}
		}
				
		$this->HighCharts->setChartParams( $chartName,	array('xAxisCategories'	=> $xAxisCategories ));
		
		$series->addName('Mesiace')->addData($chartData);
		
		$mychart->addSeries($series);
	}
	
	public function expense() {
	
		$this->paginate = array(
				'limit' => 20,
				'order' => array(
						'Transaction.post_date' => 'asc'
				),
				'conditions' => array(
						'Transaction.user_id' => $this->Session->read('User.id'),
						'Transaction.transaction_type_id' => '2',
				),
		);
	
		$this->Transaction->recursive = 0;
		$this->set('transactions', $this->paginate());
	}
	
	public function date_test() {
	
		$this->paginate = array(
				'limit' => 20,
				'order' => array(
						'Transaction.post_date' => 'asc'
				),
				'conditions' => array(
						'Transaction.user_id' => $this->Session->read('User.id'),
						'Transaction.post_date <' => '2013-04-26',
				),
		);
	
		$this->Transaction->recursive = 0;
		$this->set('transactions', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->check_ownership($id) ) {
			throw new PrivateActionException(__('Na prístup k tejto operácii nemáte oprávnenie.'));
		}
		if (!$this->Transaction->exists($id)) {
			throw new NotFoundException(__('Invalid transaction'));
		}
		$options = array('conditions' => array('Transaction.' . $this->Transaction->primaryKey => $id));
		$this->set('transaction', $this->Transaction->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		
		
		/* Array
		(
				[Transaction] => Array
				(
						[transaction_type_id] => 1
						[name] => name 30
						[amount] => 30
						[category_id] => 3
						[subcategory_id] => 3
						[user_id] => 8
						[post_date] => 2013-03-04
						[repeat] => 0
						[repeat_every] => mesiac
						[number_of_cycles] => 1
				)
		
		) */
		
		if ($this->request->is('post')) {
			$this->Transaction->create();
			print_r($this->request->data);
			$data= $this->request->data['Transaction'];
			if ($this->Transaction->save($this->request->data)) {
				
				if ($data['repeat'] == 1) {
					$data['original_transaction_id'] = $this->Transaction->id;
					$data['id'] = $this->Transaction->id;
					if($this->insert_repeat($data, $data['post_date'])) {
						$this->Session->setFlash(__('The transaction has been saved'));
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('Zadajte prosím skorší deň v mesiaci pri výbere dátumu. Najneskorší povolený je 28. deň.'));
					}
					
				} else {
					$this->Session->setFlash(__('The transaction has been saved'));
					$this->redirect(array('action' => 'index'));
				}
				
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
			
		}
		
		$user_id = $this->Session->read('User.id'); 
		
		//print_r($this->Session->read('User.id'));
		$users = $this->Transaction->User->find('list');
		$this->set('transaction_types', $this->Transaction->TransactionType->find('list'));
		$this->set('categories', $this->Transaction->Category->find('list', array('conditions' => array('Category.user_id' => $user_id))));
		$this->set('subcategories', $this->Transaction->Subcategory->find('list', array('conditions' => array('Subcategory.user_id' => $user_id))));
		$this->set('user', $user_id); 	
		$this->set(compact('users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->check_ownership($id) ) {
			throw new PrivateActionException(__('Na prístup k tejto operácii nemáte oprávnenie.'));
		}
		$this->Transaction->id = $id;
		if (!$this->Transaction->exists($id)) {
			throw new NotFoundException(__('Neplatná transakcia'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			print_r($this->request->data);
			
			$data= $this->request->data['Transaction'];
			$original_date=$this->Transaction->field('post_date');
			print_r($original_date);
			unset ($this->request->data['Transaction']['original_transaction_id']);
			if ($this->Transaction->save($this->request->data)) {
				if ($data['update_next'] == 1) {
					//$this->delete_next_repeats();
					if ($data['original_transaction_id'] == '') {
						$data['original_transaction_id'] = $data['id'];
					}
					if($this->insert_repeat($data, $original_date)) {
						$this->Session->setFlash(__('Transakcia bola upravená'));
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('Transakciu sa nepodarilo upraviť. Skúste prosím znovu.'));
					}
					
				} else {
					$this->Session->setFlash(__('Transakcia bola upravená'));
					$this->redirect(array('action' => 'index'));
				}
			} else {
				$this->Session->setFlash(__('Transakciu sa nepodarilo upraviť. Skúste prosím znovu.'));
			}
			
			
			
		} else {
			$options = array('conditions' => array('Transaction.' . $this->Transaction->primaryKey => $id));
			$this->request->data = $this->Transaction->find('first', $options);
			$this->set('data', $this->request->data);
		}
		
		$user_id = $this->Session->read('User.id');
		
		$users = $this->Transaction->User->find('list');
		$this->set('transaction_types', $this->Transaction->TransactionType->find('list'));
		$this->set('categories', $this->Transaction->Category->find('list', array('conditions' => array('Category.user_id' => $user_id))));
		$this->set('subcategories', $this->Transaction->Subcategory->find('list', array('conditions' => array('Subcategory.user_id' => $user_id))));
		$this->set('user', $user_id);
		$this->set(compact('users'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->check_ownership($id) ) {
			throw new PrivateActionException(__('Na prístup k tejto operácii nemáte oprávnenie.'));
		}
		$this->Transaction->id = $id;
		if (!$this->Transaction->exists()) {
			throw new NotFoundException(__('Zlá transakcia'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Transaction->delete()) {
			$this->Session->setFlash(__('Transakcia bola vymazaná'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Transakciu sa nepodarilo vymazať'));
		$this->redirect(array('action' => 'index'));
	}
	
	
	/**
	* delete_next_repeats method
	*
	* @throws NotFoundException
	* @throws MethodNotAllowedException
	* @param string $id
	* @return void
	*/
	public function delete_next_repeats($id = null) {
		if (!$this->check_ownership($id) ) {
			throw new PrivateActionException(__('Na prístup k tejto operácii nemáte oprávnenie.'));
		}
		$this->Transaction->id = $id;
		if (!$this->Transaction->exists()) {
			throw new NotFoundException(__('Zlá transakcia'));
		}
		//$this->request->onlyAllow('post', 'delete');
		
		$original_id = $this->Transaction->field('original_transaction_id');
		$post_date = $this->Transaction->field('post_date');
		
		if ($this->Transaction->delete()) {
// 			$original_id = $this->Transaction->original_transaction_id;
// 			$post_date = $this->Transaction->post_date;
// echo $original_id.' | '.$post_date;
			if($original_id != '') {
				if($this->Transaction->deleteAll(array('Transaction.original_transaction_id' => $original_id, 'Transaction.post_date >' => $post_date ), false)) {
					$this->Session->setFlash(__('Vybratá transakcia a jej neskoršie opakovania boli vymazané.'));
					$this->redirect(array('action' => 'index'));
				}
			} else {
				if($this->Transaction->deleteAll(array('Transaction.original_transaction_id' => $id, 'Transaction.post_date >' => $post_date ), false)) {
					$this->Session->setFlash(__('Vybratá transakcia a jej neskoršie opakovania boli vymazané. 2'));
					$this->redirect(array('action' => 'index'));
				}
			}
// 			$this->Session->setFlash(__('Vybratá transakcia a jej neskoršie opakovania boli vymazané.'));
// 			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Transakcia nebola vymazaná.'));
		//$this->redirect(array('action' => 'index'));
	}
	
	private function insert_repeat($data, $original_date) {
		if(!$this->Transaction->insert_repeat($data, $original_date)) {
			return false;
		} else {
			return true;
		} 
	}
	
	/* private function insert_repeat($data, $original_date) {
		$pom_data= array();
		
		$this->Transaction->deleteAll(array('Transaction.original_transaction_id' => $data['original_transaction_id'], 'Transaction.post_date >' => $original_date, 'Transaction.id <>' => $data['id']  ), false);
			
		
		
		for ($i = 1; $i<= $data['number_of_cycles']; $i++) {
			$timestamp= strtotime($data['post_date']);
			$day_of_the_month= date('j', $timestamp);	// kolky den v mesiaci bol nastaveny
			$month_of_the_year=date('n', $timestamp);
			if ($data['repeat_every'] == 'tyzden') {   //  ak je nastavene opakovanie kazdy tyzden
				$future_timestamp= strtotime("+$i week", $timestamp);
					
				$pom_data[] =
				array(
						'transaction_type_id' => $data['transaction_type_id'],
						'name' => $data['name'],
						'amount' => $data['amount'],
						'category_id' => $data['category_id'],
						'subcategory_id' => $data['subcategory_id'],
						'user_id' => $data['user_id'],
						'post_date' => date('Y-m-d', $future_timestamp),
						'original_transaction_id' => $data['original_transaction_id'], );
					
			}
			if ($data['repeat_every'] == 'mesiac') { 		// ak je nastavene opakovanie kazdy mesiac
				if ($day_of_the_month < 29) {
					$future_timestamp= strtotime("+$i month", $timestamp);
						
					$pom_data[] =
					array(
							'transaction_type_id' => $data['transaction_type_id'],
							'name' => $data['name'],
							'amount' => $data['amount'],
							'category_id' => $data['category_id'],
							'subcategory_id' => $data['subcategory_id'],
							'user_id' => $data['user_id'],
							'post_date' => date('Y-m-d', $future_timestamp),
							'original_transaction_id' => $data['original_transaction_id'], );
				}
				else {			// nastaveny prilis neskory datum v mesiaci
					if (!$this->Transaction->exists()) {
						throw new NotFoundException(__('Zlá transakcia'));
					}
					$this->request->onlyAllow('post', 'delete');   // potrebujem zmazat prvu vytvorenu originalnu transakciu, pretoze ostatne sa nemohli vytvorit
					if ($this->Transaction->delete()) {
						//$this->Session->setFlash(__('Zbytocna transakcia bola zmazaná'));
						$this->Session->setFlash(__('Zadajte prosím skorší deň v mesiaci pri výbere dátumu. Najneskorší povolený je 28. deň.'));
						//$this->redirect(array('action' => 'index'));
					}
					//$this->Session->setFlash(__('Zbytočnú transakciu sa nepodarilo zmazať'));
					//$this->redirect(array('action' => 'index'));
				}
					
			}
			if ($data['repeat_every'] == 'rok') { 		// ak je nastavene opakovanie kazdy rok
				if (($day_of_the_month == 29) && ($month_of_the_year == 2)) {
					$future_timestamp= strtotime("+$i year -1 day", $timestamp);   // ak je nastaveny 29.feb tj. priestupny rok zmeni nasledujuce opakovania na 28.feb
		
					$pom_data[] =
					array(
							'transaction_type_id' => $data['transaction_type_id'],
							'name' => $data['name'],
							'amount' => $data['amount'],
							'category_id' => $data['category_id'],
							'subcategory_id' => $data['subcategory_id'],
							'user_id' => $data['user_id'],
							'post_date' => date('Y-m-d', $future_timestamp),
							'original_transaction_id' => $data['original_transaction_id'], );
				}
				else {
					$future_timestamp= strtotime("+$i year", $timestamp);
		
					$pom_data[] =
					array(
							'transaction_type_id' => $data['transaction_type_id'],
							'name' => $data['name'],
							'amount' => $data['amount'],
							'category_id' => $data['category_id'],
							'subcategory_id' => $data['subcategory_id'],
							'user_id' => $data['user_id'],
							'post_date' => date('Y-m-d', $future_timestamp),
							'original_transaction_id' => $this->Transaction->id );
				}
					
			}
		}
		$this->Transaction->create();
		$this->Transaction->saveMany($pom_data);
		//print_r($pom_data);
		
	} */
	
	private function check_ownership($id) {    
		$user_transaction = $this->Transaction->find('first', array(
    'conditions' => array('Transaction.id' => $id),));
		if ($this->Session->read('User.id') == $user_transaction['Transaction']['user_id']) {
			return true;
		}
		else {
			return false;
		}
 	}
 	
}
	



