<?php

/**
 * Base Lightbox class to extend from for any other Lightbox layouts that may be desired.
 */
class Lightbox extends DataObject implements PermissionProvider {

	public static $singular_name = 'Lightbox';
	public static $plural_name = 'Lightboxes';

	private static $db = array(
		'Title' => 'Varchar(255)',
		'CloseButtonLabel' => 'Varchar(255)',
		'Content' => 'HTMLText',
	);

	private static $summary_fields = array(
		'Title',
		'ClassName',
	);

	public static $searchable_fields = array(
		'Title',
		'ClassName',
	);

	private static $many_many = array(
		'SiteTrees' => 'SiteTree',
	);

	private static $defaults = array(
		'CloseButtonLabel' => 'Close',
	);

	private $relations = array();

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		// tell the javascript to disable lightbox options for this form
		$fields->add(new LiteralField('LightboxDisable', '<div class="lightbox-disable"></div>'));

		// check for relationship tabs
		$relationships = $this->getRelationships();
		if (!empty($relationships)) {
			foreach($relationships as $name => $type) {
				$itemsField = $fields->dataFieldByName($name);
				if ($itemsField) {
					$itemsFieldConfig = GridFieldConfig_RecordEditor::create();
					$itemsField->setConfig($itemsFieldConfig)->setTitle(false);

					// Remove buttons to manage the relationship, as this is done automatically
					$itemsFieldConfig->removeComponentsByType('GridFieldDeleteAction');
					$itemsFieldConfig->removeComponentsByType('GridFieldAddNewButton');
					$itemsFieldConfig->removeComponentsByType('GridFieldEditButton');
				}
			}
		}

		return $fields;
	}

	public function fieldLabels($includerelations = true) {
		$labels = parent::fieldLabels($includerelations);

		// ClassName is ignored in translation files by default
		$labels['ClassName'] = _t('Lightbox.db_ClassName', 'Type');

		return $labels;
	}

	public function Link() {
		return Director::baseURL() . $this->RelativeLink();
	}

	public function RelativeLink() {
		return Controller::join_links('lightbox', 'lightbox-'.$this->ID);
	}

	public function getRelationships() {
		if (empty($this->relations)) {
			$this->relations = array_unique(array_merge(
				($relations = Config::inst()->get($this->class, 'has_one')) ? $relations : array(),
				($relations = Config::inst()->get($this->class, 'has_many')) ? $relations : array(),
				($relations = Config::inst()->get($this->class, 'many_many')) ? $relations : array(),
				($relations = Config::inst()->get($this->class, 'belongs_many_many')) ? $relations : array(),
				($relations = Config::inst()->get($this->class, 'belongs_to')) ? $relations : array()
			));
		}
		return $this->relations;
	}
	public function canView($member = null) {
		return Permission::check('CMS_ACCESS_LightboxAdmin');
	}

	public function canEdit($member = null) {
		return Permission::check('CMS_ACCESS_LightboxAdmin');
	}

	public function canDelete($member = null) {

		$relationships = $this->getRelationships();
		$has_relations = false;

		if (!empty($relationships)) foreach ($relationships as $name => $type) {
			$relation = $this->{$name}();
			if ($relation && $relation->exists()) {
				$has_relations = true;
				break;
			}
		}

		return Permission::check('CMS_ACCESS_LightboxAdmin') && !$has_relations;
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