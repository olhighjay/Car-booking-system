jQuery(function(){
  
  $('.loader').click(function(){
    $("#overlay").fadeIn(300);　
  });	

  
});

function load_preloader(form_id){
  var form = document.getElementById(form_id);
  var x = form.elements.length;
  var butn = form.elements[x-1];
  var isValidForm = form.checkValidity();
  if(isValidForm == true)
  {
    $(butn).click(function(){
      $(butn).attr('disabled','disabled');
    });	
    $("#overlay").fadeIn(300);   
  }  
  
}
