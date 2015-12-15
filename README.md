# NavBarBundle
### Version: 1.0.3-dev

The NavBarBundle allow to create a navbar from navbar.yml files placed into the Resources/config directory.

#####Installation

Register the bundle into app/appKernel.php

```
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            [...]
            new Cscfa\Bundle\NavBarBundle\CscfaNavBarBundle(),
        );
        
        [...]
    }
}
```

#####The navbar.yml file construction

The default place of the 'navbar.yml' file is into 'Resources/config' directory. It's possible to change this place by using the following configuration : 

```
cscfa_nav_bar:
    files_path: "path"
```
_note the given path must start with '/' and contain the complete file name. (example with the default configuration : '/Resources/config/navbar.yml')_

######The file format is :

```
navbar:
	name:
		label: ""
		roles: []
		parent: ""
		path: {route: "", param: {}}
		url: ""
		options: mixed
```

The 'navbar' element is the root element.

The 'name' element is the identifyer of the navbar element, it can be used for define parent and will be used as label if 'label' is not defined.

The 'label' element is used as element text for rendering. It's an optional element.

The 'roles' element is used to render or not the element, according with the current user roles. It's an optional element.

The 'parent' element is used to define the parent element of the current for the rendering logic. It's an optional element.

The 'path' and 'url' elements are used to define the navbar element destination. The 'path' element is a generated application route with 'route' as route name and 'param' as route parameters. It's an optional element.

The 'options' element is used to send options to the render template. It's an optional element.

#####The template

Use twig function '{{ renderNavbar() }}' to display the navbar with the default template.

To use personnal template, use the app/config/config.yml with the following parameters: 

```
cscfa_nav_bar:
    template: "bundleName:directory:template.html.twig"
    child_template: "bundleName:directory:template.html.twig"
```

The 'template' parameter define the root template of the navbar. The template receive the navbar element into the 'element' variable.

The 'child_template' parameter define the rendered template for each elements of the navbar. This template receive a child into 'element' variable and the navbar nesting level into 'nestedLevel' variable.
