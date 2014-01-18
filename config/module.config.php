<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'MwUser\Controller\Admin' => 'MwUser\Controller\AdminController'
        )
    ),
    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'child_routes' => array(
                    'mwuseradmin' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/users[/:action][/:id]',
                            'defaults' => array(
                                'controller' => 'MwUser\Controller\Admin',
                                'action' => 'index'
                            )
                        )
                    )
                )
            )
        )
    ),
    'navigation' => array(
        'admin' => array(
            'mwuser' => array(
                'type' => 'mvc',
                'route' => 'zfcadmin/mwuseradmin',
                'label' => 'Users'
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    )
);