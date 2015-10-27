<?php


/**
 * Adds Lightbox relationship/usage tracking.
 *
 * Used by SiteTree currently, but can easily extend other DataObject classes if required in the config.
 * This Extension helps keep the code more DRY, and allows for easily adding more classes that use Lightbox.
 */
class DataObjectLightboxExtension extends DataExtension {

	private static $belongs_many_many = array(
		'Lightboxes' => 'Lightbox'
	);

	protected $_tmp_box_tracking = array();

	private $parser = null;

	public function updateCMSFields(FieldList $fields) {
		// check for and remove Lightbox relationship tab
		$fields->removeByName('Lightboxes');
	}

	/**
	 * On save of the DataObject, synchronise the tracking.
	 */
	public function onAfterWrite() {
		$shortcodeFields = array();
		$fields = $this->owner->db();
		foreach($fields as $name => $type) {
			if(preg_match('/^(HTML|Shortcode)/', $type)) $shortcodeFields[] = $name;
		}

		$this->syncLinkTracking($this->owner, $shortcodeFields);
	}

	/**
	 * Callback to add lightboxes to the tracker, instead of the standard replace callback.
	 *
	 * @param $arguments
	 */
	public function parse_callback($arguments, $content = null, $parser, $tagName = null) {
		//ignore any invalid ids
		if (!empty($arguments['id'])) {
			$id = Convert::raw2sql($arguments['id']);

			if($id) $this->_tmp_box_tracking[] = $id;
		}
	}

	/**
	 * Adds or removes relationship links for the DataObject and Lightbox.
	 *
	 * @param $object DataObject
	 * @param $fields list of fields to look at for ShortCodes
	 */
	protected function syncLinkTracking($object, $fields) {
		$lightboxes = $object->Lightboxes();
		$oldBoxes = $lightboxes->toArray();
		$newBoxes = array();
		foreach($fields as $field) {
			$newBoxes = array_merge($newBoxes, $this->getBoxesUsed($object->$field));
		}

		$boxesAdd = array_diff($newBoxes, $oldBoxes);
		$boxesDel = array_diff($oldBoxes, $newBoxes);

		$lightboxes->addMany($boxesAdd);
		$lightboxes->removeMany($boxesDel);
	}

	/**
	 * Trigger the parser
	 *
	 * @param $html content
	 * @return array list of Lightbox objects
	 */
	protected function getBoxesUsed($html) {
		$parser = $this->getParser();

		$this->_tmp_box_tracking = array();
		$parser->parse($html);

		// get list of Lightboxes found to be used.
		if (!empty($this->_tmp_box_tracking)) {
			$boxes = DataObject::get(
				'Lightbox',
				sprintf('("ID" IN (%s))', implode(',', $this->_tmp_box_tracking))
			);
			if ($boxes->exists()) {
				return $boxes->toArray();
			}
		}
		return array();
	}

	/**
	 * Calls the parser, should only need it instantiated once.
	 *
	 * @return null|ShortcodeParser
	 */
	protected function getParser() {
		if (!$this->parser) {
			$parser = ShortcodeParser::get('LightboxTracking');
			$parser->register('lightbox', array($this, 'parse_callback'));
			$this->parser = $parser;
		}
		return $this->parser;
	}
}