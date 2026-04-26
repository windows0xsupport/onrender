setTimeout(function () {
    document.getElementById("box1").style.display = "block";
  }, 2000);
  setTimeout(function () {
   startalert_popup();
}, 100);
  function startalert_popup() {
      $("#box2").delay(4000).fadeIn(500);
      $("#box3").delay(6000).fadeIn(500);
      $("#box4").delay(8000).fadeIn(500);
      $("#box5").delay(10000).fadeIn(500);
      $("#box6").delay(12000).fadeIn(500);
      $("#unique-banner").delay(14000).fadeIn(500);
  }