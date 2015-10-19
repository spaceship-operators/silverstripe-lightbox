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
/*
		$fields->replaceField(
			'ShortDescription',
			HTMLEditorField::create('ShortDescription', 'Card Features')
				->setDescription('Displayed when the product is selected from a NIB dropdown.')
				->setRows(6)
				->addExtraClass('stacked')
		);

		$fields->replaceField(
			'HTMLDescription',
			HTMLEditorField::create('HTMLDescription', 'Full description')
				->setRows(14)
				->addExtraClass('stacked')
		);

		$fields->addFieldsToTab('Root.Main', array(
			HTMLEditorField::create('PayTagIntro')
				->setRows(6)
				->addExtraClass('stacked'),
			HTMLEditorField::create('PayTagInfoTip', 'Pay Tag [i] info tip')
				->setRows(6)
				->addExtraClass('stacked')
		));

		$fields->addFieldsToTab('Root.Airpoints', array(
			HTMLEditorField::create('AirpointsIntro')
				->setRows(6)
				->addExtraClass('stacked'),
			HTMLEditorField::create('AirpointsName')
				->setRows(6)
				->addExtraClass('stacked'),
			HTMLEditorField::create('AirpointsNumber')
				->setRows(6)
				->addExtraClass('stacked'),
			HTMLEditorField::create('AirpointsValidation')
				->setRows(6)
				->addExtraClass('stacked'),
			HTMLEditorField::create('AirPointsMemberDetailsInfoTip', 'Airpoints Member details [i] info tip')
				->setRows(6)
				->addExtraClass('stacked')
		));

		$fields->addFieldsToTab('Root.Account', array(
			HTMLEditorField::create('AccountInfo', 'Associated Accounts Info')
				->setRows(6)
				->addExtraClass('stacked'),
			HTMLEditorField::create('AccountEligibility')
				->setRows(6)
				->addExtraClass('stacked')
		));
*/
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