/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function handleLengthInZone() {
    var l = $("#enter_note textarea").val().length;
    var disp = 100 - l;
    
    $("#max_char_live").html(disp);
}

function handleLengthInZoneOnEvent(e) {
    var l = $(e.target).val().length;
    var disp = 100 - l;
    
    $("#max_char_live").html(disp);
}

function handleNoteProcess (e) {
    var l = $("#enter_note textarea").val().length;
    var m = $("#enter_note textarea").val();
            
    if ( l > 0 ) {
        $("#note_bd q").html(m);
    }
}


var m = function() {
    
   handleLengthInZone(); 
   $("#enter_note textarea").keyup(function(e){
       handleLengthInZoneOnEvent(e);
   });
   $("#enter_note textarea").keydown(function(e){
       handleLengthInZoneOnEvent(e);
   });
   $("#enter_note textarea").change(function(e){
       handleLengthInZoneOnEvent(e);
   });
   
   /** SEND **/
   $("#btn_note_send").click(function(e){
       handleNoteProcess(e);
   });
};

m();