<?php
declare(strict_types=1);

namespace App\Controller;

class BooksController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Books');
        $this->loadModel('Publishers');
        $this->loadModel('Authors');
        $this->loadComponent('Flash');
    }

    
    public function index()
    {
        $this->paginate = [
            'contain' => ['Publishers', 'Authors'],
        ];
        $books = $this->paginate($this->Books);

        $this->set(compact('books'));
    }

 
    public function view($id = null)
    {
        $book = $this->Books->get($id, [
            'contain' => ['Publishers', 'Authors'],
        ]);

        $this->set(compact('book'));
    }

    public function add()
    {
        $book = $this->Books->newEmptyEntity();
        if ($this->request->is('post')) {
            $book = $this->Books->patchEntity($book, $this->request->getData());
            if ($this->Books->save($book)) {
                $this->Flash->success(__('The book has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The book could not be saved. Please, try again.'));
        }
        $publishers = $this->Books->Publishers->find('list', ['limit' => 200])->all();
        
        $authors = $this->Books->Authors->find('list', ['limit' => 200])->all();
        $this->set(compact('book', 'publishers', 'authors'));
    }

  
    public function edit($id = null)
    {
        $book = $this->Books->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $book = $this->Books->patchEntity($book, $this->request->getData());
            if ($this->Books->save($book)) {
                $this->Flash->success(__('The book has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The book could not be saved. Please, try again.'));
        }
        $publishers = $this->Books->Publishers->find('list', ['limit' => 200])->all();
       
        $authors = $this->Books->Authors->find('list', ['limit' => 200])->all();
        $this->set(compact('book', 'publishers', 'authors'));
    }


    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $book = $this->Books->get($id);
        if ($this->Books->delete($book)) {
            $this->Flash->success(__('The book has been deleted.'));
        } else {
            $this->Flash->error(__('The book could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
