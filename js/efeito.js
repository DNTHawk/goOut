var data = new Date();

var hora = 19; //data.getHours();



$("#btn-night").on("click", function(){

  $("body").toggleClass("day");

});
$("#btn-night").on("click", function(){

  $("body").removeClass("night");
});


$("#btn-day").on("click", function(){

  $("body").removeClass("day");
});

$("#btn-day").on("click", function(){

  $("body").toggleClass("night");

});

$("#btn-bars").on("click", function(){

  $("header").toggleClass("open-menu");

});
$("#menu-mobile-mask").on("click", function(){

  $("header").removeClass("open-menu");
});

$("#btn-control").on("click", function(){

  $("header").toggleClass("open-filtro");

});
$("#filtro-mobile-mask").on("click", function(){

  $("header").removeClass("open-filtro");
});

$("#btn-control2").on("click", function(){

  $("filtro-mobile2").toggleClass("open-filtro2");

});
$("#filtro-mobile-mask2").on("click", function(){

  $("filtro-mobile2").removeClass("open-filtro2");
});

$("#btn-codigos").on("click", function(){

  $("#home").toggleClass("hidden");

});
$("#btn-codigos").on("click", function(){

  $("#codigos").removeClass("hidden");
});

$("#btn-voltar-cupon").on("click", function(){

  $("#codigos").toggleClass("hidden");

});
$("#btn-voltar-cupon").on("click", function(){

  $("#home").removeClass("hidden");
});

$("#btn-perfil").on("click", function(){

  $("#home").toggleClass("hidden");

});
$("#btn-perfil").on("click", function(){

  $("#perfil").removeClass("hidden");
});

$("#btn-voltar-perfil").on("click", function(){

  $("#perfil").toggleClass("hidden");

});
$("#btn-voltar-perfil").on("click", function(){

  $("#home").removeClass("hidden");
});

$("#lista1-close").on("click", function(){

  $("#lista1-close").toggleClass("hidden");

});
$("#lista1-close").on("click", function(){

  $("#lista1-open").removeClass("hidden");
});
$("#lista1-open").on("click", function(){

  $("#lista1-open").toggleClass("hidden");

});
$("#lista1-open").on("click", function(){

  $("#lista1-close").removeClass("hidden");
});

$("#lista2-close").on("click", function(){

  $("#lista2-close").toggleClass("hidden");

});
$("#lista2-close").on("click", function(){

  $("#lista2-open").removeClass("hidden");
});

$("#lista2-open").on("click", function(){

  $("#lista2-open").toggleClass("hidden");

});
$("#lista2-open").on("click", function(){

  $("#lista2-close").removeClass("hidden");
});

$("#lista3-close").on("click", function(){

  $("#lista3-close").toggleClass("hidden");

});
$("#lista3-close").on("click", function(){

  $("#lista3-open").removeClass("hidden");
});

$("#lista3-open").on("click", function(){

  $("#lista3-open").toggleClass("hidden");

});
$("#lista3-open").on("click", function(){

  $("#lista3-close").removeClass("hidden");
});

$("#lista4-close").on("click", function(){

  $("#lista4-close").toggleClass("hidden");

});
$("#lista4-close").on("click", function(){

  $("#lista4-open").removeClass("hidden");
});

$("#lista4-open").on("click", function(){

  $("#lista4-open").toggleClass("hidden");

});
$("#lista4-open").on("click", function(){

  $("#lista4-close").removeClass("hidden");
});

/*$("#btn-night").on("click", function(){

  $("body").removeClass("night");
});

$("#btn-night").on("click", function(){

  $("body").toggleClass("day");

});

$("#btn-day").on("click", function(){

  $("body").removeClass("day");
});

$("#btn-day").on("click", function(){

  $("body").toggleClass("night");

});*/

var inputRange = document.getElementsByClassName('range')[0],
        maxValue = 100, // the higher the smoother when dragging
        speed = 5,
        currValue, rafID;

      // set min/max value
      inputRange.min = 0;
      inputRange.max = maxValue;

      // listen for unlock
      function unlockStartHandler() {
          // clear raf if trying again
          window.cancelAnimationFrame(rafID);
          // set to desired value
          currValue = +this.value;
        }

        function unlockEndHandler() {

          // store current value
          currValue = +this.value;

    // determine if we have reached success or not
    if(currValue >= maxValue) {
      successHandler();
    }
    else {
      rafID = window.requestAnimationFrame(animateHandler);
    }
  }

// bind events
inputRange.addEventListener('mousedown', unlockStartHandler, false);
inputRange.addEventListener('mousestart', unlockStartHandler, false);
inputRange.addEventListener('mouseup', unlockEndHandler, false);
inputRange.addEventListener('touchend', unlockEndHandler, false);

// move gradient
inputRange.addEventListener('input', function() {
    //Change slide thumb color on way up
    if (this.value > 20) {
      inputRange.classList.add('ltpurple');
    }
    if (this.value > 40) {
      inputRange.classList.add('purple');
    }
    if (this.value > 60) {
      inputRange.classList.add('pink');
    }

    //Change slide thumb color on way down
    if (this.value < 20) {
      inputRange.classList.remove('ltpurple');
    }
    if (this.value < 40) {
      inputRange.classList.remove('purple');
    }
    if (this.value < 60) {
      inputRange.classList.remove('pink');
    }

    
  });

$(window).on('load', function(){
  document.getElementById("carregando").style.display = "none";
  document.getElementById("corpo").style.display = "block";
});