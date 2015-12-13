# NavBarBundle
### Version: 1.0.1-dev

The NavBarBundle allow to create a navbar from navbar.yml files placed into the Resources/config directory.

#####Installation

Register the bundle into app/appKernel.php

```
** *app/AppKernel.php* **
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            *[...]*
            new Cscfa\Bundle\NavBarBundle\CscfaNavBarBundle(),
        );
        
        *[...]*
    }
}
```

#####The navbar.yml file construction

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

Use twig function '{{ renderNavbar() }}' to display the navbar.

To use personnal template, use the app/config/config.yml with the following parameters: 

```
cscfa_nav_bar:
    template: "bundleName:directory:template.html.twig"
```
