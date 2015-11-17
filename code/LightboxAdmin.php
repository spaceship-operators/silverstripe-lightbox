<?php

/**
 * Class to handle a ModelAdmin section on the left panel in the CMS.
 */
class LightboxAdmin extends ModelAdmin {

	static $managed_models = array(
		'Lightbox'
	);

	private static $menu_title = 'Lightboxes';

	private static $url_segment = 'lightboxes';

	private static $menu_icon = 'framework/admin/images/menu-icons/16x16/blog.png';

	public function getEditForm($id = null, $fields = null) {
		$form = parent::getEditForm($id, $fields);
		$gridfield = $form->fields->first()->getConfig();

		// TODO: not sure why it's not getting placed before ExportButton, but will look after first commit
		$gridfield->addComponent(new GridFieldAddNewMultiClass(), 'GridFieldExportButton');
		$gridfield->removeComponentsByType('GridFieldAddNewButton');

		return $form;
	}

	public static function getLightboxField($label = 'lightbox', $name = 'Lightbox') {
		$lightboxArr = DataObject::get('Lightbox')->toArray();
		$lightboxes = array();
		foreach ($lightboxArr as $lightbox) {
			$lightboxes[$lightbox->ID] = $lightbox->Title;
		}
		return new DropdownField(
			$label,
			$name,
			$lightboxes
		);
	}
}