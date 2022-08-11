<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">RMA Plugin</h1>

<p align="center">Add RMA contact page into Sylius.</p>
<p align="center">/!\ Currently in alpha /!\</p>

## Quickstart


```
$ composer require ikuzostudio/rma-plugin
```

Add plugin dependencies to your `config/bundles.php` file:

```php
return [
  // ...
  Ikuzo\SyliusRMAPlugin\IkuzoSyliusRMAPlugin::class => ['all' => true],
];
```

Import required config in your `config/packages/_sylius.yaml` file:

```yaml
# config/packages/_sylius.yaml

imports:
  ...
  - { resource: "@IkuzoSyliusRMAPlugin/Resources/config/app/config.yaml"}
```

Add routes in `config/routes.yaml`

```yaml
# config/routes.yaml

ikuzo_rma_routes:
    resource: "@IkuzoSyliusRMAPlugin/Resources/config/routes.yaml"
    prefix: /{_locale}
    requirements:
        _locale: ^[a-z]{2}(?:_[A-Z]{2})?$
```

Add the RMAChannelInterface to the Channel model and implement it with the RMAChannelTrait
```php

use Ikuzo\SyliusRMAPlugin\Model\RMAChannelInterface;
use Ikuzo\SyliusRMAPlugin\Model\RMAChannelTrait;

class Channel extends BaseChannel implements RMAChannelInterface
{
    use RMAChannelTrait;
}
```

Create a migration and run it
```bash
bin/console make:migration
bin/console doctrine:migration:migrate
```

Go in the admin panel and enable the RMA for the wanted channels

