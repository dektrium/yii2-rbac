# Installation

This document will guide you through the process of installing Yii2-rbac using **composer**. Installation is a quick and
easy four-step process.

> **NOTE:** Before we start make sure that you have properly configured **db** application component.

> **NOTE:** Please make sure that you don't have `authManager` component configured. It will be configured automatically
 during installation.

## Step 1: Download using composer

Download extension using [composer](https://getcomposer.org):

```bash
$ composer require dektrium/yii2-rbac:1.0.0-alpha@dev
```

## Step 2: Configure your web application

Add rbac module to web application config file as follows:

```php
...
'modules' => [
    ...
    'rbac' => 'dektrium\rbac\RbacWebModule',
    ...
],
...
```

## Step 3: Configure your console application

Add rbac module to console application config file as follows:

```php
...
'modules' => [
    ...
    'rbac' => 'dektrium\rbac\RbacConsoleModule',
    ...
],
...
```

## Step 4: Update your database schema

After you downloaded and configured Yii2-rbac, the last thing you need to do is updating your database schema by 
applying the migration:

```bash
$ php yii migrate/up --migrationPath=@yii/rbac/migrations
```
