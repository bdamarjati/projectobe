!(function($) {
  "use strict";

  var MainApp = function() {
    this.$btnFullScreen = $("#btn-fullscreen");
  };

  (MainApp.prototype.initNavbar = function() {
    $(".navbar-toggle").on("click", function(event) {
      $(this).toggleClass("open");
      $("#navigation").slideToggle(400);
    });

    $(".navigation-menu>li")
      .slice(-2)
      .addClass("last-elements");

    $('.navigation-menu li.has-submenu a[href="#"]').on("click", function(e) {
      if ($(window).width() < 992) {
        e.preventDefault();
        $(this)
          .parent("li")
          .toggleClass("open")
          .find(".submenu:first")
          .toggleClass("open");
      }
    });
  }),
    (MainApp.prototype.initSlimscroll = function() {
      $(".slimscroll").slimscroll({
        height: "auto",
        position: "right",
        size: "7px",
        color: "#9ea5ab",
        touchScrollStep: 50
      });
    }),
    (MainApp.prototype.initMenuItem = function() {
      $(".navigation-menu a").each(function() {
        var pageUrl = window.location.href.split(/[?#]/)[0];
        if (this.href == pageUrl) {
          $(this)
            .parent()
            .addClass("active"); // add active to li of the current link
          $(this)
            .parent()
            .parent()
            .parent()
            .addClass("active"); // add active class to an anchor
          $(this)
            .parent()
            .parent()
            .parent()
            .parent()
            .parent()
            .addClass("active"); // add active class to an anchor
        }
      });
    }),
    (MainApp.prototype.initComponents = function() {
      $('[data-toggle="tooltip"]').tooltip();
      $('[data-toggle="popover"]').popover();
    }),
    //full screen
    (MainApp.prototype.initFullScreen = function() {
      var $this = this;
      $this.$btnFullScreen.on("click", function(e) {
        e.preventDefault();

        if (
          !document.fullscreenElement &&
          /* alternative standard method */ !document.mozFullScreenElement &&
          !document.webkitFullscreenElement
        ) {
          // current working methods
          if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
          } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
          } else if (document.documentElement.webkitRequestFullscreen) {
            document.documentElement.webkitRequestFullscreen(
              Element.ALLOW_KEYBOARD_INPUT
            );
          }
        } else {
          if (document.cancelFullScreen) {
            document.cancelFullScreen();
          } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
          } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
          }
        }
      });
    }),
    (MainApp.prototype.init = function() {
      this.initNavbar();
      this.initSlimscroll();
      this.initMenuItem();
      this.initComponents();
      this.initFullScreen();
      Waves.init();
    }),
    //init
    ($.MainApp = new MainApp()),
    ($.MainApp.Constructor = MainApp);
})(window.jQuery),
  //initializing
  (function($) {
    "use strict";
    $.MainApp.init();

    function getUrlParameter(sParam) {
      var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split("&"),
        sParameterName,
        i;

      for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split("=");

        if (sParameterName[0] === sParam) {
          return sParameterName[1] === undefined
            ? false
            : decodeURIComponent(sParameterName[1]);
        }
      }

      return false;
    }

    // var mediaWidth = window.innerWidth > 0 ? window.innerWidth : screen.width;
    // var mediaWidth = Math.max(window.innerWidth, screen.width);
    var headerBg = $(".header-bg").first();
    // var titleBox = $(".page-title-box").first();
    var wrapper = $(".wrapper").first();
    // var breadcrumb = $(".breadcrumb").first();

    var hh = headerBg.outerHeight();
    // th = titleBox.outerHeight();
    // bh = breadcrumb.outerHeight();

    wrapper.css("padding-top", hh + 65 + "px");
    // if (mediaWidth <= 572) {
    // $(".chart-hide-xs")
    //   .find(".apexcharts-inner")
    //   .hide();
    // }

    var btnActC = $(".btn-activitas-create");
    btnActC.click(function(e) {
      var type = getUrlParameter("type");
      var tm = getUrlParameter("tm");

      if (type == false || tm == false) {
        var modal = new ModalRemote("#actCreateModal");
        var url = window.location.origin + "/aktivitas/landing-create";

        modal.doRemote(url, "GET", null);

        return false;
      }

      return true;
    });

    var btnKjm = $(".btn-kerjasama-create");
    btnKjm.click(function(e) {
      var jk = getUrlParameter("jk");

      if (jk == false) {
        var modal = new ModalRemote("#actCreateModal");
        var url = window.location.origin + "/import/form";

        modal.doRemote(url, "GET", null);

        return false;
      }

      return true;
    });

    var btnEarly = $(".btn-early");
    btnEarly.click(function() {
      var url = $(this).attr("href");

      if ($(this).hasClass("is-warning")) {
        Swal.fire({
          title: "Apakah anda yakin?",
          text: "Akan memproses data kerjasama ini.",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "OK!"
        }).then(function(result) {
          if (result.value) {
            window.open(url, "_self");
          }
        });
      }

      return false;
    });
  })(window.jQuery);
