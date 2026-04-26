$(function() {
        var a = 0,
          b = setInterval(function() {
            a += 10;
            $("#dynamic").css("width", a + "%").attr("aria-valuenow", a).text(a + "% 完了");
            100 <= a && clearInterval(b)
          }, 100)
      });