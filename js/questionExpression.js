(function() {
    var mathElements = [
      'math',
      'maction',
      'maligngroup',
      'malignmark',
      'menclose',
      'merror',
      'mfenced',
      'mfrac',
      'mglyph',
      'mi',
      'mlabeledtr',
      'mlongdiv',
      'mmultiscripts',
      'mn',
      'mo',
      'mover',
      'mpadded',
      'mphantom',
      'mroot',
      'mrow',
      'ms',
      'mscarries',
      'mscarry',
      'msgroup',
      'msline',
      'mspace',
      'msqrt',
      'msrow',
      'mstack',
      'mstyle',
      'msub',
      'msup',
      'msubsup',
      'mtable',
      'mtd',
      'mtext',
      'mtr',
      'munder',
      'munderover',
      'semantics',
      'annotation',
      'annotation-xml'
    ];
  
    
    CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://ckeditor.com/docs/ckeditor4/4.16.0/examples/assets/plugins/ckeditor_wiris/', 'plugin.js');
    
    var array = document.getElementsByClassName('ckeditor');
    for(i = 0; i < array.length; i++){
        CKEDITOR.replace(array[i].name, {
            extraPlugins: 'ckeditor_wiris',
            // For now, MathType is incompatible with CKEditor file upload plugins.
            removePlugins: 'uploadimage,uploadwidget,uploadfile,filetools,filebrowser',
            height: 320,
            // Update the ACF configuration with MathML syntax.
            extraAllowedContent: mathElements.join(' ') + '(*)[*]{*};img[data-mathml,data-custom-editor,role](Wirisformula)'
          });
    }

    
  }());

  