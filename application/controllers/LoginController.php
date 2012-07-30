<?php

class LoginController extends Zend_Controller_Action
{
    public function init(){
        
          $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        
           $this->initView();
           
           $this->view->messages = $this->_flashMessenger->getMessages();
       
       
        
    }
    
    public function indexAction()
    {
        $this->view->form = $this->getForm();
    }
    
    public function processAction()
    {
        $request = $this->getRequest();

        // Check if we have a POST request
        if (!$request->isPost()) {
            return $this->_helper->redirector('index');
        }

        // Get our form and validate it
        $form = $this->getForm();
        if (!$form->isValid($request->getPost())) {
            // Invalid entries
            $this->view->form = $form;
            return $this->render('index'); // re-render the login form
        }
            
            $db = $this->_getParam('db');
        
        $adapter = new Zend_Auth_Adapter_DbTable(
                $db,
                'users',
                'username',
                'password',
                'MD5(?)'
                
                );
        
        $select = $adapter->getDbSelect();
        $select->where('active = 1');
        
     
        // Get our authentication adapter and check credentials
        $formValues = $form->getValues();
        
       $adapter
            ->setIdentity ( $formValues['username'] )
            ->setCredential ( $formValues['password'] );
        
        $auth    = Zend_Auth::getInstance();
        
        $result  = $auth->authenticate($adapter);
        
        if (!$result->isValid()) {
            // Invalid credentials
            $form->setDescription('Identifiants incorrects');
            $this->view->form = $form;
            return $this->render('index'); // re-render the login form
        }

        // We're authenticated! Redirect to the home page
        $this->_helper->redirector('index', 'index');
    }
 
   
    
    public function getForm()
    {
        return new Application_Form_Login(array(
            'action' => '/login/process',
            'method' => 'post',
        ));
    }

    public function getAuthAdapter(array $params)
    {
        // Leaving this to the developer...
        // Makes the assumption that the constructor takes an array of 
        // parameters which it then uses as credentials to verify identity.
        // Our form, of course, will just pass the parameters 'username'
        // and 'password'.
    }
    
    
    public function preDispatch()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            // If the user is logged in, we don't want to show the login form;
            // however, the logout action should still be available
            if ('logout' != $this->getRequest()->getActionName()) {
                $this->_helper->redirector('index', 'index');
            }
        } else {
            // If they aren't, they can't logout, so that action should 
            // redirect to the login form
            if ('logout' == $this->getRequest()->getActionName()) {
                $this->_helper->redirector('test');
            }
        }
    }
    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index'); // back to login page
    }
 
}
?>
