<?php

class Lightbox extends DataObject implements PermissionProvider {

	public static $singular_name = 'Lightbox';
	public static $plural_name = 'Lightboxes';

	private static $db = array(
		'Title' => 'Varchar(255)',
		'CloseButtonLabel' => 'Varchar(255)',
		'Content' => 'HTMLText',
	);

	private static $summary_fields = array(
		'Title' => 'Title',
		'ClassName' => 'Type',
	);

	private static $defaults = array(
		'CloseButtonLabel' => 'Close',
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();


		return $fields;
	}


	public function canEdit($member = null) {
		return Permission::check('CMS_ACCESS_LightboxAdmin');
	}

	public function canDelete($member = null) {
		return Permission::check('CMS_ACCESS_LightboxAdmin');
	}

	public function canCreate($member = null) {
		return Permission::check('CMS_ACCESS_LightboxAdmin');
	}

	public function providePermissions() {
		return array(
			'CMS_ACCESS_LightboxAdmin' => 'Lightboxes'
		);
	}

}