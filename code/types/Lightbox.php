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

		$fields = $this->scaffoldFormFields(array(
			'tabbed' => true,
			'ajaxSafe' => true
		));

		// Tell the javascript to disable lightbox options for this form
		$fields->add(new LiteralField('LightboxDisable', '<div class="lightbox-disable"></div>'));

		// Add dependent objects which would prevent this object from being deleted
		if ($this->ID) {
			$relationships = $this->getDependents();
			if (!empty($relationships)) foreach ($relationships as $name) {

				// For has_one and belongs_to relations
				$result = $this->{$name}();

				if (!$result instanceof DataList) {
					$result = new ArrayList(array($result));
				}

				$fields->addFieldToTab(
					'Root.Dependents',
					GridField::create(
						$name,
						$name,
						$result
					)
				);
			}
		}

		$this->extend('updateCMSFields', $fields);

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

	/**
	 * The relations to the lightbox object that depend on the lightbox object existing, for example
	 * the site tree relationship.
	 *
	 * @return array List of relation names
	 */
	public function getDependents() {
		$dependents =  array(
			'SiteTrees'
		);

		$this->extend('udpateDependents', $dependents);
		return $dependents;
	}

	public function canView($member = null) {
		return Permission::check('CMS_ACCESS_LightboxAdmin');
	}

	public function canEdit($member = null) {
		return Permission::check('CMS_ACCESS_LightboxAdmin');
	}

	public function canDelete($member = null) {

		$relationships = $this->getDependents();
		$has_relations = false;

		if (!empty($relationships)) foreach ($relationships as $name) {
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