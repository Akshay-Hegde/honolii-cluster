/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add('radio',function(a){return{title:a.lang.checkboxAndRadio.radioTitle,minWidth:350,minHeight:140,onShow:function(){var c=this;delete c.radioButton;var b=c.getParentEditor().getSelection().getSelectedElement();if(b&&b.getName()=='input'&&b.getAttribute('type')=='radio'){c.radioButton=b;c.setupContent(b);}},onOk:function(){var b,c=this.radioButton,d=!c;if(d){b=this.getParentEditor();c=b.document.createElement('input');c.setAttribute('type','radio');}if(d)b.insertElement(c);this.commitContent({element:c});},contents:[{id:'info',label:a.lang.checkboxAndRadio.radioTitle,title:a.lang.checkboxAndRadio.radioTitle,elements:[{id:'name',type:'text',label:a.lang.common.name,'default':'',accessKey:'N',setup:function(b){this.setValue(b.data('cke-saved-name')||b.getAttribute('name')||'');},commit:function(b){var c=b.element;if(this.getValue())c.data('cke-saved-name',this.getValue());else{c.data('cke-saved-name',false);c.removeAttribute('name');}}},{id:'value',type:'text',label:a.lang.checkboxAndRadio.value,'default':'',accessKey:'V',setup:function(b){this.setValue(b.getAttribute('value')||'');},commit:function(b){var c=b.element;if(this.getValue())c.setAttribute('value',this.getValue());else c.removeAttribute('value');}},{id:'checked',type:'checkbox',label:a.lang.checkboxAndRadio.selected,'default':'',accessKey:'S',value:'checked',setup:function(b){this.setValue(b.getAttribute('checked'));},commit:function(b){var c=b.element;if(!(CKEDITOR.env.ie||CKEDITOR.env.opera)){if(this.getValue())c.setAttribute('checked','checked');else c.removeAttribute('checked');}else{var d=c.getAttribute('checked'),e=!!this.getValue();if(d!=e){var f=CKEDITOR.dom.element.createFromHtml('<input type="radio"'+(e?' checked="checked"':'')+'></input>',a.document);c.copyAttributes(f,{type:1,checked:1});f.replace(c);a.getSelection().selectElement(f);b.element=f;}}}}]}]};});
