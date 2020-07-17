function updateDragAndDropValue(name,id,content) {
    var ul = document.getElementById(id);
    var li = ul.getElementsByTagName('li');

    var namevalue = new Array();
    if(content){
      var contentids = new Array();
    }
   for (i = 0; i < li.length; i++) {

      namevalue.push(li[i].getAttribute('data-value'));


      if(content){
      contentids.push(li[i].getAttribute('data-cid'));
    }

   }
   namevalue.join(",");
  
   document.getElementById(name).value = namevalue;
  if(content){
    contentids.join(",");
   
   document.getElementById(name+'-cid').value = contentids;
    }
}


function closeListElement(for_id,id) {
  event.preventDefault();

  var elm = document.getElementById(for_id);

  var children = elm.children;
  for(var i = 0; i < children.length; i++) {
    if(children[i].getAttribute('data-id') == id) {
      children[i].remove();
      break;
    }
  }
  var form_input = elm.getAttribute('data-for');

  var flags = getDnDListAttributes(elm);
  if(flags['data-list-align'] != false ) {
    form_input += '-' + flags['data-list-align'];
  }


 updateSortableList(for_id);

}



function expandListItem(for_id,id) {
  event.preventDefault();
  var button = event.target;
  var icon = button.getElementsByTagName("span")[0];
  if(typeof icon !== 'undefined') {
    var icon = button.getElementsByTagName("span")[0];
  } else {
    var icon = button;
  }

  var elm = document.getElementById(for_id);

  var children = elm.children;
  for(var i = 0; i < children.length; i++) {
    if(children[i].getAttribute('data-id') == id) {


    var divs = children[i].children;
    for(var k = 0; k < divs.length; k++) {

      if(hasClass(divs[k],'ds-options-list-item-contracted')){
        addRemoveClass(divs[k],'ds-options-list-item-expanded','ds-options-list-item-contracted');
        addRemoveClass(icon,'ds-form-icon-arrow-up','ds-form-icon-arrow-down');
      
        break;
      }
      if(hasClass(divs[k],'ds-options-list-item-expanded')){
        addRemoveClass(divs[k],'ds-options-list-item-contracted','ds-options-list-item-expanded');
        addRemoveClass(icon,'ds-form-icon-arrow-down','ds-form-icon-arrow-up');
        break;
      }

    }


    }
  }

}





function getDnDListAttributes(element) {

var tags = [
    'data-for',
    'data-content',
    'data-remove',
    'data-expand',
    'data-dynamic-input',
    'data-swap',
    'data-multidrag',
    'data-put',
    'data-pull',
    'data-sort',
    'data-max',
    'data-min',
    'data-input',
    'data-list-align'
];
var return_tags = new Array();
tags.forEach(function(item,index){
  var tag = element.getAttribute(item);
  if(typeof tag !== undefined && tag != '' && tag != 'false') {
    return_tags[item] = tag;
  } else {
    return_tags[item] = false;
  }
});

return return_tags;
}
function insertBefore(newNode, existingNode) {
    existingNode.parentNode.insertBefore(newNode, existingNode);
}

function updateSortableList(id) {

  var elm = document.getElementById(id);
  var flags = getDnDListAttributes(elm);
  var children = elm.children;
  for(var i = 0; i < children.length; i++) {

    var child = children[i];

    if(child.getAttribute('data-id') != null ) {
    child.setAttribute('data-id', i);
    } else {
    var did = document.createAttribute('data-id');
    did.value = i;
    child.setAttributeNode(did);
    }
    




    var newhtml = getSortableIncludeHTML(i,flags);

    var divs = child.children;
    var content = '';
    for(var k = 0; k < divs.length; k++) {
    if(hasClass(divs[k],'ds-options-list-top-content')){
      updateSortableTopListItemContent(divs[k],i,id,flags);
    }
    if(hasClass(divs[k],'ds-options-list-bottom-content')){

     updateSortableListBottmContent(divs[k],i,flags);
      }
    }

 

  }

  var flags = getDnDListAttributes(elm);
  if(flags['data-input']){
  var form_input = elm.getAttribute('data-for');
  if(flags['data-list-align'] != false ) {
    form_input += '-' + flags['data-list-align'];
  }
  updateDragAndDropValue(form_input,id,flags['data-content']);
  }

}

function  updateSortableTopListItemContent(divs,num,id,flags) {
children = divs.getElementsByTagName('button');
var hasclosebtn = false;
    for(var k = 0; k < children.length; k++) {
      button = children[k];
      if(hasClass(button,'ds-form-expand-sortablelist-item-button')){
      button.setAttribute("onclick", `expandListItem('${id}','${num}')`);
      }
      if(hasClass(button,'ds-form-remove-sortablelist-item-button')){
      button.setAttribute("onclick", `closeListElement('${id}','${num}')`);
      hasclosebtn = true;
      }

    }


    if(flags['data-remove']) {
      if(!hasclosebtn){
        getSortableIncludeHTML(id,flags);
        div = divs.children;
        for (var i = 0; i < div.length; i++) {
             child = div[i];
            if(hasClass(child,'ds-form-sortablelist-button-container')) {
              //var oldhtml = child.innerHTML;
              child.innerHTML = '';
              //flags['data-expand'] = false;
              var newhtml = getSortableIncludeHTML(num,flags);
              child.innerHTML  = newhtml;
            }
        }


      }


    }

}

function updateSortableListBottmContent(div,num,flags) {

  if(flags['data-dynamic-input']) {
    inputs = div.getElementsByTagName('input');
    for(var k = 0; k < inputs.length; k++) {
      input = inputs[k];
      var group = input.getAttribute('data-group');
      var type = input.getAttribute('data-option-type');
      var dname = input.getAttribute('data-name');
      var form = input.getAttribute('data-for');
      var name = `${form}[optiongroup][${num}][${group}][${type}][${dname}]`;
      var value = input.value;
      input.name = name;
      input.id = name + "-id";
  
      input.removeAttribute('disabled');
    }

    selects = div.getElementsByTagName('select');
    for(var k = 0; k < selects.length; k++) {
      select = selects[k];
      var group = select.getAttribute('data-group');
      var type = select.getAttribute('data-option-type');
      var dname = select.getAttribute('data-name');
      var form = select.getAttribute('data-for');
      var name = `${form}[optiongroup][${num}][${group}][${type}][${dname}]`;
      var value = select.value;
      select.name = name;
      select.id = name + "-id";
      select.removeAttribute('disabled');
    }

    textareas = div.getElementsByTagName('textarea');
    for(var k = 0; k < textareas.length; k++) {
      textarea = textareas[k];
      var group = textarea.getAttribute('data-group');
      var type = textarea.getAttribute('data-option-type');
      var dname = textarea.getAttribute('data-name');
      var form = textarea.getAttribute('data-for');
      var name = `${form}[optiongroup][${num}][${group}][${type}][${dname}]`;
      var value = textarea.value;
      textarea.name = name;
      textarea.id = name + "-id";
      textarea.removeAttribute('disabled');
    }

  }



  return div.outerHTML;
}



function getSortableIncludeHTML(id,flags) {
var for_id = flags['data-for'];
var list_align = flags['data-list-align'];
if(list_align != false) {
   for_id += "-doublesortablelist-" + list_align;
} else {
  for_id += "-sortablelist";
}

var add_html = '';
if(flags['data-remove'] != false) {
add_html += `<button class='ds-form-remove-sortablelist-item-button' data-id="${id}" data-mode="" 
onclick="closeListElement('${for_id}','${id}')" 
data-for="${for_id}" class="">
<span class="ds-form-icon-close-mini"></span>
</button>`
}
if(flags['data-expand'] != false) {
add_html += `
<button class='ds-form-expand-sortablelist-item-button' data-id="0" data-mode="" 
onclick="expandListItem('${for_id}','${id}')" 
data-for="${for_id}" class="">
<span class="ds-form-icon-arrow-down"></span>
</button>
`
}

var html = `
<div id='testclose' class='ds-form-sortablelist-button-container'>
${add_html}
</div>
`;

return html;
}


function getSortableFunctions(tags,twin_tags) {
var data = {
    'swap': tags['data-swap'],
    'multidrag' : tags['data-multidrag'],
    'put' : tags['data-put'],
    'pull' : tags['data-pull'],
    'sort' : tags['data-sort'],
    'onAdd' : false,
    'onEnd' : false,
    'onClone' : false,
  };
  var hastwin = false;
  if(typeof twin_tags !== undefined) {
    hastwin = true;
  }

    
    var onAdd = function(evt) {

        var origEl = evt.item;
        var name = origEl.getAttribute('data-name');

        var id_add = "-sortablelist";
        var name_add = "";
        if(tags['data-list-align'] != false) {
        name_add = "-" + tags['data-list-align'];
        id_add = "-doublesortablelist-" + tags['data-list-align'];
        } 

        updateSortableList(name+id_add);
    }

    data['onAdd'] = onAdd;

    var onClone = function(evt) {

       

        var origEl = evt.item;
        var cloneEl = evt.clone;
        var name = origEl.getAttribute('data-name');

        var id_add = "-sortablelist";
        var name_add = "";
        if(tags['data-list-align'] != false) {
        name_add = "-" + tags['data-list-align'];
        id_add = "-doublesortablelist-" + tags['data-list-align'];
        } 
        //updateSortableList(name+id_add);

        updateSortableList(name+id_add);
        //updateDragAndDropValue(name+name_add,name+id_add,twin_tags['data-content']);
        return true;
    }

    data['onClone'] = onClone;



    if(tags['data-pull'] != false ) {

      var pull = function(to,from) {
          var return_this = tags['data-pull'];
          if(hastwin) {
            if(twin_tags['data-max'] != 'false') {
              if(to.el.children.length >= twin_tags['data-max']) {
                return_this = false;
              }

            }
          }
          return return_this;
          }  

          data['pull'] = pull;
      }

    if(tags['data-put'] != false) {

      var put = function (to) {

        var return_this = tags['data-put'];
       return return_this;
    }
       data['put'] = put;
    }

    if(tags['data-input'] != false) {
      var id_add = "-sortablelist";
      var name_add = "";
      if(tags['data-list-align'] != false) {
        name_add = "-" + tags['data-list-align'];
        id_add = "-doublesortablelist-" + tags['data-list-align'];
      } 

      var onEnd = function (evt) {
        var itemEl = evt.item;  // dragged HTMLElement
        var name = itemEl.getAttribute('data-name');
        updateSortableList(name+id_add);
        updateDragAndDropValue(name+name_add,name+id_add,tags['data-content']);
      }
      data['onEnd'] = onEnd;
    }




return data;
}


