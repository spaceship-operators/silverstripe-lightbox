<?php

class HtmlEditorField_ToolbarLightboxExtension extends DataExtension {

	public function updateLinkForm(&$form) {
		$enabled = (int)$this->owner->request->getVar('lightbox');
		$fields = $form->fields;

		$field = $fields->dataFieldByName('LinkType');
		$options = $field->getSource();

		if ($enabled) {
			$options['lightbox'] = 'Lightbox';
		} else {
			// remove "anchor" for lightbox, since it's page specific
			unset($options['anchor']);
		}
		$field->setSource($options);
		$fields->replaceField('LinkType', $field);

		if ($enabled) {
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
}

class HtmlEditorFieldLightboxExtension extends DataExtension {
	public function onBeforeRender () {
		if ($this->owner instanceof HtmlEditorField) {
			Requirements::javascript('lightbox/javascript/lightbox_admin.js');
		}
	}
}