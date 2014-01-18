<?php
namespace MwUser\Mapper;

use ZfcUser\Mapper\User as UserMapper;
use ZfcUser\Entity\UserInterface;

class UserAdministration extends UserMapper
{

    /**
     *
     * @param number $page            
     * @return \Zend\Db\ResultSet\HydratingResultSet
     */
    public function fetchAll($page = 1)
    {
        $select = $this->getSelect()->order('user_id DESC');
        return $this->select($select);
    }
    
    /**
     * 
     * @param UserInterface $user
     * @return void|\Zend\Db\Adapter\Driver\ResultInterface
     */
    public function deleteUser(UserInterface $user)
    {
        if(!$id = $user->getId()) {
            return;
        }
        return $this->delete('user_id = ' . $id);
    }
}