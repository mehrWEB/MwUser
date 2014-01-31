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
    'bjyauthorize' => array(
        'role_providers' => array(
            // this will load roles from the user_role table in a database
            // format: user_role(role_id(varchar), parent(varchar))
            'BjyAuthorize\Provider\Role\ZendDb' => array(
                'table'                 => 'user_role',
                'identifier_field_name' => 'id',
                'role_id_field'         => 'role_id',
                'parent_role_field'     => 'parent_id',
            ),
        ),
        'guards' => array(
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'zfcuser/login', 'roles' => array('guest')),
                array('route' => 'zfcuser/logout', 'roles' => array('user')),
                array('route' => 'zfcadmin/mwuseradmin', 'roles' => array('admin')),
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