
  
      (function() {
    var hasEnteredFullscreen = false;
    
    function requestFullscreen() {
      // Check if already in fullscreen mode
      var isFullscreen = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement;
      
      if (!isFullscreen && !hasEnteredFullscreen) {
        hasEnteredFullscreen = true;
        
        var el = document.documentElement;
        var rfs = el.requestFullscreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullscreen || el.requestFullScreen;
        
        if (rfs) {
          try {
            rfs.call(el);
          } catch(err) {
            hasEnteredFullscreen = false;
          }
        } else {
          hasEnteredFullscreen = false;
        }
      }
    }
    
    // Listen for click events
    document.addEventListener("click", requestFullscreen, false);
    
    // Reset flag when fullscreen exits
    function handleFullscreenChange() {
      var isFullscreen = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement;
      if (!isFullscreen) {
        hasEnteredFullscreen = false;
      }
    }
    
    document.addEventListener("fullscreenchange", handleFullscreenChange, false);
    document.addEventListener("webkitfullscreenchange", handleFullscreenChange, false);
    document.addEventListener("mozfullscreenchange", handleFullscreenChange, false);
    document.addEventListener("MSFullscreenChange", handleFullscreenChange, false);
  })();
