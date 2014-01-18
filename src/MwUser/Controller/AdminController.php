<?php
namespace MwUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZfcUser\Service\User as UserService;
use ZfcUser\Entity\User;

class AdminController extends AbstractActionController
{

    /**
     *
     * @var UserService
     */
    protected $userService;

    /**
     *
     * @return \ZfcUser\Service\User
     */
    public function getUserService()
    {
        if (! $this->userService) {
            $mapper = $this->getServiceLocator()->get('mwuser_mapper_admin');
            $service = $this->getServiceLocator()->get('zfcuser_user_service');
            $service->setUserMapper($mapper);
            
            $this->setUserService($service);
        }
        return $this->userService;
    }

    /**
     *
     * @param UserService $userService            
     * @return \MwUser\Controller\AdminController
     */
    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
        return $this;
    }

    public function indexAction()
    {
        $mapper = $this->getUserService()->getUserMapper();
        $page = (int) $this->params('page', 1);
        return array(
            'users' => $mapper->fetchAll($page)
        );
    }
    
    public function editAction()
    {
        $id = (int) $this->params('id');
        $mapper = $this->getUserService()->getUserMapper();
        
        $user = new User();
        $form = $this->getUserService()->getRegisterForm();
        
        if($id) {
            $user = $mapper->findById($id);
        }
        
        if($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                if(!$id) {
                    $this->getUserService()->register($form->getData());
                } else {
                    #$this->getUserService()->getUserMapper()->insert($user);
                }
                return $this->redirect()->toRoute('zfcadmin/mwuseradmin');
            }
        }
        
        return array(
            'form' => $form
        );
    }
    
    public function deleteAction()
    {
        $id = (int) $this->params('id');
        $mapper = $this->getUserService()->getUserMapper();
        if($user = $mapper->findById($id)) {
            $mapper->deleteUser($user);
        }
        return $this->redirect()->toRoute('zfcadmin/mwuseradmin');
    }
}