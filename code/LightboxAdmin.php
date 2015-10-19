<?php

class LightboxAdmin extends ModelAdmin {

	static $managed_models = array(
		'Lightbox'
	);

	static $menu_title = 'Lightboxes';

	static $url_segment = 'lightboxes';

	public function getEditForm($id = null, $fields = null) {
		$form = parent::getEditForm($id, $fields);
		$gridfield = $form->fields->first()->getConfig();

		// TODO: not sure why it's not getting placed before ExportButton, but will look after first commit
		$gridfield->addComponent(new GridFieldAddNewMultiClass(), 'GridFieldExportButton');
		$gridfield->removeComponentsByType('GridFieldAddNewButton');

		return $form;
	}
}