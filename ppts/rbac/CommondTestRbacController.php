<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use app\models\User;

/**
 * This is class file for testRbac.
 * @author Emma Fu
 */
class TestRbacController extends Controller
{
    /**
     * Init system auth using rbac
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        // add the rule
        //添加一条规则
        $rule = new \app\rbac\AuthorRule;
        $auth->add($rule);

        // add "createPost" permission 添加“创建文章”的权限
        $createPost = $auth->createPermission('create-post');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // add "updatePost" permission 添加“更新文章”的权限
        $updatePost = $auth->createPermission('update-post');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        // add "author" role and give this role the "createPost" permission
        //创建一个“作者”角色，并给它“创建文章”的权限
        $author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createPost);

        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        // 添加“admin”角色，给它“更新文章”的权限
        // “作者”角色
        $admin = $auth->createRole('testAdmin');
        $auth->add($admin);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $author);



        // add the "updateOwnPost" permission and associate the rule with it.
        //添加“updateOwnPost”权限，并且和上面的规则关联起来
        $updateOwnPost = $auth->createPermission('update-own-post');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);

        // "updateOwnPost" will be used from "updatePost"
        $auth->addChild($updateOwnPost, $updatePost);

        // allow "author" to update their own posts
        $auth->addChild($author, $updateOwnPost);



        // Assign roles to users. 5 and 9 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        // 给用户指定角色，5和9是IdentityInterface::getId()返回的ID，就是用户ID。
        $auth->assign($author, 13);
        $auth->assign($admin, 1);
    }

    public function actionDown()
    {
        $auth = Yii::$app->authManager;
        $auth->deleteMyCreate();
        $auth->removeAllRules();

    }
}
