<?php

class HtmlEditorFieldLightboxExtension extends DataExtension {

	public function updateLinkForm(&$form) {

		$fields = $form->fields;
		$field = $fields->dataFieldByName('LinkType');

		$options = $field->getSource();
		$options['lightbox'] = 'Lightbox';
		$field->setSource($options);

		$fields->replaceField('LinkType', $field);
		$form->setFields($fields);
	}
}

class SiteTreeLightboxExtension extends DataExtension {

	public function updateCMSFields(FieldList $fields) {
		Requirements::javascript('lightbox/javascript/lightbox.js');
	}
}