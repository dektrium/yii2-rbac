Installation
============

This document will guide you through the process of installing Yii2-rbac using **composer**. Installation is a quick and
easy three-step process.

> **NOTE:** Before we start make sure that you have properly configured **db** application component.


Step 1: Download using composer
-------------------------------

Add Yii2-rbac to the require section of your **composer.json** file:

```js
{
    "require": {
        "dektrium/yii2-rbac": "dev-master"
    }
}
```

And run following command to download extension using **composer**:

```bash
$ php composer.phar update
```

Step 2: Configure your application
----------------------------------

Add rbac module to both web and console config files as follows:

```php
...
'modules' => [
    ...
    'rbac' => [
        'class' => 'dektrium\rbac\Module',
    ],
    ...
],
...
```

Step 3: Updating database schema
--------------------------------

After you downloaded and configured Yii2-rbac, the last thing you need to do is updating your database schema by applying
the migration:

```bash
$ php yii migrate/up --migrationPath=@yii/rbac/migrations
```
