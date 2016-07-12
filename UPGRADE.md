# Upgrading instructions for Yii2-rbac

The following upgrading instructions are cumulative. That is, if you want to
upgrade from version A to version C and there is version B between A and C, you
need to following the instructions for both A and B.

## Upgrade from Yii2-rbac 0.3.* to Yii2-rbac 1.0.0

- Module's class has been renamed from `dektrium\rbac\Module` to `dektrium\rbac\RbacWebModule`. You have to update your
 config files accordingly. 

- Module's option `enableFlashMessages` has been removed. If you've used it you should remove it from your config. This
 also means that module won't show any flash messages. You have to use your own widget to display flash messages.
