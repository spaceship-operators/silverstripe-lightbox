<?php

class CustomLightbox extends Lightbox {

	public static $singular_name = 'Custom Lightbox';
	public static $plural_name = 'Custom Lightboxes';

	private static $db = array(
		'Content2' => 'HTMLText',
		'Button2Text' => 'Varchar(255)',
		'Button2Link' => 'Varchar(255)',
	);
}