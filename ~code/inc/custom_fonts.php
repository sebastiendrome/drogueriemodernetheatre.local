<?php
// arrays of custom fonts (custom_fonts for small font and header_fonts for headers)
/* see also ~code/custom.css.php, where conditionals load google fonts if any of the following are used */
$custom_fonts = array(
	
	/* monospace */
	'Inconsolata'=> array(
		'"Inconsolata", monospace',
		'https://fonts.googleapis.com/css?family=Inconsolata&display=swap&subset=latin-ext'
	),
	'Courier New'=> array(
		'"Courier New", "Courier", monospace',
		''
	),
	'Special Elite'=> array(
		'"Special Elite", monospace',
		'https://fonts.googleapis.com/css?family=Special+Elite&display=swap'
	),
	'Lucida Console'=> array(
		'"Lucida Console", "Lucida Sans Typewriter", Monaco, monospace',
		''
	),
	'Ubuntu Mono'=> array(
		'"Ubuntu Mono", monospace',
		'https://fonts.googleapis.com/css?family=Ubuntu+Mono&display=swap&subset=latin-ext'
	),
	'Space Mono'=> array(
		'"Space Mono", monospace', 
		'https://fonts.googleapis.com/css?family=Space+Mono&display=swap&subset=latin-ext'
	),
	'Lucida Sans Unicode'=> array(
		'"Lucida Sans Unicode", "Lucida Grande", sans-serif', 
		''
	),
	'Rubik'=> array(
		'"Rubik", sans-serif',
		'https://fonts.googleapis.com/css?family=Rubik&display=swap&subset=latin-ext'
	),

	/******  sans serif ******/
	'Alegreya Sans'=> array(
		'"Alegreya Sans", sans-serif',
		'https://fonts.googleapis.com/css?family=Alegreya+Sans&display=swap&subset=latin-ext'
	),
	'Open Sans'=> array(
		'"Open Sans", sans-serif',
		'https://fonts.googleapis.com/css?family=Open+Sans&display=swap&subset=latin-ext'
	),
	'PT Sans'=> array(
		'"PT Sans", sans-serif',
		'https://fonts.googleapis.com/css?family=PT+Sans&display=swap&subset=latin-ext'
	),
	'Tahoma'=> array(
		'"Tahoma", "Geneva", sans-serif',
		''
	),
	'Trebuchet MS'=> array(
		'"Trebuchet MS", "Helvetica", sans-serif',
		''
	),
	'Verdana'=> array(
		'"Verdana", "Geneva", sans-serif',
		''
	),
	'Arial'=> array(
		'"Arial", "Helvetica", sans-serif',
		''
	),
	'Arial Narrow'=> array(
		'"Arial Narrow", "Arial", sans-serif',
		''
	),
	'Ubuntu'=> array(
		'"Ubuntu", "Palatino", sans-serif',
		'https://fonts.googleapis.com/css?family=Ubuntu&display=swap&subset=latin-ext'
	),
	/* no support for bold */
	'Ubuntu Condensed'=> array(
		'"Ubuntu Condensed", "Arial Narrow", sans-serif',
		'https://fonts.googleapis.com/css?family=Ubuntu+Condensed&display=swap&subset=latin-ext'
	),
	'Helvetica Neue'=> array(
		'"Helvetica Neue", "Helvetica", "Arial", sans-serif',
		''
	),
	'Raleway'=> array(
		'"Raleway", sans-serif',
		'https://fonts.googleapis.com/css?family=Raleway&display=swap&subset=latin-ext'
	),
	'Proza Libre'=> array(
		'"Proza Libre", sans-serif',
		'https://fonts.googleapis.com/css?family=Proza+Libre&display=swap&subset=latin-ext'
	),
	'Karla'=> array(
		'"Karla", sans-serif',
		'https://fonts.googleapis.com/css?family=Karla&display=swap&subset=latin-ext'
	),

	/********** serif ********/
	'Spectral'=> array(
		'"Spectral", "Times", serif',
		'https://fonts.googleapis.com/css?family=Spectral&display=swap&subset=latin-ext'
	),
	'Times New Roman'=> array(
		'"Times New Roman", "Times", serif',
		''
	),
	'Georgia'=> array(
		'"Georgia", serif',
		''
	),
	'Palatino Linotype'=> array(
		'"Palatino Linotype", "Book Antiqua", "Palatino", serif',
		''
	),
	'EB Garamond'=> array(
		'"EB Garamond", serif',
		'https://fonts.googleapis.com/css?family=EB+Garamond&display=swap&subset=latin-ext'
	),
	'Old Standard TT'=> array(
		'"Old Standard TT", serif',
		'https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap&subset=latin-ext'
	)
);

$header_fonts = array(

	/****** monospace *******/
	'Space Mono'=> array(
		'"Space Mono", monospace',
		'https://fonts.googleapis.com/css?family=Space+Mono:700&display=swap&subset=latin-ext'
	), 
	'Courier New'=> array(
		'"Courier New", "Courier", monospace',
		''
	),
	'Special Elite'=> array(
		'"Special Elite", monospace',
		'https://fonts.googleapis.com/css?family=Special+Elite&display=swap'
	),
	'Cutive'=> array(
		'"Cutive", monospace',
		'https://fonts.googleapis.com/css?family=Cutive&display=swap&subset=latin-ext'
	),
	/* no support for bold */
	'Slabo 27px'=> array(
		'"Slabo 27px", serif',
		'https://fonts.googleapis.com/css?family=Slabo+27px&display=swap&subset=latin-ext'
	),

	/******** sans serif *******/
	'Oswald'=> array(
		'"Oswald", sans-serif',
		'https://fonts.googleapis.com/css?family=Oswald&display=swap&subset=latin-ext'
	),
	'Open Sans'=> array(
		'"Open Sans", sans-serif',
		'https://fonts.googleapis.com/css?family=Open+Sans:700&display=swap&subset=latin-ext'
	),
	'Archivo Narrow'=> array(
		'"Archivo Narrow", "Arial Narrow", sans-serif',
		'https://fonts.googleapis.com/css?family=Archivo+Narrow:700&display=swap'
	),
	'Ubuntu Condensed'=> array(
		'"Ubuntu Condensed", "Arial Narrow", sans-serif',
		'https://fonts.googleapis.com/css?family=Ubuntu+Condensed&display=swap&subset=latin-ext'
	),
	'Arial'=> array(
		'"Arial", "Helvetica", sans-serif',
		''
	),
	'Arial Narrow'=> array(
		'"Arial Narrow", "Arial", sans-serif',
		''
	),
	'Helvetica Neue'=> array(
		'"Helvetica Neue", "Helvetica", "Arial", sans-serif',
		''
	),

	/****** serif *******/
	'Spectral'=> array(
		'"Spectral", "Times", serif',
		'https://fonts.googleapis.com/css?family=Spectral:700&display=swap&subset=latin-ext'
	),
	'Cormorant Garamond'=> array(
		'"Cormorant Garamond", serif',
		'https://fonts.googleapis.com/css?family=Cormorant+Garamond:700&display=swap&subset=latin-ext'
	),
	'Trirong'=> array(
		'"Trirong", serif',
		'https://fonts.googleapis.com/css?family=Trirong:700&display=swap&subset=latin-ext'
	),
	'Times New Roman'=> array(
		'"Times New Roman", "Times", serif',
		''
	),
	'Georgia'=> array(
		'Georgia, serif',
		''
	),
	'Palatino Linotype'=> array('"Palatino Linotype", "Book Antiqua", "Palatino", serif',
		''
	),
	'EB Garamond'=> array(
		'"EB Garamond", serif',
		'https://fonts.googleapis.com/css?family=EB+Garamond:700&display=swap&subset=latin-ext'
	),
	'Old Standard TT'=> array(
		'"Old Standard TT", serif',
		'https://fonts.googleapis.com/css?family=Old+Standard+TT:700&display=swap&subset=latin-ext'
	),
	'Vollkorn'=> array(
		'"Vollkorn", serif',
		'https://fonts.googleapis.com/css?family=Vollkorn:700&display=swap&subset=latin-ext'
	),
	'Arvo'=> array(
		'"Arvo", serif',
		'https://fonts.googleapis.com/css?family=Arvo:700&display=swap'
	),
);
