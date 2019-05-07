<?php
namespace App\Crud\Traits;

use Crud\Traits\FindMethodTrait;

trait AuthFindMethodTrait
{
    use FindMethodTrait {
        FindMethodTrait::_findRecord as _authFindRecord;
    }

    protected function _findRecord($id, Subject $subject, $auth_policy = null)
    {
        $entity = $this->_authFindRecord($id, $subject);

        if ($auth_policy) {
            $this->Authorization->authorize($auth_policy, $entity);
        }

        return $entity;

    }
}
