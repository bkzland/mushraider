<?php
class ClassesController extends AdminAppController {
    public $uses = array('Game', 'Classe');

    var $paginate = array(
        'Classe' => array(
            'limit' => 20,
            'recursive' => 1,
            'contain' => array('Game'),
            'order' => array('Classe.game_id' => 'asc', 'Classe.title' => 'asc')
        )
    );

    var $adminOnly = true;

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        $conditions = array();
        $classes = $this->paginate('Classe', $conditions);        
        $this->set('classes', $classes);
    }

    public function add() {
        if(!empty($this->request->data['Classe'])) {            
            $toSave = array();
            $toSave['title'] = ucfirst($this->request->data['Classe']['title']);
            $toSave['slug'] = $this->Tools->slugMe($toSave['title']);
            $toSave['color'] = $this->request->data['Classe']['color'];
            $toSave['game_id'] = !empty($this->request->data['Classe']['game_id'])?$this->request->data['Classe']['game_id']:null;

            if($this->Classe->save($toSave)) {
                if($this->request->is('ajax')) {
                    Configure::write('debug', 0);
                    $this->layout = 'ajax';
                    $this->autoRender = false;
                    $dungeon = array('id' => $this->Classe->getLastInsertId(), 'title' => $toSave['title']);
                    return json_encode($dungeon);
                }else {
                    $this->Session->setFlash(__('%s has been added to your classes list', $toSave['title']), 'flash_success');
                    $this->redirect('/admin/classes');
                }
            }
        }

        if(empty($this->request->data['Classe']['color'])) {
            $this->request->data['Classe']['color'] = '#333333';
        }

        if($this->request->is('ajax')) {
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            $this->autoRender = false;
            $this->render('elements/addClasse');
            return;
        }else {
            $gamesList = $this->Game->find('list', array('order' => 'title ASC'));        
            $this->set('gamesList', $gamesList);  
        }
    }

    public function edit($id = null) {
        if(!$id) {
            $this->redirect('/admin/classes');
        }

        $params = array();
        $params['recursive'] = 1;
        $params['contain']['Game'] = array();     
        $params['conditions']['Classe.id'] = $id;
        if(!$classe = $this->Classe->find('first', $params)) {
            $this->Session->setFlash(__('MushRaider is unable to find this class oO'), 'flash_error');
            $this->redirect('/admin/classes');
        }

        if(!empty($this->request->data['Classe']) && $this->request->data['Classe']['id'] == $id) {
            $toSave = array();
            $toSave['id'] = $this->request->data['Classe']['id'];            
            $toSave['title'] = ucfirst($this->request->data['Classe']['title']);
            $toSave['slug'] = $this->Tools->slugMe($toSave['title']);
            $toSave['color'] = $this->request->data['Classe']['color'];
            $toSave['game_id'] = $this->request->data['Classe']['game_id'];
            if($this->Classe->save($toSave)) {
                $this->Session->setFlash(__('Class %s has been updated', $classe['Classe']['title']), 'flash_success');
                $this->redirect('/admin/classes');
            }

            $this->Session->setFlash(__('Something goes wrong'), 'flash_error');

            $classe['Classe'] = array_merge($classe['Classe'], $this->request->data['Classe']);
        }

        $gamesList = $this->Game->find('list', array('order' => 'title ASC'));        
        $this->set('gamesList', $gamesList);      

        $this->request->data['Classe'] = $classe['Classe'];

        if(empty($this->request->data['Classe']['color'])) {
            $this->request->data['Classe']['color'] = '#333333';
        }
    }

    public function delete($id = null) {
        if($id) {
            $params = array();
            $params['fields'] = array('id');
            $params['recursive'] = -1;
            $params['conditions']['id'] = $id;
            $params['conditions']['game_id'] = null;            
            if(!$classe = $this->Classe->find('first', $params)) {
                $this->Session->setFlash(__('This class is linked to a game, you can\'t delete it.'), 'flash_warning');
            }elseif($this->Classe->delete($id)) {
                $this->Session->setFlash(__('The class has been deleted'), 'flash_success');
            }else {
                $this->Session->setFlash(__('Something goes wrong'), 'flash_error');
            }
        }
 
        $this->redirect('/admin/classes');
    }
}