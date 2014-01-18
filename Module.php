<?php
namespace MwUser;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use ZfcUser\Entity;
use ZfcUser\Mapper;
use MwUser\Mapper as MwUserMapper;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ConfigProviderInterface, BootstrapListenerInterface, AutoloaderProviderInterface, ServiceProviderInterface
{

    /**
     * (non-PHPdoc)
     * 
     * @see \Zend\ModuleManager\Feature\BootstrapListenerInterface::onBootstrap()
     */
    public function onBootstrap(EventInterface $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $em = $eventManager->getSharedManager();
        $em->attach('ZfcUser\Form\Login', 'init', function ($e)
        {
            /* @var $form \ZfcUser\Form\Login */
            $form = $e->getTarget();
            $form->get('identity')->setAttribute('class', 'form-control');
            $form->get('credential')->setAttribute('class', 'form-control');
            $form->get('submit')->setAttribute('class', 'btn btn-primary');
        });
        $em->attach('ZfcUser\Form\Register', 'init', function ($e)
        {
            /* @var $form \ZfcUser\Form\Register */
            $form = $e->getTarget();
            $form->get('email')->setAttribute('class', 'form-control')
                               ->setAttribute('autocomplete', 'off');
            $form->get('password')->setAttribute('class', 'form-control');
            $form->get('passwordVerify')->setAttribute('class', 'form-control');
            $form->get('username')->setAttribute('class', 'form-control');
            $form->get('submit')->setAttribute('class', 'btn btn-primary');
            
            $form->add(array(
                'type' => 'select',
                'name' => 'state',
                'attributes' => array(
                    'class' => 'form-control',
                ),
                'options' => array(
                    'label' => 'Active',
                    'value_options' => array(
                        'Inactive',
                        'Active'
                    )
                ),
                
            ));
        });
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\ModuleManager\Feature\ConfigProviderInterface::getConfig()
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
    
    /**
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\ServiceProviderInterface::getServiceConfig()
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'mwuser_mapper_admin' => function ($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $mapper = new MwUserMapper\UserAdministration();
                    $mapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                    $entityClass = $options->getUserEntityClass();
                    $mapper->setEntityPrototype(new $entityClass);
                    $mapper->setHydrator(new Mapper\UserHydrator());
                    $mapper->setTableName($options->getTableName());
                    return $mapper;
                }
            )
        );
    }
}