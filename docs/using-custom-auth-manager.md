Using custom auth manager
=========================

Yii2-rbac provides special database auth manager that extends default manager provided by Yii. However in some projects
you may need to override in order to add some features that you need. Or you may want to use php files instead of database
to store auth data. In this case your class should implement interface `dektrium\rbac\components\ManagerInterface`:

```php

namespace app\components;

class MyManager extends \yii\rbac\PhpManager implements \dektrium\rbac\components\ManagerInterface
{
    public function getItems($type = null, $excludeItems = [])
    {
        // you should implement this method or extend your class from \dektrium\rbac\components\DbManager
    }

    public function getItem($name)
    {
        // you should implement this method or extend your class from \dektrium\rbac\components\DbManager
    }
}
```

After you created your class you should add it to your config file:

```php
[
    'components' => [
        'authManager' => [
            'class' => 'app\components\MyManager',
        ],
    ],
]
```