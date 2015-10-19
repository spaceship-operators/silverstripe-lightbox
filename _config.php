<?php
ShortcodeParser::get('default')->register(
	'lightbox', array('LightboxShortCodeParser', 'parse_Lightbox')
);
HtmlEditorField_Toolbar::add_extension('HtmlEditorFieldLightboxExtension');

SiteTree::add_extension('SiteTreeLightboxExtension');