/**
* This is the main JS file for the Divine Star Options Form. 
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha 0
*/
function dropDownSearchClick(event,form) {
  event.preventDefault();
  var button = document.getElementById(form+"-dropdownsearch-dropdownbutton")
  button.getElementsByTagName("span")[0].classList.toggle("ds-image-rotate");;
  document.getElementById(form+"-dropdownsearch-dropdown").classList.toggle("show");
}

function filterFunction(form) {
  var input, filter, ul, li, a, i;
  input = document.getElementById(form+"-dropdownsearch-searchinput");
  filter = input.value.toUpperCase();
  div = document.getElementById(form+"-dropdownsearch-dropdown");
  a = div.getElementsByTagName("a");
  for (i = 0; i < a.length; i++) {
    txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "";
    } else {
      a[i].style.display = "none";
    }
  }
}

function clieckedDropDownSearchOption(event,form,callback) {

  console.log(form);
  var value = event.target.getAttribute('data-value');


  var orgvalue = value;
  if(value.length >= 16 ) {
    var split = value.split(" ");
    value = split[0] + " " + split[1] + ".....";
    if(value.length >= 16 ) {
      value = value.substring(0, 16);
    }
  }
  document.getElementById(form+'-dropdownsearch-currentselected').innerText = value;
  document.getElementById(form+'-dropdownsearch-searchinput').value = '';
  document.getElementById(form).value = orgvalue;
  div = document.getElementById(form+"-dropdownsearch-dropdown");
  a = div.getElementsByTagName("a");
  for (i = 0; i < a.length; i++) {
    a[i].style.display = "block";
  }

  callback(value,form);
} 

function dropDownSearchClearSelect(event,form) {
  event.preventDefault();
  document.getElementById(form+'-dropdownsearch-currentselected').innerText = '';
  document.getElementById(form+'-dropdownsearch-searchinput').value = '';
  document.getElementById(form).value = '';
  return;
  
}

function updateFontDisplay(value,form) {
  console.log(document.getElementById(form+'-font-display'));
  document.getElementById(form+'-font-display').style.fontFamily = value;

}
function defaultCallBack(value,form) {
  return true;
}

function updateDragAndDropValue(name) {
    var ul = document.getElementById(name+"-sortablelist");
    var li = ul.getElementsByTagName('li');

    var namevalue = new Array();
   for (i = 0; i < li.length; i++) {

      namevalue.push(li[i].getAttribute('data-value'));
   }
   namevalue.join(",");
   document.getElementById(name).value = namevalue;
}

docReady(function() {
//ds-options-sortablelist


   document.querySelectorAll('.ds-options-sortablelist').forEach(item => {

      new Sortable(item, {
      animation: 150,
      ghostClass: 'ds-options-sortablelist-ghost',
    onEnd: function (/**Event*/evt) {
    var itemEl = evt.item;  // dragged HTMLElement
    var name = itemEl.getAttribute('data-name');

    updateDragAndDropValue(name);

  },

 });

   });





//collapse-button
  var wp_collapse_button  = document.getElementById('collapse-button');
 

  var menu_button = document.querySelector("button[data-id=ds-options-menu-button]");
  var close_menu_button = document.querySelector("button[data-id=ds-options-close-menu-button]");
  var menu_bar = document.getElementById('ds-options-menu-js-id');


   if(wp_collapse_button.getAttribute('aria-expanded') == 'false' ) {
    addClass(menu_bar,'ds-sidebar-wp-collapsed');
  }


  wp_collapse_button.addEventListener("click", function() {

      if(hasClass(menu_bar,'ds-sidebar-wp-collapsed')) {
        addRemoveClass(menu_bar,'ds-sidebar-wp-open','ds-sidebar-wp-collapsed');
        return;
      }
      if(hasClass(menu_bar,'ds-sidebar-wp-open')) {
        addRemoveClass(menu_bar,'ds-sidebar-wp-collapsed','ds-sidebar-wp-open');
        return;
      }




  });



 
  menu_button.addEventListener("click", function() {
         addClass(menu_bar,' ds-menu-open ');
           removeClass(menu_bar,'ds-menu-folded');
           menu_button.style.display = "none";
           close_menu_button.style.display = "block";
  });
  
  close_menu_button.addEventListener("click", function() {
         removeClass(menu_bar,'ds-menu-open');
         addClass(menu_bar,' ds-menu-folded ');
           
           close_menu_button.style.display = "none";
           menu_button.style.display = "block";
  });


    function check_menu() {

      var width = window.innerWidth;
         if(width > 961) {
             if(!hasClass(menu_bar,'ds-menu-open')) {
           addClass(menu_bar,' ds-menu-open ');
           addClass(menu_bar,'ds-sidebar-wp-open');
           removeClass(menu_bar,'ds-menu-folded');
           removeClass(menu_bar,'ds-sidebar-wp-collapsed');
           menu_button.style.display = "none";
           close_menu_button.style.display = "none";
           }
         } else if(width < 961) {
             if(!hasClass(menu_bar,'ds-menu-folded')) {
             addClass(menu_bar,' ds-menu-folded ');
              addClass(menu_bar,'ds-sidebar-wp-collapsed');
             removeClass(menu_bar,'ds-menu-open');
             removeClass(menu_bar,'ds-sidebar-wp-open');
             menu_button.style.display = "block";
           close_menu_button.style.display = "none";
             }
         }

    }

    window.addEventListener("resize", function() {
    check_menu();
  });

    check_menu();

// ds-options-remove-single-image-button

  document.querySelectorAll('.ds-options-upload-single-image-button').forEach(item => {
  item.addEventListener('click', event => {
          var data_for = item.getAttribute('data-for')
         // var image_inputs = document.querySelectorAll(`input[data-for='${data_for}']`);
        
          event.preventDefault();
  
          open_wpmedia(false,data_for);
  })
});

  document.querySelectorAll('.ds-options-remove-single-image-button').forEach(item => {
  item.addEventListener('click', event => {
          event.preventDefault();
          var for_img = item.getAttribute('data-for');
          var img_put_id = for_img + "-image";
          document.getElementById(`${for_img}[id]`).value = '';
          document.getElementById(`${for_img}[orgsrc]`).value = '';
          document.getElementById(`${for_img}[size]`).value = '';
          document.getElementById(`${for_img}[src]`).value = '';
          document.getElementById(`${for_img}[alt]`).value = '';
          document.getElementById(`${for_img}[title]`).value = '';
          document.getElementById(`${for_img}[caption]`).value = '';
          document.getElementById(`${for_img}[description]`).value = '';
          document.getElementById(`${for_img}[orgwidth]`).value = '';
          document.getElementById(`${for_img}[orgheight]`).value = '';
          document.getElementById(img_put_id).src = '';
       
  })
})




function open_wpmedia(multiple,for_img) {

 var image_frame;
             if(image_frame){
                 image_frame.open();
             }
             // Define image_frame as wp.media object
             image_frame = wp.media({
                           title: 'Select Media',
                           multiple : multiple,
                           library : {
                                type : 'image',
                            }
                       });

                       image_frame.on('close',function() {
                          var selection =  image_frame.state().get('selection');
                          console.log(selection);
                          var gallery_ids = new Array();
                          var my_index = 0;
                          selection.each(function(attachment) {
                             console.log(attachment);
                             console.log(attachment['id']);
                             gallery_ids[my_index] = attachment['id'];
                             my_index++;
                          });
                          var ids = gallery_ids.join(",");
                          console.log(ids);
                          //jQuery('input#myprefix_image_id').val(ids);
                       
                          update_image(ids,for_img);

                          
                       });

                      image_frame.on('open',function() {
                        
        
                        var selection =  image_frame.state().get('selection');
                        console.log(for_img+"[id]");
                        var ids = document.getElementById(for_img+"[id]").value;
                        
                        if(ids.split(',').length > 1) {
                        ids = ids.split(',');
                        ids.forEach(function(id) {
                          var attachment = wp.media.attachment(id);
                          attachment.fetch();
                          selection.add( attachment ? [ attachment ] : [] );
                        });
                        } else {
                           var attachment = wp.media.attachment(ids);
                          attachment.fetch();
                          selection.add( attachment ? [ attachment ] : [] );

                        }



                      });

                    image_frame.open();

}





});


function update_image(imgid,for_img){

      var data = {
        action: 'divine_star_update_image_form',
        id: imgid
      };

      var xhr = new XMLHttpRequest();
      xhr.open('GET', `${ajaxurl}?action=divine_star_update_image_form&imgid=${imgid}&size=150 150`);
      xhr.send();
      xhr.onreadystatechange = function () {
    var done = 4; // readyState 4 means the request is done.
    var ok = 200; // status 200 is a successful return.
    if (xhr.readyState === done) {
      if (xhr.status === ok) {
        //console.log(xhr.responseText);
        var returndata = JSON.parse(xhr.responseText);
        console.log(returndata);
        console.log(returndata['data']['id']);
        var id = returndata['data']['id'];
        var orgsrc = returndata['data']['orgsrc'];
        var size = returndata['data']['size'];
        var src = returndata['data']['src'];
        var alt = returndata['data']['alt'];
        var title = returndata['data']['title'];
        var caption = returndata['data']['caption'];
        var description = returndata['data']['description'];
        var orgwidth = returndata['data']['orgwidth'];
        var orgheight = returndata['data']['orgheight'];


        var img_put_id = for_img + "-image";
        document.getElementById(`${for_img}[id]`).value = id;
        document.getElementById(`${for_img}[orgsrc]`).value = orgsrc;
        document.getElementById(`${for_img}[size]`).value = size;
        document.getElementById(`${for_img}[src]`).value = src;
        document.getElementById(`${for_img}[alt]`).value = alt;
        document.getElementById(`${for_img}[title]`).value = title;
        document.getElementById(`${for_img}[caption]`).value = caption;
        document.getElementById(`${for_img}[description]`).value = description;
        document.getElementById(`${for_img}[orgwidth]`).value = orgwidth;
        document.getElementById(`${for_img}[orgheight]`).value = orgheight;
        document.getElementById(img_put_id).src = src;
      } else {

    console.log('Error: ' + xhr.status); // An error occurred during the request.
  }
}
};

}

    


   jQuery(document).ready(function($) {

    $(".ds-options-menu-form").submit(function(event) {
      event.preventDefault();
      console.log("the form submited");
      var data = $(this).serializeArray(); 
      var going_to = $(this).attr('data-going-to');
      var formData = new FormData(document.querySelector('form.ds-options-menu-form'));


      /*
      console.log(formData.entries());
      $(this).find('input[type="checkbox"]').each(function(){
            if( !$(this).is(":checked")) {
              var name = $(this).attr('name');
              console.log(name);
              data.push({
                name:name,
                value: ''
              });
            }
      });
      var data = JSON.stringify(data);
      
      var send = {
        action: 'divine_star_updateoptions',
        going_to: going_to,
        theformdata: formData.entries(),
        data: data
      }
      */
      console.log('updating images!!!');
      formData.append("action", "divine_star_updateoptions");
      formData.append("going_to", going_to);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', ajaxurl);
      xhr.send(formData);
      xhr.onreadystatechange = function () {
      var done = 4; // readyState 4 means the request is done.
      var ok = 200; // status 200 is a successful return.
      if (xhr.readyState === done) {
      if (xhr.status === ok) {
      console.log('success!!!!!!');
      console.log(xhr.responseText);

      } else {
      console.log('Error: ' + xhr.status); // An error occurred during the request.
      }
      }


};






    });

       $(".ds-section-menu-option-button").click(function(event){
          event.preventDefault();


          var id = $(this).attr("data-id"); 
            console.log(id);
          if(id == 'ds-options-menu-button' || id == 'ds-options-close-menu-button' ) {
                console.log('pressed menu button!');
            return;
          }

          $(".ds-section-menu-option-button").each(function() {
            $(this).removeClass("active");
          });
          $(this).addClass('active');

           if($(this).hasClass("ds-section-menu-option-top-level")) { 
          if($(this).hasClass("ds-section-optoin-has-submenu")) {
    
            $(".ds-section-menu-option-button.ds-section-menu-option-top-level").each(function(event){
              $(this).removeClass('ds-option-section-expanded');
            }); 
            $(this).addClass('ds-option-section-expanded');
          } else {
            $(".ds-section-menu-option-button.ds-section-menu-option-top-level").each(function(event){
              $(this).removeClass('ds-option-section-expanded');
            }); 
          }
           }

          if($(this).hasClass("ds-section-menu-option-top-level")) {  
    
          $("form.ds-options-menu-form").each(function(){
            var fid = $(this).attr("id");
            var forid = $(this).attr("data-for");
            if(fid == id+'-form') {
            $("#"+forid+" .ds-subsection-menu-ul").css("display","block");  
            $(this).css("display","block");
            } else {
            $("#"+forid+" .ds-subsection-menu-ul").css("display","none"); 
            $(this).css("display","none");
            }

          });
          } else {
          $("form.ds-options-menu-form").each(function(){
            var fid = $(this).attr("id");
            var forid = $(this).attr("data-for");
            if(fid == id+'-form') {
            $(this).css("display","block");
            } else {
            $(this).css("display","none");
            }

          });


         }

       });

   });
