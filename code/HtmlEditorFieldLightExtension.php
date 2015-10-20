<?php

class HtmlEditorFieldLightboxExtension extends DataExtension {

	public function updateLinkForm(&$form) {
		$fields = $form->fields;

		$field = $fields->dataFieldByName('LinkType');
		$options = $field->getSource();
		$options['lightbox'] = 'Lightbox';
		$field->setSource($options);
		$fields->replaceField('LinkType', $field);

		$lightboxArr = DataObject::get('Lightbox')->toArray();
		$lightboxes = array();
		foreach ($lightboxArr as $lightbox) {
			$lightboxes[$lightbox->ID] = $lightbox->Title;
		}
		$fields->insertAfter(new DropdownField(
			'lightbox',
			'Lightbox',
			$lightboxes
		),
			'internal');

		$form->setFields($fields);
	}
}

class SiteTreeLightboxExtension extends DataExtension {

	public function updateCMSFields(FieldList $fields) {
		Requirements::javascript('lightbox/javascript/lightbox_admin.js');
	}
}