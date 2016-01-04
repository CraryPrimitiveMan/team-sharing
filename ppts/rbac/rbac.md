Title: Yii2 Rbac 的使用及实现原理
url: https://github.com/ksky521/nodePPT
Author: Emma Fu

[slide style="background-image:url('../bg10.jpg')"]
<h1 style="color:#BBDBD0;">Yii2 Rbac 的使用及实现原理 </h1>
<h1 style="color:#BBDBD0;">演讲者: Emma Fu</h1>

[slide data-transition="horizontal3d" style="background-image:url('../bg11.jpg'); color:black;"]
# 概述 {:&.flexbox.vleft}

- 基于角色的访问控制提供了简单而又功能强大的集中的访问控制。
- Yii提供了两种鉴权管理器：yii\rbac\PhpManager 和 yii\rbac\DbManager。
- 前者使用一个PHP脚本文件管理鉴权数据，而后者是把数据存储在数据库里面。
- 假如你的应用不需要经常变动的角色和权限管理，你可以考虑前者。
- yii\rbac\PhpManager默认把RBAC数据存储在@app/data/rbac.php文件里面，你可能需要手动的创建它。
- Yii本身的DbManager类已将RBAC的增删改查都实现好了，我们可以直接使用。

[slide data-transition="horizontal3d" style="background-image:url('../bg11.jpg'); color:black;"]
# 目录 {:&.flexbox.vleft}

- Rbac的基本用法
- 实现原理
- 项目中的用法
- 基本用法和项目中用法的区别

[slide data-transition="horizontal3d" style="background-image:url('../bg9.jpg'); color:black;"]
# 配置RBAC管理器 {:&.flexbox.vleft}


1.在定义鉴权数据并执行访问检查之前，必须先配置authManager组件。

```
return [
    // ...
    'components' => [
        'authManager' => [
             'class' => 'yii\rbac\DbManager', //用数据库管理
        ],
        // ...
   ],
 ];
```
2.authManager可以通过\Yii::$app->authManager来访问。

[slide data-transition="horizontal3d" style="background-image:url('../bg11.jpg'); color:black;"]
#基本类 {:&.flexbox.vleft}

- yii\rbac: Item 为角色或者权限的基类，其中用字段type来标识(Role:1, Permission:2)

- yii\rbac: Role 为代表角色的类

- yii\rbac: Permission 为代表权限的类

- yii\rbac: Assignment 为代表用户角色或者权限的类

- yii\rbac: Rule 为代表角色或权限能否执行的判定规则表


[slide data-transition="horizontal3d" style="background-image:url('../bg9.jpg'); color:black;"]
# 包含关系 {:&.flexbox.vleft}

除了角色可以包含角色外，权限也是可以包含权限的。也就是说auth_item表里面有三个关系：
```
角色 包含 权限

角色 包含 角色

权限 包含 权限
```

如果要得到一个角色的所有的权限，要做两方面的查找，一个是递归查找当前权限所有的子权限， 一个是查看所包含的角色的所有的权限以及子权限。

所以在使用中不建议让权限继承，只让角色继承。而且继承深度也不宜太深。


[slide data-transition="horizontal3d" style="background-image:url('../bg9.jpg'); color:black;"]
# 数据表 {:&.flexbox.vleft}
## 1. auth_item

 | name | type | description | rule_name | data | created_at | updated_at |
```
name: 权限名|角色名（必须是唯一的）

type: 类型，标注是权限或是角色

description: 描述

rule_name: 所使用的规则名

data：与此item相关的数据
```

## 2. auth_item_child

 | parent | child |
```
parent:角色名称|或者权限名称

child: parent角色拥有的权限或角色
```

[slide data-transition="horizontal3d" style="background-image:url('../bg9.jpg'); color:black;"]
# 数据表 {:&.flexbox.vleft}

## 3. auth_assignment

 | item_name | user_id | created_at |
```
item_name: 权限或角色名称

user_id: 用户的id
```
## 4. auth_rule

 | name | data | created_at | updated_at |
```
name: 规则的名称

data:与该规则有关的数据
```
[slide data-transition="horizontal3d" style="background-color:#BBDBD0;]
# 表结构之间的关系 {:&.flexbox.vleft}

<img src="../baseRelation.png" width = "300" height = "200" alt="角色和权限间的关系" align=left />


[slide data-transition="horizontal3d" style="background-image:url('../bg11.jpg'); color:black;"]
#  构建鉴权数据 {:&.flexbox.vleft}
	1.定义角色和权限

	2.建立角色和权限间的关系

	3.定义规则

	4.将规则与角色和权限关联起来

	5.把角色分配给用户

[slide data-transition="horizontal3d" style="background-image:url('../bg9.jpg'); color:black;"]
 # 定义角色和权限 {:&.flexbox.vleft}
```
public function actionInit()
{
	$auth = Yii::$app->authManager;
    //创建权限
	$createPost = $auth->createPermission('createPost');
	$createPost->description = 'Create a post';
    //保存权限到auth_item表中
	$auth->add($createPost);
    //创建角色
	$author = $auth->createRole('author');
    //保存角色到auth_item表中
	$auth->add($author);
    ...
}
```


[slide data-transition="horizontal3d" style="background-image:url('../bg9.jpg'); color:black;"]
# 建立角色和权限间的关系 {:&.flexbox.vleft}
```
public function actionInit()
{
    ...
    //建立角色和权限间的对应关系
	$auth->addChild($author, $createPost);
    ...
}
```

[slide data-transition="horizontal3d" style="background-image:url('../bg9.jpg'); color:black;"]
# 定义规则 {:&.flexbox.vleft}
	```
	class AuthorRule extends Rule
	{
	    public $name = 'isAuthor';

	    public function execute($userId, $item, $params)
	    {
		    return isset($params['post']) ? $params['post']->createdBy == $user : false;
	    }
	}
	```
[slide data-transition="horizontal3d" style="background-image:url('../bg9.jpg'); color:black;"]
# 将规则与角色和权限关联起来 {:&.flexbox.vleft}
```
public function actionInit()
{
    // add the rule
    //添加一条规则
	$rule = new \app\rbac\AuthorRule;

	$auth->add($rule);

	// add the "updateOwnPost" permission and associate the rule with it.

	//添加“updateOwnPost”权限，并且和上面的规则关联起来

	$updateOwnPost = $this->auth->createPermission('updateOwnPost');

	$updateOwnPost->description = 'Update own post';

	$updateOwnPost->ruleName = $rule->name;

	$auth->add($updateOwnPost);
    ...
}
```

[slide data-transition="horizontal3d" style="background-image:url('../bg9.jpg'); color:black;"]
# 把角色分配给用户 {:&.flexbox.vleft}
```
public function actionInit()
{
    ...
    // Assign roles to users. 5 and 9 are IDs returned by IdentityInterface::getId()
    // usually implemented in your User model.
    // 给用户指定角色，5和9是IdentityInterface::getId()返回的ID，就是用户ID。
    $auth->assign($author, 13);
    $auth->assign($admin, 1);
    ...
}
```

[slide data-transition="horizontal3d" style="background-image:url('../bg9.jpg'); color:black;"]
# 访问检查 {:&.flexbox.vleft}
	当鉴权数据准备好之后，访问检查就只是调用yii\rbac\ManagerInterface::checkAccess()方法。

	Yii提供了一个快捷的方法 yii\web\User::can()来进行访问检查，底层还是调用checkAccess()方法

```
    public function beforeAction($action)
    {
        $id = $this->getBody();
        $action = Yii::$app->controller->action->id;
        //根据 rule检查user是否拥有访问某个操作的权限
        if(Yii::$app->user->can($action, $id)){
            return true;
        }else{
            throw new UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
        }
    }
```

[slide data-transition="horizontal3d" style="background-color:#BBDBD0;]
# 项目中设计的角色和权限间的关系 {:&.flexbox.vleft}

<img src="../Relation.png" width = "300" height = "200" alt="角色和权限间的关系" align=left />


# 项目中的使用

[slide data-transition="horizontal3d" style="background-image:url('../bg11.jpg'); color:black;"]
# 基本用法和项目中用法的区别 {:&.flexbox.vleft}

- 基本用法使用auth_assignment保存user所拥有的权限

- 项目中使用auth_item_child保存user所拥有的权限（user-id-apis, user-id-widgets, user-id-states）

- 基本用法使用auth_rule来检查user的访问规则（权限）

- 项目中是根据 auth_item_child中的对应关系来检查role的访问规则


[slide data-transition="horizontal3d" style="background-image:url('../bg11.jpg'); color:black;"]
# 相关网站 {:&.flexbox.vleft}

中文 https://www.360us.net/article/13.html

英文 http://www.yiiframework.com/doc-2.0/guide-security-authorization.html

[slide data-transition="horizontal3d" style="background-image:url('../bg11.jpg'); color:black;"]
# Rbac {:&.flexbox.vleft}
- In computer systems security, role-based access control (RBAC) is an approach to restricting system access to authorized users.
- It is used by the majority of enterprises with more than 500 employees,and can implement mandatory(强制) access control (MAC) or discretionary(任意) access control (DAC).

- RBAC is sometimes referred to（被称为） as role-based security.

[slide data-transition="horizontal3d" style="background-image:url('../bg11.jpg'); color:black;"]
# Rbac {:&.flexbox.vleft}
- Role-Based-Access-Control (RBAC) is a policy neutral access control mechanism defined around roles and privileges.
- The components of RBAC such as role-permissions, user-role and role-role relationships make it simple to do user assignments.
- A study in NIST has demonstrated that RBAC addresses many needs of commercial and government organizations.
- RBAC can be used to facilitate administration of security in large organizations with hundreds of users and thousands of permissions.
- Although RBAC is different from MAC and DAC access control frameworks, it can enforce these policies without any complication（错杂，混乱）.
- Its popularity is evident（很明显） from the fact that many products and businesses are using it directly or indirectly.


[slide data-transition="horizontal3d" style="background-image:url('../bg8.jpg'); color:black;"]
<div style="font-size:80px; float:left; margin-top: -119px;">
    Thank you！
</div>










