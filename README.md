[![](http://img.shields.io/badge/license-MIT-green.svg?style=flat)](https://github.com/concrete5/documentation_generator/blob/master/LICENSE.md)
[![](http://img.shields.io/packagist/v/concrete5/documentation_generator.svg?style=flat)](https://packagist.org/packages/concrete5/documentation_generator)
[![](http://img.shields.io/packagist/dt/concrete5/documentation_generator.svg?style=flat)](https://packagist.org/packages/concrete5/documentation_generator)
[![](http://img.shields.io/scrutinizer/g/concrete5/documentation_generator.svg?style=flat)](http://scrutinizer-ci.com/g/concrete5/documentation_generator/)

# concrete5 documentation generator
This composer package adds commands to concrete5's command line utility that
generate documentation based on the core configuration values.

## Installation

### Using composer
1. Require this composer package `"concrete/documentation_generator"`
2. Add the base service provider to your `application/config/app.php`
    ```php
    <?php
    // application/config/app.php

    return array(
        'providers' => array(
            'documentation_generator' => "\\Concrete\\DocumentationGenerator\\ServiceProvider"
        )
    );
    ```

### Using concrete5 package system
1. Run composer install
2. Move the clone or symbolically link to your packages directory
3. Navigate to the install page in your concrete5 dashboard and hit install

## Usage
From the root of your concrete5 directory, run `$ ./concrete/bin/concrete5 c5:docs:generate > docs.md`

## Generators

 Handle | Class | Command | Description
 --- | --- | --- | ---
 `asset_list` | `\Concrete\DocumentationGenerator\Generator\Asset\AssetListGenerator` | c5:docs:asset_list:generate | Generates a list of core assets and asset groups
 `config_group` | `\Concrete\DocumentationGenerator\Generator\ConfigGroup\ConfigGroupListGenerator` | c5:docs:config_group:generate | Generates a list of core configuration groups with a table of the configuration items they contain
 `service_provider_list` | `\Concrete\DocumentationGenerator\Generator\ServiceProvider\ServiceProviderListGenerator` | c5:docs:service_provider_list:generate | Generates a list of core service providers
