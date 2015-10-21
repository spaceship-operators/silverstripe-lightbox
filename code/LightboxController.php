<?php

/**
 * Created by PhpStorm.
 * User: cjoe
 * Date: 20/10/2015
 * Time: 5:29 PM
 */
class LightboxController extends Controller {

	private static $allowed_actions = array(
		'renderBox'
	);

	private static $url_handlers = array(
		'$URLSegment' => 'renderBox'
	);

	public function renderBox($request) {
		$url = $request->param('URLSegment');
		$id = (int) preg_replace('/lightbox\-/', '', $url);
		$lightbox = DataObject::get_by_id('Lightbox', $id);

		if ($lightbox) {
			return $lightbox->renderWith('Lightbox');
		}
		$this->httpError(404, ErrorPage::response_for(404));
	}
}