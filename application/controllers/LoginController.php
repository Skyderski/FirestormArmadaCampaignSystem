<?php

class LoginController extends Zend_Controller_Action
{
    
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
                'MD5(CONCAT(?, password_salt))'
                );
        
      /*  $adapter
    ->setTableName('users')
    ->setIdentityColumn('username')
    ->setCredentialColumn('password');
        */
        // Get our authentication adapter and check credentials
        $formValues = $form->getValues();
        
       $adapter
            ->setIdentity ( $formValues['username'] )
            ->setCredential ( $formValues['password'] );
        //$adapter = $this->getAuthAdapter($form->getValues());
        $auth    = Zend_Auth::getInstance();
        
        $result  = $auth->authenticate($adapter);
        if (!$result->isValid()) {
            // Invalid credentials
            $form->setDescription('Invalid credentials provided');
            $this->view->form = $form;
            return $this->render('index'); // re-render the login form
        }

        // We're authenticated! Redirect to the home page
        $this->_helper->redirector('index', 'index');
    }
 
    public function loginAction()
    {
        
        $db = $this->_getParam('db');
        $form = new Application_Form_Login();
        
        $this->view->formLogin = $form;
   
        //$loginForm = new Application_Form_Login($_POST);
        if ($this->_request->isPost()) {  
        $formData = $this->_request->getPost();
        if ($form->isValid($formData)) {
 
            $adapter = new Zend_Auth_Adapter_DbTable(
                $db,
                'users',
                'username',
                'password',
                'MD5(CONCAT(?, salt))'
                //'MD5((?))'
                );
 
            $adapter->setIdentity($form->getValue('email'));
            $adapter->setCredential($form->getValue('password'));
            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($adapter);
 
            if ($result->isValid()) {
                $user = $adapter->getResultRowObject(null, 'password');
                $auth->getStorage()->write($user);
                $this->_helper->FlashMessenger('Successful Login');
                $this->_helper->redirector('index', 'index');
                return;
            }
            else{
             switch ($result->getCode()) {

                    case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                        printf("Identifiant inconnu");
                        break;

                    case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                        printf("Mot de passe incorrect");
                        $messages = $result->getMessages();
                        foreach ($messages as $i => $message) {
                              printf($message);
                        }
                        break;

                    case Zend_Auth_Result::SUCCESS:
                        /** do stuff for successful authentication **/
                        break;

                    default:
                        /** do stuff for other failure **/
                        break;
                
            }}}}
        
        /*
        $db = $this->_getParam('db');
 
        $loginForm = new Application_Form_Login();
 
        if ($loginForm->isValid($_POST)) {
 
            $adapter = new Zend_Auth_Adapter_DbTable(
                $db,
                'users',
                'username',
                'password'
                );
            
            
 
            $adapter->setIdentity($loginForm->getValue('username'));
            $adapter->setCredential($loginForm->getValue('password'));
 
            $auth   = Zend_Auth::getInstance();
            $result = $auth->authenticate($adapter);
 
            if ($result->isValid()) {
                $this->view->test="OK";
                $this->_helper->FlashMessenger('Successful Login');
                $this->_redirect('/');
                return;
            }
 
        }*/
 
        $this->view->loginForm = $form;
 
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
                $this->_helper->redirector('index');
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
