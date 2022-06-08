(function() {
    var select = document.getElementById('role');
    select.addEventListener('change', function(){
       var skill=document.getElementById('skill');
       console.log(select.options[select.selectedIndex].value);
       if(select.options[select.selectedIndex].value=="freelancer"){
           skill.disabled=false;
       }else{
           skill.disabled=true;
       }
    }, false);
 })();


