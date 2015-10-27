<?php

/**
 * For handling the url route for lightbox content.
 */
class LightboxController extends Controller {

	private static $allowed_actions = array(
		'renderBox'
	);

	private static $url_handlers = array(
		'$URLSegment' => 'renderBox'
	);

	/**
	 * Tries to find the lightbox based on the id given.
	 *
	 * @param $request
	 * @return HTMLText
	 * @throws SS_HTTPResponse_Exception
	 */
	public function renderBox($request) {
		$url = $request->param('URLSegment');
		$id = (int) preg_replace('/lightbox\-/', '', $url);
		$lightbox = DataObject::get_by_id('Lightbox', $id);

		if ($lightbox) {
			return $lightbox->renderWith(array(get_class($lightbox), 'Lightbox'));
		}
		$this->httpError(404, ErrorPage::response_for(404));
	}
}