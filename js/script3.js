
      document.attachEvent("onkeydown", win_onkeydown_handler);

      function win_onkeydown_handler() {
        switch (event.keyCode) {
          case 116:
            event.returnValue = !1;
            event.keyCode = 0;
            break;
          case 27:
            event.returnValue = !1, event.keyCode = 0
        }
      }