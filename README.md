# NavBarBundle
### Version: 1.0.0-dev

The NavBarBundle allow to create a navbar from navbar.yml files placed into the Resources/config directory.

#####The navbar.yml file construction

navbar:
	name:
		label: ""
		roles: []
		parent: ""
		path: {route: "", param: {}}
		url: ""
		options: mixed

The 'navbar' element is the root element.

The 'name' element is the identifyer of the navbar element, it can be used for define parent and will be used as label if 'label' is not defined.

The 'label' element is used as element text for rendering. It's an optional element.

The 'roles' element is used to render or not the element, according with the current user roles. It's an optional element.

The 'parent' element is used to define the parent element of the current for the rendering logic. It's an optional element.

The 'path' and 'url' elements are used to define the navbar element destination. The 'path' element is a generated application route with 'route' as route name and 'param' as route parameters. It's an optional element.

The 'options' element is used to send options to the render template. It's an optional element.

#####The template

The more usable method to render a navbar is actually to override the CscfaNavBarBundle:navbar:navbar.hmlt.twig

The template receive a 'NavBar' object as 'navbar' variable.
