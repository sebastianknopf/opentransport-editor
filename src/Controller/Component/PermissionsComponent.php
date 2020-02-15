<?php


namespace App\Controller\Component;


use Acl\Controller\Component\AclComponent;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Component for processing ACL data from GUI.
 *
 * Class PermissionsComponent
 * @package App\Controller\Component
 */
class PermissionsComponent extends Component
{
    /**
     * Transforms the array of ACO permissions into acl controlled list. The ARO must be in
     * format ['model' => 'ModelName', 'foreign_key' => $key]. The ACO array must be in format
     * [
     *      'AcoAlias1' => [
     *          'read' => 1,
     *          'create' => 0,
     *          'update' => 0,
     *          'delete' => 0
     *      ]
     * ]
     *
     * @param array $aro The aro identifier array
     * @param array $acos The array with acos and set permissions
     */
    public function processPermissions($aro = [], $acos = [])
    {
        $registry = new ComponentRegistry();
        $acl = new AclComponent($registry);

        foreach ($acos as $alias => $perms) {
            // allow or deny read (view) permission
            if ($perms['read'] == 1) {
                $acl->allow($aro, $alias, 'read');
            } else {
                $acl->deny($aro, $alias, 'read');
            }

            // allow or deny create (add) permission
            if ($perms['create'] == 1) {
                $acl->allow($aro, $alias, 'create');
            } else {
                $acl->deny($aro, $alias, 'create');
            }

            // allow or deny update (edit) permission
            if ($perms['update'] == 1) {
                $acl->allow($aro, $alias, 'update');
            } else {
                $acl->deny($aro, $alias, 'update');
            }

            // allow or deny delete permission
            if ($perms['delete'] == 1) {
                $acl->allow($aro, $alias, 'delete');
            } else {
                $acl->deny($aro, $alias, 'delete');
            }
        }
    }
}