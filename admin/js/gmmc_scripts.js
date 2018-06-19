jQuery(document).ready(function(){
  /*var button = $("#gmm_btn_select_pdf_file");
  button.on('click', function(e){
    e.preventDefault();
    console.log("Estamos seleccionando un archivo");
  });*/

  var $marco;
  $btnMarco = $("#gmm_btn_select_pdf_file");
  $btnMarco.on('click', function(e){
    e.preventDefault();
    if($marco){
      $marco.open();
      return;
    }

    var $marco = wp.media({
      frame:'select',
      title:'Selecciona un archivo',
      button:{
        text:'Seleccionar archivo'
      },
      multiple:false,
      library:{
        order:'DESC',
        //type:'pdf'
      }
    });//end wp.media
    $marco.on('select', function(){
      var file = $marco.state().get('selection').toArray()[0].attributes;
      $("#pdf_url").val(file.url);
    });
    $marco.open();
  });//end clicl button
});
