$(document).ready(function(){

  $("#jay-tableButton").click(function(){
        $("#jay-tableSlide").stop();
    $("#jay-tableSlide").slideToggle(1000);
    // $(this).val( $(this).val() === 'Hide' ? 'Show' : 'Hide' );
    if($(this).text() == "Show Available Vehicles"){
        $(this).text("Hide Available Vehicles");
      }else{
        //  $(this).stop();
        $(this).text("Show Available Vehicles");
      }
     
    });




// $(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#datatable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);

      // alert("The paragraph is now hidden");
    });
    // .$("#datatable tr" + value).css("background-color", "yellow")
    // $("#datatable tr" + value).css("background-color", "yellow")
    // alert("The paragraph is now hidden");
  });



  

  
});


// $(document).ready(function(){
//   $("#myInput").on("keyup", function () {
//  var searchexp = $(this).val();
//  $("table tr").hide();
//        var matchSearched = new RegExp(searchexp,"ig");

//        $("table").find("tr").each(function(){
//           var foundSomeMatch = false;
//           $(this).find("td").each(function(){
//               let textInside = $(this).text();
//               textInside = textInside.replace(matchSearched, function myFunction(x){
//                   foundSomeMatch = true;
//                   return '<span style="background-color: orange;">'+x+'</span>';
//               });
//               $(this).html(textInside);
//              if(foundSomeMatch){
//                  $(this).closest("tr").show();
//              }
//           });
//        });
//   });
// });
// var form = document.querySelector('.form');
// console.log(form.checkValidity());