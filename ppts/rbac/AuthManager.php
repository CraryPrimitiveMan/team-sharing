<?php
namespace app\components;

use Yii;
use ReflectionClass;
use ReflectionMethod;
use yii\rbac\DbManager;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

/**
 * @inheritdoc
 *
 * @author Harry Sun
 */
class AuthManager extends DbManager
{
    /**
     * rbac all states cache key
     */
    const STATES_CACHE_KEY = 'rbac-auth-all-states';
    /**
     * rbac all apis cache key
     */
    const APIS_CACHE_KEY = 'rbac-auth-all-apis';

    /**
     * state config path
     */
    public $jsonPath;

    /**
     * Init authManager
     */
    public function init()
    {
        parent::init();
        $this->jsonPath = Yii::getAlias('@app') . '/../frontend/build/scripts/states.json';
    }

    /**
     * Get the permissions based on permission type and name
     */
    public function getForbiddenPermissions($type, $permissionName)
    {
        $methodName = 'getAll' . ucwords($type);
        $permissions = $this->$methodName();
        return $permissions[$permissionName];
    }

    /**
     * Get all states from the config file and cache it
     */
    public function getAllStates()
    {
        $result = $this->getCacheStates();

        if (!empty($result)) {
            return $result;
        } else {
            $result = [];
        }

        $data = file_get_contents($this->jsonPath);
        $states = Json::decode($data);
        ArrayHelper::getColumn($states['data'], function ($state) use (&$result) {
            if (is_array($state) && isset($state[0])) {
                $state = $state[0];
            }
            list($stateId, $others) = explode('-', $state);
            $result[$stateId . '-*'][] = $state;
        });
        $this->cacheStates($result);
        return $result;
    }

    /**
     * Get all apis and cache it
     * Scan the controller directory to get all apis
     */
    public function getAllApis()
    {
        $apis = $this->getCacheApis();

        if (!empty($apis)) {
            return $apis;
        } else {
            $apis = [];
        }
        $path = Yii::getAlias('@app/controllers');
        $fileNames = scandir($path);

        foreach ($fileNames as $fileName) {
            if ($fileName == '.' || $fileName == '..') {
                continue;
            }

            if (!is_file($path . '/' . $fileName)) {
                continue;
            }

            $controllerName = str_replace('.php', '', $fileName);
            $controllerId = $this->formatName(str_replace('Controller.php', '', $fileName));
            $className = 'app\\controllers\\' . $controllerName;
            $controller = new ReflectionClass($className);
            $methods = $controller->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $methodName = $method->name;
                if ('actions' !== $methodName && strpos($methodName, 'action') === 0) {
                    $api = $controllerId . '/' . $this->formatName(str_replace('action', '', $methodName));
                    $apis[$controllerId . '/*'][] = $api;
                    //array_push($apis, $api);
                }
            }

            // get the actions
            $moduleId = Yii::$app->id;
            $actions = (new $className($controllerId, $moduleId))->actions();
            if (is_array($actions)) {
                $actions = array_keys($actions);
                foreach ($actions as $actionName) {
                    $api = $controllerId . '/' . $this->formatName($actionName);
                    $apis[$controllerId . '/*'][] = $api;
                }
            }
        }

        $this->cacheApis($apis);
        return $apis;
    }

    /**
     * Formate the controller and action name
     * Change ChargeLog to charge-log
     */
    private function formatName($name)
    {
        // ChargeLogController.php  charge-log
        $nameArr = preg_split('/(?=[A-Z])/', $name, 0, PREG_SPLIT_NO_EMPTY);
        return strtolower(implode('-', $nameArr));
    }

    /**
     * Get all states cache
     */
    public function getCacheStates()
    {
        Yii::$app->cache->get(static::STATES_CACHE_KEY);
    }

    /**
     * Set all states cache
     */
    public function cacheStates($states)
    {
        Yii::$app->cache->set(static::STATES_CACHE_KEY, $states);
    }

    /**
     * Delete all states cache
     */
    public function delCachedStates()
    {
        Yii::$app->cache->delete(static::STATES_CACHE_KEY);
    }

    /**
     * Get all apis cache
     */
    public function getCacheApis()
    {
        Yii::$app->cache->get(static::APIS_CACHE_KEY);
    }

    /**
     * Set all apis cache
     */
    public function cacheApis($apis)
    {
        Yii::$app->cache->set(static::APIS_CACHE_KEY, $apis);
    }

    /**
     * Delete all apis cache
     */
    public function delCachedApis()
    {
        Yii::$app->cache->delete(static::APIS_CACHE_KEY);
    }

    /**
     * Delete all apis and states cache
     */
    public function delAllCache()
    {
        $this->delCachedStates();
        $this->delCachedApis();
    }
    /**
     * Returns all forbidden api permissions that the specified user.
     * @param string $userId the user id
     * @return string[] all apis permissions that the user
     */
    public function getForbiddenApiByUser($userId)
    {
        return $this->getForbiddenPermissionByUser($userId, 'apis');
    }

    /**
     * Returns all forbidden state permissions that the specified user.
     * @param string $userId the user id
     * @return string[] all states permissions that the user
     */
    public function getForbiddenStateByUser($userId)
    {
        return $this->getForbiddenPermissionByUser($userId, 'states');
    }

    /**
     * Returns all forbidden widget permissions that the specified user.
     * @param string $userId the user id
     * @return string[] all widgets permissions that the user
     */
    public function getForbiddenWidgetByUser($userId)
    {
        return $this->getForbiddenPermissionByUser($userId, 'widgets');
    }

    /**
     * Returns all forbidden permissions that the specified user.
     * @param string $userId the user id
     * @param string $type the permission type
     * @return string[] all widgets permissions that the user
     */
    private function getForbiddenPermissionByUser($userId, $type)
    {
        $permissions = $this->getPermissionsByRole('user-' . $userId . '-' . $type);
        $forbiddenPermissions = $this->getPermissionsByRole('forbidden-' . $type);
        $permissions = ArrayHelper::getColumn($permissions, 'name');
        $forbiddenPermissions = ArrayHelper::getColumn($forbiddenPermissions, 'name');
        return array_diff($forbiddenPermissions, $permissions);
    }

    /**
     * Returns whether the user has a role.
     * @param string $userId the user id
     * @param string $role the role name
     * @return boolean
     */
    public function hasRole($userId, $roleName)
    {
        $parentRoleName = $this->getName($userId, 'apis');
        $childRoleName = $this->getKeyName($roleName, 'apis');
        $parentRole = $this->getRole($parentRoleName);
        if (empty($parentRole)) {
            return false;
        } else {
            return $this->hasChild($parentRole, $this->getRole($childRoleName));
        }
    }

    /**
     * Init a empty role. if it doesn't exists, create it and remove it's all children
     * @param  string $roleName role name
     * @return Role
     */
    public function getInitRole($roleName, $isRemoveChildren = false)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        if (empty($role)) {
            // add role
            $role = $auth->createRole($roleName);
            $auth->add($role);
        }
        if ($isRemoveChildren) {
            $auth->removeChildren($role);
        }
        return $role;
    }

    /**
     * Get role's name.
     * @param int User's id
     * @param string the suffix of role's name
     * @return string role's name
     */
    public function getName($userId, $suffix)
    {
        return 'user-' . $userId . '-' . $suffix;
    }

    /**
     * Get role's name.
     * @param string key
     * @param string the suffix of role's name
     * @return string role's name
     */
    public function getKeyName($key, $suffix)
    {
        return $key. '-' . $suffix;
    }

    /**
     * Add permission and role then assign role to user.
     * @param int User's id
     * @param object like item conclude name and description
     * @return boolean whether save successfully
     */
    public function saveRoles($userId, $items)
    {
        $auth = Yii::$app->authManager;

        $db = Yii::$app->db;
        $tran = $db->beginTransaction();
        try {
            $names = [$this->getName($userId, 'apis'), $this->getName($userId, 'states'), $this->getName($userId, 'widgets')];

            foreach ($names as $name) {
                $this->getInitRole($name, true);
            }

            $suffixs = ['apis', 'widgets', 'states'];
            foreach ($suffixs as $suffix) { // Add new relationship between roles in auth_item_child.
                $parent = $this->getRole($this->getName($userId, $suffix));
                foreach ($items as $itemName) {
                    $child = $this->getRole($this->getKeyName($itemName, $suffix));
                    if (!$this->addChild($parent, $child)) {
                        throw new BadRequestHttpException(Yii::t('common', 'user_auth_save_fail'));
                    }
                }
            }

            $tran->commit();
        } catch (HttpException $e) {
            $tran->rollBack();
            throw $e;
        }

        return ['ok' => true];
    }

    /**
     * Get one user's all roles.
     * @param int User's id
     * @return array user's roles
     */
    public function getRoleByUserId($userId)
    {
        $apiName = $this->getName($userId, 'apis');
        $allApiRoles = $this->getChildren($apiName);
        $existRoles = [];
        foreach ($allApiRoles as $apiRole) {
            array_push($existRoles, substr($apiRole->name, 0, -5));
        }

        return ['existRoles' => $existRoles];
    }

    /**
    *Demo test rbac
    */
    public function deleteMyCreate()
    {
        $arr = ['testAdmin', 'author', 'create-post', 'update-post', 'update-own-post'];
        foreach ($arr as $value) {
            $item = $this->getItem($value);
            $this->removeItem($item);
        }
    }
}
