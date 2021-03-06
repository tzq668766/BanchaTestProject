<?php
/**
 * Articles Controller
 *
 */
class ArticlesController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		// Configure the BanchaPaginatorComponent explicitly to allow filtering on the fields below
		// This is used in the examples associations-sample.html and remote-filter-samples.html
		$this->Paginator->setAllowedFilters(array('user_id','title','published'));

		// this is the default cake code
		$articles = $this->paginate();																// added
		$this->set('articles', $articles);															// modified
		return array_merge($this->request['paging']['Article'],array('records'=>$articles)); 		// added
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Article->id = $id;
		if (!$this->Article->exists()) {
			throw new NotFoundException(__('Invalid article'));
		}
		$this->set('article', $this->Article->read(null, $id));
		return $this->Article->data;																// added
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Article->create();
			
			if(isset($this->request->params['isBancha']) && $this->request->params['isBancha']) return $this->Article->saveFieldsAndReturn($this->request->data);	 // added
			
			if ($this->Article->save($this->request->data)) {
				$this->Session->setFlash(__('The article has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The article could not be saved. Please, try again.'));
			}
		}
		$users = $this->Article->User->find('list');
		$tags = $this->Article->Tag->find('list');
		$this->set(compact('users', 'tags'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Article->id = $id;
		if (!$this->Article->exists()) {
			throw new NotFoundException(__('Invalid article'));
		}
		
		if(isset($this->request->params['isBancha']) && $this->request->params['isBancha']) return $this->Article->saveFieldsAndReturn($this->request->data);	 // added
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Article->save($this->request->data)) {
				$this->Session->setFlash(__('The article has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The article could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Article->read(null, $id);
		}
		$users = $this->Article->User->find('list');
		$tags = $this->Article->Tag->find('list');
		$this->set(compact('users', 'tags'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Article->id = $id;
		if (!$this->Article->exists()) {
			throw new NotFoundException(__('Invalid article'));
		}
		
		if(isset($this->request->params['isBancha']) && $this->request->params['isBancha']) return $this->Article->deleteAndReturn();	 // added
		
		if ($this->Article->delete()) {
			$this->Session->setFlash(__('Article deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Article was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
