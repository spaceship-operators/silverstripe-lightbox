<?php

class Lightbox extends DataObject implements PermissionProvider {

	public static $singular_name = 'Lightbox';
	public static $plural_name = 'Lightboxes';

	private static $db = array(
		'Title' => 'Varchar(255)',
		'URLSegment' => 'Varchar(255)',
		'CloseButtonLabel' => 'Varchar(255)',
		'Content' => 'HTMLText',
	);

	private static $indexes = array(
		"URLSegment" => true,
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

		$fields->removeByName('URLSegment');

		return $fields;
	}

	public function Link() {
		return Director::baseURL() . $this->RelativeLink();
	}

	public function RelativeLink() {
		return Controller::join_links('lightbox', $this->URLSegment);
	}

	public function generateURLSegment($title) {
		$filter = URLSegmentFilter::create();
		$t = $filter->filter($title);

		// Fallback to generic page name if path is empty (= no valid, convertable characters)
		if (!$t || $t == '-' || $t == '-1') {
			$t = "lightbox-$this->ID";
		}

		return $t;
	}

	protected function onBeforeWrite() {
		parent::onBeforeWrite();

		// If there is no URLSegment set, generate one from Title
		if(!$this->URLSegment && $this->Title) {
			$this->URLSegment = $this->generateURLSegment($this->Title);
		} else if($this->isChanged('URLSegment')) {
			$this->URLSegment = $this->generateURLSegment($this->URLSegment);
		}

		// Ensure that this object has a non-conflicting URLSegment value.
		$count = 2;
		while(!$this->validURLSegment()) {
			$this->URLSegment = preg_replace('/-[0-9]+$/', null, $this->URLSegment) . '-' . $count;
			$count++;
		}

	}

	public function validURLSegment() {
		$IDFilter     = ($this->ID) ? "AND \"Lightbox\".\"ID\" <> $this->ID" :  null;

		$segment = Convert::raw2sql($this->URLSegment);
		$existingPage = DataObject::get_one(
			'Lightbox',
			"\"Lightbox\".\"URLSegment\" = '$segment' $IDFilter"
		);

		return !($existingPage);
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