## ESTRUTURA HMVC

Uma estrutura simples e fácil de ser utilizada, que pode ser implementada em qualquer tipo de projeto.

Abraços, Luis Eduardo.

Esta estrutura pode ser divididas em módulos, por exemplo, tenho um site e uma área administrativa, 
a área administrativa seria um módulo na estrutura que conteria seus próprios controllers,views e models, 
mas que também poderia utilizar os models gerais do sistema!

As views são mais organizadas, se eu criar um controller com o nome homeController e um ação chamada index nesse controller e utilizar simplesmente:
```php 
$this->loadView(); 
```
ele irá procurar dentro das views uma pasta com o nome do controller e o arquivo com o nome da ação (views/home/index.php) ou você pode simplesmente passar o nome da view desejeada: 
```php
$this->loadView("outra/outra.php"); 
```
É possível também você ter um "template" para cada módulo do sistema, apenas guardando header.php e footer.php dentro da pasta theme que vai estar na pasta geral do sistema (views/theme/..) ou dentro do módulo em específico (modules/views/theme/..).