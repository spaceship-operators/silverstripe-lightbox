<?php

/**
 * Parser for handling any lightbox links found, to replace with the correct url route
 */
class LightboxShortCodeParser {

	static function parse_Lightbox($arguments, $content = null, $parser, $tagName = null) {
		$lightbox = DataObject::get_by_id('Lightbox', $arguments['id']);

		if ($lightbox && $link = $lightbox->Link()) {
			return "$link";
		}
	}
}
