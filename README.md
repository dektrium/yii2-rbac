Yii2-rbac
=========

Yii2-rbac provides a web interface for advanced access control and includes following features:

> **NOTE:** Module is not yet in alpha version. Use it on your own risk. Some features are missing. Anything can be changed at any time.

## Installation

Add Yii2-rbac to the require section of your **composer.json** file:

```js
{
    "require": {
        "dektrium/yii2-rbac": "dev-master"
    }
}
```

And run following command to make **composer** download and install Yii2-rbac:

```bash
$ php composer.phar update
```

## How to use

Go to `/rbac/role/index` route.

## FAQ

#### How to use?
Install it and go to `/rbac/role/index` route.

#### Why feature *X* is missing?
Because it is not implemented yet or will never be implemented. Check out roadmap.

#### How to contribute?

Contributing instructions are located in [CONTRIBUTING.md](CONTRIBUTING.md) file.

## Roadmap

- [ ] Managing roles
- [ ] Managing permissions
- [ ] Managing rules
- [ ] Assigning auth items to users
- [ ] Documentation
- [ ] Managing default roles

## License

Yii2-rbac is released under the MIT License. See the bundled [LICENSE](LICENSE) for details.
