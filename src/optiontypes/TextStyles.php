<?php
/**
* Handles text syltes option types.
*
* Handles the text styles option such as font, size, colors, and drop shadow. 
* 
* @category   Options
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha .2
*/
class TextStyles extends Option
{
	
	



	public function get_value_structure($type,$args,$mode = null) : array
	{
		return array();
	}


	public function get_data_strcutre($type,$args,$mode = null) : array
	{
		if($type == 'font') {
			if($mode == 'font-family') {
				return  array(
					'value' => $args,
					'type' => $type 
				); 

			}


		}
	}


	public function get_html($type,$option,$value) : string
	{
		$mode = $option['mode'];
		
		if($type == 'font') {
			if($mode == 'font-family') {
				return $this->font_family_dropdown_search($option,$value);

			}


		}

		return $this->return_form_error('No known option type or mode for option type TextStyles.');
	}


	public function is_type($type) : bool
	{
	$types = array(
		'font'
	);

	  return array_search($type, $types ) !== false;
	}



	private function font_family_dropdown_search($option,$value) {
		$label = $option->label;
		$name = (string) $option->name;
		$description = $option->description;
		$id = $option->value . '-id';
		$did = $option->name . '-description';

		$fonts = $this->get_fonts();
		$form_name = "divinestaroptions[font][$name]";
		$font_data = '';
		foreach($fonts as $font_family => $ff) {
		  foreach ($ff as $key => $font) {
		  $font_data .= <<<HTML
<a tabindex="0" onclick='clieckedDropDownSearchOption("{$font}","{$form_name}",updateFontDisplay)' class='ds-dropdown-search-item' data-value='$font'>$font</a>
HTML;
		  }
		}

$url = OPTIONS_URL;


	$html = <<<HTML
<style type="text/css">
.ds-options-dropdownsearch-currentselected {
  color: black;
  width: 150px;
  font-size: 14px;
  border-radius: 5px;

  cursor: pointer;
}
.ds-options-dropdownsearch-currentselected span{
	padding: 15px;

}



.ds-options-dropdownsearch-clearselect {
  width: 15px;
  height: 22px;
}
.ds-options-dropdownsearch-clearselect:hover {
 cursor: pointer;
}
.ds-options-dropdownsearch-clearselect span.ds-close-image {
    padding: 2px 15px 14px 2px;

    box-sizing: border-box;
    background-image: url({$url}assets/images/formicons/close_mini.svg);
   background-position: 0px 6px;
    background-repeat: no-repeat;
    background-size: 10px 10px;
}


.ds-options-dropdownsearch-dropdownbutton {
    padding: 4px;
	border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
	height: 17px;
	width: 19px;
	
	background-image: linear-gradient( #0071a1, #ccefff);
}
.ds-options-dropdownsearch-dropdownbutton span.ds-arrowdown-image.ds-image-rotate {
	background-image: url({$url}assets/images/formicons/arrow_up.svg);
	transform: rotate(180deg);
	border-top-right-radius: 0px;
    border-bottom-right-radius: 0px;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
}
.ds-options-dropdownsearch-dropdownbutton:hover {
	background-color:  #016087;
	cursor: pointer;
}
.ds-options-dropdownsearch-dropdownbutton span.ds-arrowdown-image {
	padding: 2px 15px 14px 0px;
    margin-top: 5px;
    box-sizing: border-box;
	background-image: url({$url}assets/images/formicons/arrow_down.svg);
	background-position: 0px 4px;
    background-repeat: no-repeat;
    background-size: 15px 15px;

}
.ds-options-dropdownsearch-searchinput {
  box-sizing: border-box;
  background-image: url('{$url}assets/images/formicons/search.svg');
  background-position: 170px 5px;
  background-repeat: no-repeat;
  background-size: 20px 20px;
  font-size: 14px;
  padding: 14px 20px 12px 45px;
  border: none;
  width: 198px;
  border-bottom: 1px solid #ddd;
}

/*
.ds-options-dropdownsearch-searchinput:focus 
{outline: 3px solid #ddd;}
*/
.dropdown {
  width: 200px;
  height: 25px;
  border-radius: 5px;
  border: 1px solid grey;
  position: relative;
  display: inline-block;
}
.dropdown:hover, .dropdown:focus {
  background-color: #e6e6e6;

}
.dropdown-content {
  border-left: 1px solid grey;
  border-right: 1px solid grey;
  border-bottom: 1px solid grey;
  border-bottom-left-radius: 5px;
  border-bottom-right-radius: 5px;
  display: none;
  position: absolute;
  background-color: #f6f6f6;
  width: 200px;
  overflow: auto;
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 5px 16px;
  text-decoration: none;
  display: block;
}
.dropdown-content.show {
	display: block;
}
.search-list-container {
	height: 200px;
	overflow-y: scroll;
}
.ds-dropdown-items div {
	display: inline-block;
}

.ds-options-font-display {
	border: 1px solid grey;
	margin-top: 5px;
	margin-bottom: 5px;
	padding: 5px;
	width: 350px;
	height: 80px;
}

</style>
<script type="text/javascript">




</script>
<input type="hidden" value="" id="{$form_name}" name="{$form_name}"/>
<div tabindex="0" class="dropdown">
<div class='ds-dropdown-items dropbtn'>

	<div tabindex="0" class="ds-options-dropdownsearch-currentselected" onclick="dropDownSearchClick(event,'{$form_name}')">
	<span id='{$form_name}-dropdownsearch-currentselected'>Arial</span>
	</div>

	<div tabindex="0" id='{$form_name}-dropdownsearch-clearcurrentselected' class='ds-options-dropdownsearch-clearselect'>
	<span  onclick='dropDownSearchClearSelect(event,"{$form_name}")' class="ds-close-image"></span>
	</div>

	<div tabindex="0" id='{$form_name}-dropdownsearch-dropdownbutton' class='ds-options-dropdownsearch-dropdownbutton'>
	<span  onclick='dropDownSearchClick(event,"{$form_name}")' class="ds-arrowdown-image"></span>
	</div>

</div>
</div>
  <div id="{$form_name}-dropdownsearch-dropdown" class="dropdown-content">
    <input class='ds-options-dropdownsearch-searchinput' type="text" placeholder="Search.." id="{$form_name}-dropdownsearch-searchinput" onkeyup="filterFunction('{$form_name}')">
    <div class='search-list-container'>
    $font_data 
    </div>
  </div>
<div>
</div>
<br>
<div class='ds-options-font-display'>
<p style='font-family:"Arial" ' id='{$form_name}-font-display'>1 2 3 4 5 6 7 8 9 0 A B C D E F G H I J K L M N O P Q R S T U V W X Y Z a b c d e f g h i j k l m n o p q r s t u v w x y z</p>
</div>
HTML;

		return $this->get_form_wrap($html,$label,true,$id);
	}


/**
	* Get an array of fonts.
	*
	* Will return the built in HTML fonts and or a list of Google Fonts.
	*
	* List current as of 7-13-2020
	*
	* Where the list for the HTML fonts where found: 
	* @link https://www.tutorialbrain.com/css_tutorial/css_font_family_list/
	* Where the list for the Google fonts where found:
	* @link https://www.w3schools.com/howto/howto_google_fonts.asp
	*
	* @param bool $google Wether to return Google Fonts.
	* @return array An array of fonts. The key being the font family. 
	* The key for google fonts is 'Google Fonts'
	* @access private
	*
*/
private function get_fonts($google = false) : array {
	$fonts = array(
	'sans-serif' => array(
		'Arial',
		'Arial Black',
		'Arial narrow',
        'Arial Rounded MT Bold',
        'Helvetica',
        'Verdana',
        'Calibri',
        'Noto',
        'Lucida Sans',
        'Gill Sans',
		'Century Gothic',
		'Candara',
		'Futara',			
		'Franklin Gothic Medium',
		'Trebuchet MS'
	),
	'serif' => array(
		'Times New Roman',
		'Big Caslon',
		'Bodoni MT',
		'Book Antiqua',
		'Bookman',
		'New Century Schoolbook',
		'Calisto MT',
		'Cambria',
		'Didot',
		'Garamond',
		'Georgia',
		'Goudy Old Style',
		'Hoefler Text',
		'Lucida Bright',
		'Palatino',
		'Perpetua',	
		'Rockwell',
		'Rockwell Extra Bold',
		'Baskerville'
	),
	'monospace' => array(
		'Consolas',
		'Courier',
		'Courier New',
		'Lucida Console',
		'Lucidatypewriter',
		'Lucida Sans Typewriter',
		'Monaco',
		'Andale Mono'
	),
	'cursive' => array(
		'Comic Sans',	
		'Comic Sans MS',
		'Apple Chancery',
		'Zapf Chancery',
		'Bradley Hand',
		'Brush Script MT',
		'Brush Script Std',
		'Snell Roundhan',
		'URW Chancery',
		'Coronet script',
		'Florence',
		'Parkavenue'
	),
	'fantasy' => array(
		'Impact',
		'Brushstroke',
		'Luminari',
		'Chalkduster',
		'Jazz LET',
		'Blippo',
		'Stencil Std',
		'Marker Felt',
		'Trattatello',
		'Arnoldboecklin',
		'Oldtown',
		'Copperplate',
		'papyrus'
	)
	);
	$google_fonts = array(
		'ABeeZee',
		'Abel',
		'Abhaya Libre',
		'Abril Fatface',
		'Aclonica',
		'Acme',
		'Actor',
		'Adamina',
		'Advent Pro',
		'Aguafina Script',
		'Akronim',
		'Aladin',
		'Alata',
		'Alatsi',
		'Aldrich',
		'Alef',
		'Alegreya',
		'Alegreya SC',
		'Alegreya Sans',
		'Alegreya Sans SC',
		'Aleo',
		'Alex Brush',
		'Alfa Slab One',
		'Alice',
		'Alike',
		'Alike Angular',
		'Allan',
		'Allerta',
		'Allerta Stencil',
		'Allura',
		'Almarai',
		'Almendra',
		'Almendra Display',
		'Almendra SC',
		'Amarante',
		'Amaranth',
		'Amatic SC',
		'Amethysta',
		'Amiko',
		'Amiri',
		'Amita',
		'Anaheim',
		'Andada',
		'Andika',
		'Angkor',
		'Annie Use Your Telescope',
		'Anonymous Pro',
		'Antic',
		'Antic Didone',
		'Antic Slab',
		'Anton',
		'Arapey',
		'Arbutus',
		'Arbutus Slab',
		'Architects Daughter',
		'Archivo',
		'Archivo Black',
		'Archivo Narrow',
		'Aref Ruqaa',
		'Arima Madurai',
		'Arimo',
		'Arizonia',
		'Armata',
		'Arsenal',
		'Artifika',
		'Arvo',
		'Arya',
		'Asap',
		'Asap Condensed',
		'Asar',
		'Asset',
		'Assistant',
		'Astloch',
		'Asul',
		'Athiti',
		'Atma',
		'Atomic Age',
		'Aubrey',
		'Audiowide',
		'Autour One',
		'Average',
		'Average Sans',
		'Averia Gruesa Libre',
		'Averia Libre',
		'Averia Sans Libre',
		'Averia Serif Libre',
		'B612',
		'B612 Mono',
		'Bad Script',
		'Bahiana',
		'Bahianita',
		'Bai Jamjuree',
		'Baloo 2',
		'Baloo Bhai 2',
		'Baloo Bhaina 2',
		'Baloo Chettan 2',
		'Baloo Da 2',
		'Baloo Paaji 2',
		'Baloo Tamma 2',
		'Baloo Tammudu 2',
		'Baloo Thambi 2',
		'Balsamiq Sans',
		'Balthazar',
		'Bangers',
		'Barlow',
		'Barlow Condensed',
		'Barlow Semi Condensed',
		'Barriecito',
		'Barrio',
		'Basic',
		'Baskervville',
		'Battambang',
		'Baumans',
		'Bayon',
		'Be Vietnam',
		'Bebas Neue',
		'Belgrano',
		'Bellefair',
		'Belleza',
		'Bellota',
		'Bellota Text',
		'BenchNine',
		'Bentham',
		'Berkshire Swash',
		'Beth Ellen',
		'Bevan',
		'Big Shoulders Display',
		'Big Shoulders Text',
		'Bigelow Rules',
		'Bigshot One',
		'Bilbo',
		'Bilbo Swash Caps',
		'BioRhyme',
		'BioRhyme Expanded',
		'Biryani',
		'Bitter',
		'Black And White Picture',
		'Black Han Sans',
		'Black Ops One',
		'Blinker',
		'Bokor',
		'Bonbon',
		'Boogaloo',
		'Bowlby One',
		'Bowlby One SC',
		'Brawler',
		'Bree Serif',
		'Bubblegum Sans',
		'Bubbler One',
		'Buda',
		'Buenard',
		'Bungee',
		'Bungee Hairline',
		'Bungee Inline',
		'Bungee Outline',
		'Bungee Shade',
		'Butcherman',
		'Butterfly Kids',
		'Cabin',
		'Cabin Condensed',
		'Cabin Sketch',
		'Caesar Dressing',
		'Cagliostro',
		'Cairo',
		'Caladea',
		'Calistoga',
		'Calligraffitti',
		'Cambay',
		'Cambo',
		'Candal',
		'Cantarell',
		'Cantata One',
		'Cantora One',
		'Capriola',
		'Cardo',
		'Carme',
		'Carrois Gothic',
		'Carrois Gothic SC',
		'Carter One',
		'Catamaran',
		'Caudex',
		'Caveat',
		'Caveat Brush',
		'Cedarville Cursive',
		'Ceviche One',
		'Chakra Petch',
		'Changa',
		'Changa One',
		'Chango',
		'Charm',
		'Charmonman',
		'Chathura',
		'Chau Philomene One',
		'Chela One',
		'Chelsea Market',
		'Chenla',
		'Cherry Cream Soda',
		'Cherry Swash',
		'Chewy',
		'Chicle',
		'Chilanka',
		'Chivo',
		'Chonburi',
		'Cinzel',
		'Cinzel Decorative',
		'Clicker Script',
		'Coda',
		'Coda Caption',
		'Codystar',
		'Coiny',
		'Combo',
		'Comfortaa',
		'Comic Neue',
		'Coming Soon',
		'Concert One',
		'Condiment',
		'Content',
		'Contrail One',
		'Convergence',
		'Cookie',
		'Copse',
		'Corben',
		'Cormorant',
		'Cormorant Garamond',
		'Cormorant Infant',
		'Cormorant SC',
		'Cormorant Unicase',
		'Cormorant Upright',
		'Courgette',
		'Courier Prime',
		'Cousine',
		'Coustard',
		'Covered By Your Grace',
		'Crafty Girls',
		'Creepster',
		'Crete Round',
		'Crimson Pro',
		'Crimson Text',
		'Croissant One',
		'Crushed',
		'Cuprum',
		'Cute Font',
		'Cutive',
		'Cutive Mono',
		'DM Mono',
		'DM Sans',
		'DM Serif Display',
		'DM Serif Text',
		'Damion',
		'Dancing Script',
		'Dangrek',
		'Darker Grotesque',
		'David Libre',
		'Dawning of a New Day',
		'Days One',
		'Dekko',
		'Delius',
		'Delius Swash Caps',
		'Delius Unicase',
		'Della Respira',
		'Denk One',
		'Devonshire',
		'Dhurjati',
		'Didact Gothic',
		'Diplomata',
		'Diplomata SC',
		'Do Hyeon',
		'Dokdo',
		'Domine',
		'Donegal One',
		'Doppio One',
		'Dorsa',
		'Dosis',
		'Dr Sugiyama',
		'Duru Sans',
		'Dynalight',
		'EB Garamond',
		'Eagle Lake',
		'East Sea Dokdo',
		'Eater',
		'Economica',
		'Eczar',
		'El Messiri',
		'Electrolize',
		'Elsie',
		'Elsie Swash Caps',
		'Emblema One',
		'Emilys Candy',
		'Encode Sans',
		'Encode Sans Condensed',
		'Encode Sans Expanded',
		'Encode Sans Semi Condensed',
		'Encode Sans Semi Expanded',
		'Engagement',
		'Englebert',
		'Enriqueta',
		'Erica One',
		'Esteban',
		'Euphoria Script',
		'Ewert',
		'Exo',
		'Exo 2',
		'Expletus Sans',
		'Fahkwang',
		'Fanwood Text',
		'Farro',
		'Farsan',
		'Fascinate',
		'Fascinate Inline',
		'Faster One',
		'Fasthand',
		'Fauna One',
		'Faustina',
		'Federant',
		'Federo',
		'Felipa',
		'Fenix',
		'Finger Paint',
		'Fira Code',
		'Fira Mono',
		'Fira Sans',
		'Fira Sans Condensed',
		'Fira Sans Extra Condensed',
		'Fjalla One',
		'Fjord One',
		'Flamenco',
		'Flavors',
		'Fondamento',
		'Fontdiner Swanky',
		'Forum',
		'Francois One',
		'Frank Ruhl Libre',
		'Freckle Face',
		'Fredericka the Great',
		'Fredoka One',
		'Freehand',
		'Fresca',
		'Frijole',
		'Fruktur',
		'Fugaz One',
		'GFS Didot',
		'GFS Neohellenic',
		'Gabriela',
		'Gaegu',
		'Gafata',
		'Galada',
		'Galdeano',
		'Galindo',
		'Gamja Flower',
		'Gayathri',
		'Gelasio',
		'Gentium Basic',
		'Gentium Book Basic',
		'Geo',
		'Geostar',
		'Geostar Fill',
		'Germania One',
		'Gidugu',
		'Gilda Display',
		'Girassol',
		'Give You Glory',
		'Glass Antiqua',
		'Glegoo',
		'Gloria Hallelujah',
		'Goblin One',
		'Gochi Hand',
		'Gorditas',
		'Gothic A1',
		'Gotu',
		'Goudy Bookletter 1911',
		'Graduate',
		'Grand Hotel',
		'Gravitas One',
		'Great Vibes',
		'Grenze',
		'Grenze Gotisch',
		'Griffy',
		'Gruppo',
		'Gudea',
		'Gugi',
		'Gupter',
		'Gurajada',
		'Habibi',
		'Halant',
		'Hammersmith One',
		'Hanalei',
		'Hanalei Fill',
		'Handlee',
		'Hanuman',
		'Happy Monkey',
		'Harmattan',
		'Headland One',
		'Heebo',
		'Henny Penny',
		'Hepta Slab',
		'Herr Von Muellerhoff',
		'Hi Melody',
		'Hind',
		'Hind Guntur',
		'Hind Madurai',
		'Hind Siliguri',
		'Hind Vadodara',
		'Holtwood One SC',
		'Homemade Apple',
		'Homenaje',
		'IBM Plex Mono',
		'IBM Plex Sans',
		'IBM Plex Sans Condensed',
		'IBM Plex Serif',
		'IM Fell DW Pica',
		'IM Fell DW Pica SC',
		'IM Fell Double Pica',
		'IM Fell Double Pica SC',
		'IM Fell English',
		'IM Fell English SC',
		'IM Fell French Canon',
		'IM Fell French Canon SC',
		'IM Fell Great Primer',
		'IM Fell Great Primer SC',
		'Ibarra Real Nova',
		'Iceberg',
		'Iceland',
		'Imprima',
		'Inconsolata',
		'Inder',
		'Indie Flower',
		'Inika',
		'Inknut Antiqua',
		'Inria Sans',
		'Inria Serif',
		'Inter',
		'Irish Grover',
		'Istok Web',
		'Italiana',
		'Italianno',
		'Itim',
		'Jacques Francois',
		'Jacques Francois Shadow',
		'Jaldi',
		'Jim Nightshade',
		'Jockey One',
		'Jolly Lodger',
		'Jomhuria',
		'Jomolhari',
		'Josefin Sans',
		'Josefin Slab',
		'Jost',
		'Joti One',
		'Jua',
		'Judson',
		'Julee',
		'Julius Sans One',
		'Junge',
		'Jura',
		'Just Another Hand',
		'Just Me Again Down Here',
		'K2D',
		'Kadwa',
		'Kalam',
		'Kameron',
		'Kanit',
		'Kantumruy',
		'Karla',
		'Karma',
		'Katibeh',
		'Kaushan Script',
		'Kavivanar',
		'Kavoon',
		'Kdam Thmor',
		'Keania One',
		'Kelly Slab',
		'Kenia',
		'Khand',
		'Khmer',
		'Khula',
		'Kirang Haerang',
		'Kite One',
		'Knewave',
		'KoHo',
		'Kodchasan',
		'Kosugi',
		'Kosugi Maru',
		'Kotta One',
		'Koulen',
		'Kranky',
		'Kreon',
		'Kristi',
		'Krona One',
		'Krub',
		'Kulim Park',
		'Kumar One',
		'Kumar One Outline',
		'Kurale',
		'La Belle Aurore',
		'Lacquer',
		'Laila',
		'Lakki Reddy',
		'Lalezar',
		'Lancelot',
		'Lateef',
		'Lato',
		'League Script',
		'Leckerli One',
		'Ledger',
		'Lekton',
		'Lemon',
		'Lemonada',
		'Lexend Deca',
		'Lexend Exa',
		'Lexend Giga',
		'Lexend Mega',
		'Lexend Peta',
		'Lexend Tera',
		'Lexend Zetta',
		'Libre Barcode 128',
		'Libre Barcode 128 Text',
		'Libre Barcode 39',
		'Libre Barcode 39 Extended',
		'Libre Barcode 39 Extended Text',
		'Libre Barcode 39 Text',
		'Libre Baskerville',
		'Libre Caslon Display',
		'Libre Caslon Text',
		'Libre Franklin',
		'Life Savers',
		'Lilita One',
		'Lily Script One',
		'Limelight',
		'Linden Hill',
		'Literata',
		'Liu Jian Mao Cao',
		'Livvic',
		'Lobster',
		'Lobster Two',
		'Londrina Outline',
		'Londrina Shadow',
		'Londrina Sketch',
		'Londrina Solid',
		'Long Cang',
		'Lora',
		'Love Ya Like A Sister',
		'Loved by the King',
		'Lovers Quarrel',
		'Luckiest Guy',
		'Lusitana',
		'Lustria',
		'M PLUS 1p',
		'M PLUS Rounded 1c',
		'Ma Shan Zheng',
		'Macondo',
		'Macondo Swash Caps',
		'Mada',
		'Magra',
		'Maiden Orange',
		'Maitree',
		'Major Mono Display',
		'Mako',
		'Mali',
		'Mallanna',
		'Mandali',
		'Manjari',
		'Manrope',
		'Mansalva',
		'Manuale',
		'Marcellus',
		'Marcellus SC',
		'Marck Script',
		'Margarine',
		'Markazi Text',
		'Marko One',
		'Marmelad',
		'Martel',
		'Martel Sans',
		'Marvel',
		'Mate',
		'Mate SC',
		'Maven Pro',
		'McLaren',
		'Meddon',
		'MedievalSharp',
		'Medula One',
		'Meera Inimai',
		'Megrim',
		'Meie Script',
		'Merienda',
		'Merienda One',
		'Merriweather',
		'Merriweather Sans',
		'Metal',
		'Metal Mania',
		'Metamorphous',
		'Metrophobic',
		'Michroma',
		'Milonga',
		'Miltonian',
		'Miltonian Tattoo',
		'Mina',
		'Miniver',
		'Miriam Libre',
		'Mirza',
		'Miss Fajardose',
		'Mitr',
		'Modak',
		'Modern Antiqua',
		'Mogra',
		'Molengo',
		'Molle',
		'Monda',
		'Monofett',
		'Monoton',
		'Monsieur La Doulaise',
		'Montaga',
		'Montez',
		'Montserrat',
		'Montserrat Alternates',
		'Montserrat Subrayada',
		'Moul',
		'Moulpali',
		'Mountains of Christmas',
		'Mouse Memoirs',
		'Mr Bedfort',
		'Mr Dafoe',
		'Mr De Haviland',
		'Mrs Saint Delafield',
		'Mrs Sheppards',
		'Mukta',
		'Mukta Mahee',
		'Mukta Malar',
		'Mukta Vaani',
		'Muli',
		'MuseoModerno',
		'Mystery Quest',
		'NTR',
		'Nanum Brush Script',
		'Nanum Gothic',
		'Nanum Gothic Coding',
		'Nanum Myeongjo',
		'Nanum Pen Script',
		'Neucha',
		'Neuton',
		'New Rocker',
		'News Cycle',
		'Niconne',
		'Niramit',
		'Nixie One',
		'Nobile',
		'Nokora',
		'Norican',
		'Nosifer',
		'Notable',
		'Nothing You Could Do',
		'Noticia Text',
		'Noto Sans',
		'Noto Sans HK',
		'Noto Sans JP',
		'Noto Sans KR',
		'Noto Sans SC',
		'Noto Sans TC',
		'Noto Serif',
		'Noto Serif JP',
		'Noto Serif KR',
		'Noto Serif SC',
		'Noto Serif TC',
		'Nova Cut',
		'Nova Flat',
		'Nova Mono',
		'Nova Oval',
		'Nova Round',
		'Nova Script',
		'Nova Slim',
		'Nova Square',
		'Numans',
		'Nunito',
		'Nunito Sans',
		'Odibee Sans',
		'Odor Mean Chey',
		'Offside',
		'Old Standard TT',
		'Oldenburg',
		'Oleo Script',
		'Oleo Script Swash Caps',
		'Open Sans',
		'Open Sans Condensed',
		'Oranienbaum',
		'Orbitron',
		'Oregano',
		'Orienta',
		'Original Surfer',
		'Oswald',
		'Over the Rainbow',
		'Overlock',
		'Overlock SC',
		'Overpass',
		'Overpass Mono',
		'Ovo',
		'Oxanium',
		'Oxygen',
		'Oxygen Mono',
		'PT Mono',
		'PT Sans',
		'PT Sans Caption',
		'PT Sans Narrow',
		'PT Serif',
		'PT Serif Caption',
		'Pacifico',
		'Padauk',
		'Palanquin',
		'Palanquin Dark',
		'Pangolin',
		'Paprika',
		'Parisienne',
		'Passero One',
		'Passion One',
		'Pathway Gothic One',
		'Patrick Hand',
		'Patrick Hand SC',
		'Pattaya',
		'Patua One',
		'Pavanam',
		'Paytone One',
		'Peddana',
		'Peralta',
		'Permanent Marker',
		'Petit Formal Script',
		'Petrona',
		'Philosopher',
		'Piedra',
		'Pinyon Script',
		'Pirata One',
		'Plaster',
		'Play',
		'Playball',
		'Playfair Display',
		'Playfair Display SC',
		'Podkova',
		'Poiret One',
		'Poller One',
		'Poly',
		'Pompiere',
		'Pontano Sans',
		'Poor Story',
		'Poppins',
		'Port Lligat Sans',
		'Port Lligat Slab',
		'Pragati Narrow',
		'Prata',
		'Preahvihear',
		'Press Start 2P',
		'Pridi',
		'Princess Sofia',
		'Prociono',
		'Prompt',
		'Prosto One',
		'Proza Libre',
		'Public Sans',
		'Puritan',
		'Purple Purse',
		'Quando',
		'Quantico',
		'Quattrocento',
		'Quattrocento Sans',
		'Questrial',
		'Quicksand',
		'Quintessential',
		'Qwigley',
		'Racing Sans One',
		'Radley',
		'Rajdhani',
		'Rakkas',
		'Raleway',
		'Raleway Dots',
		'Ramabhadra',
		'Ramaraja',
		'Rambla',
		'Rammetto One',
		'Ranchers',
		'Rancho',
		'Ranga',
		'Rasa',
		'Rationale',
		'Ravi Prakash',
		'Red Hat Display',
		'Red Hat Text',
		'Redressed',
		'Reem Kufi',
		'Reenie Beanie',
		'Revalia',
		'Rhodium Libre',
		'Ribeye',
		'Ribeye Marrow',
		'Righteous',
		'Risque',
		'Roboto',
		'Roboto Condensed',
		'Roboto Mono',
		'Roboto Slab',
		'Rochester',
		'Rock Salt',
		'Rokkitt',
		'Romanesco',
		'Ropa Sans',
		'Rosario',
		'Rosarivo',
		'Rouge Script',
		'Rozha One',
		'Rubik',
		'Rubik Mono One',
		'Ruda',
		'Rufina',
		'Ruge Boogie',
		'Ruluko',
		'Rum Raisin',
		'Ruslan Display',
		'Russo One',
		'Ruthie',
		'Rye',
		'Sacramento',
		'Sahitya',
		'Sail',
		'Saira',
		'Saira Condensed',
		'Saira Extra Condensed',
		'Saira Semi Condensed',
		'Saira Stencil One',
		'Salsa',
		'Sanchez',
		'Sancreek',
		'Sansita',
		'Sarabun',
		'Sarala',
		'Sarina',
		'Sarpanch',
		'Satisfy',
		'Sawarabi Gothic',
		'Sawarabi Mincho',
		'Scada',
		'Scheherazade',
		'Schoolbell',
		'Scope One',
		'Seaweed Script',
		'Secular One',
		'Sedgwick Ave',
		'Sedgwick Ave Display',
		'Sen',
		'Sevillana',
		'Seymour One',
		'Shadows Into Light',
		'Shadows Into Light Two',
		'Shanti',
		'Share',
		'Share Tech',
		'Share Tech Mono',
		'Shojumaru',
		'Short Stack',
		'Shrikhand',
		'Siemreap',
		'Sigmar One',
		'Signika',
		'Signika Negative',
		'Simonetta',
		'Single Day',
		'Sintony',
		'Sirin Stencil',
		'Six Caps',
		'Skranji',
		'Slabo 13px',
		'Slabo 27px',
		'Slackey',
		'Smokum',
		'Smythe',
		'Sniglet',
		'Snippet',
		'Snowburst One',
		'Sofadi One',
		'Sofia',
		'Solway',
		'Song Myung',
		'Sonsie One',
		'Sorts Mill Goudy',
		'Source Code Pro',
		'Source Sans Pro',
		'Source Serif Pro',
		'Space Mono',
		'Spartan',
		'Special Elite',
		'Spectral',
		'Spectral SC',
		'Spicy Rice',
		'Spinnaker',
		'Spirax',
		'Squada One',
		'Sree Krushnadevaraya',
		'Sriracha',
		'Srisakdi',
		'Staatliches',
		'Stalemate',
		'Stalinist One',
		'Stardos Stencil',
		'Stint Ultra Condensed',
		'Stint Ultra Expanded',
		'Stoke',
		'Strait',
		'Stylish',
		'Sue Ellen Francisco',
		'Suez One',
		'Sulphur Point',
		'Sumana',
		'Sunflower',
		'Sunshiney',
		'Supermercado One',
		'Sura',
		'Suranna',
		'Suravaram',
		'Suwannaphum',
		'Swanky and Moo Moo',
		'Syncopate',
		'Tajawal',
		'Tangerine',
		'Taprom',
		'Tauri',
		'Taviraj',
		'Teko',
		'Telex',
		'Tenali Ramakrishna',
		'Tenor Sans',
		'Text Me One',
		'Thasadith',
		'The Girl Next Door',
		'Tienne',
		'Tillana',
		'Timmana',
		'Tinos',
		'Titan One',
		'Titillium Web',
		'Tomorrow',
		'Trade Winds',
		'Trirong',
		'Trocchi',
		'Trochut',
		'Trykker',
		'Tulpen One',
		'Turret Road',
		'Ubuntu',
		'Ubuntu Condensed',
		'Ubuntu Mono',
		'Ultra',
		'Uncial Antiqua',
		'Underdog',
		'Unica One',
		'UnifrakturCook',
		'UnifrakturMaguntia',
		'Unkempt',
		'Unlock',
		'Unna',
		'VT323',
		'Vampiro One',
		'Varela',
		'Varela Round',
		'Vast Shadow',
		'Vesper Libre',
		'Viaoda Libre',
		'Vibes',
		'Vibur',
		'Vidaloka',
		'Viga',
		'Voces',
		'Volkhov',
		'Vollkorn',
		'Vollkorn SC',
		'Voltaire',
		'Waiting for the Sunrise',
		'Wallpoet',
		'Walter Turncoat',
		'Warnes',
		'Wellfleet',
		'Wendy One',
		'Wire One',
		'Work Sans',
		'Yanone Kaffeesatz',
		'Yantramanav',
		'Yatra One',
		'Yellowtail',
		'Yeon Sung',
		'Yeseva One',
		'Yesteryear',
		'Yrsa',
		'ZCOOL KuaiLe',
		'ZCOOL QingKe HuangYou',
		'ZCOOL XiaoWei',
		'Zeyada',
		'Zhi Mang Xing',
		'Zilla Slab',
		'Zilla Slab Highlight'
	);
	if($google) {
		$fonts['Google Fonts'] = $google_fonts;
	}
	
	return $fonts;


	}
}