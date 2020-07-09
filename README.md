# Divine Star Options

<p align="center">
  <img src="https://lukejohnson.media/wp-content/uploads/2020/07/divinestar_options_2.png" />
</p>

Currently under development. 
This will be a simple WordPress theme or plugins options framework.

# Installing
This process will change as the development goes along. But as of now all you need to do is download the code and put it in your theme's or plugin's folder. 

## Before You Begin
All the data for Divine Star Options is stored in XML and JSON files. XML files are used to create the form and the structure of the JSON while JSON just stores the name and values of the options. 
Create a blank XML and JSON file then make move the XML file into the _xml_ folder and the JSON file into the _data_ folder. 
Then make them writable. One method is through chmod command.  
```console
dsm:divinestaroptions Admin$ touch generaloptions.xml
dsm:divinestaroptions Admin$ touch generaloptions.json
dsm:divinestaroptions Admin$ mv generaloptions.xml xml
dsm:divinestaroptions Admin$ mv generaloptions.json data
dsm:divinestaroptions Admin$ chmod 777 xml/generaloptions.xml
dsm:divinestaroptions Admin$ chmod 777 data/generaloptions.json
```
## Setting Up In PHP
Include the files where you need them and look at the example set up below. 
```php
//Include the Divine Star Options files.
include('divinestaroptions/divinestaroptions.php');
include('divinestaroptions/divinestaroptionsform.php');

/*
*Define the option path. This is where the divinestaroptions folder is.
*This is used to update the files. 
*/
define( 'OPTIONS_PATH', plugin_dir_path( __FILE__ ).'src/options/divinestaroptions/' );
//Create the options and the form class. 
$dsbo = new DivineStarOptions();
$dsbof = new DivineStarOptionsForm();

/*
*Setting Up the JSON Storage
*This function will create the structure of the JSON file from the XML file. 
*Just supply it with the name of the XML file you made for the form. No need for the .XML extension. 
*/
$dsbo->load_into_files('generaloptions');

/*
*Getting an option. 
*First load the option set that you would like to pull from. 
*Then call get_option with the name of the option that you set. 
*/
$dsbo->load_options('generaloptions');
$value = $dsbo->get_option('test1');

/*
*Outputting the Options Form
*This function will echo out the form right here.
*Just supply it with the name of the XML file you made for the form. No need for the .XML extension. 
*/
$dsbof->get_options_form('generaloptions');


/*
*Integrating Updating With WordPress
*The options form uses javascript ajax calls to update the form. 
*Thus we need to add the update function to WordPress Ajax API. 
*The process is very simple. Just use this code and the method will be added. 
*Please do not change the action name. 
*/
add_action( 'wp_ajax_divine_star_updateoptions', array( $dsbo,'update_options') );
``` 



![Divine Star Options ](https://lukejohnson.media/wp-content/uploads/2020/07/divinestaroptions_image_1.png)
