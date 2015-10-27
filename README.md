# Lightboxes

Provides an interface to create modal/popup boxes with customisable content within.

## Requirements

To get the lightbox working on your page, you'll need to include this javascript file.
```Requirements::javascript('lightbox/javascript/lightbox.js');```

We've provided some default styling for the lightbox, you can use that by including this css file.
```Requirements::css('lightbox/css/ltbox.css');```

## Extending more DataObjects
If you want to be able to use Lightbox on other DataObjects that are not extended from SiteTree, you can do that by following these steps:
Create a LightboxItemExtension, Item could be any DataObject class
```
class LightboxItemExtension extends DataExtension {
	private static $many_many = array(
		'Items' => 'Item',
	);
}
```

Extend both that Item class and the Lightbox class, we've done this using a config.yml file
```
---
Name: mysitelightboxextensions
---
Lightbox:
  extensions:
    - LightboxExtension
Item:
  extensions:
    - DataObjectLightboxExtension
```

DataObjectLightboxExtension contains a standard `onAfterWrite()` event that handling creating a link between the DataObject and Lightbox.