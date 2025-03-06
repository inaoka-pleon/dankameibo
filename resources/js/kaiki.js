/******/ (() => { // webpackBootstrap
    var __webpack_exports__ = {};
    /*!*******************************!*\
      !*** ./resources/js/kaiki.js ***!
      \*******************************/
    window.onload = function () {
      disp_kaiki_kikan();
    };
    $("#kaiki_kbn").change(function () {
      disp_kaiki_kikan();
    });
    function disp_kaiki_kikan() {
      if ($("#kaiki_kbn").val() === '2' || $("#kaiki_kbn").val() === '3') {
        // 初花または初盆の場合
        $("#kaiki_su").hide();
        $("#kaiki_kikan").show();
        $("#houyou_date").show();
      } else if ($("#kaiki_kbn").val() === '1') {
        // 百箇日の場合
        $("#kaiki_su").hide();
        $("#kaiki_kikan").hide();
        $("#houyou_date").hide();
      } else {
        // 年忌の場合
        $("#kaiki_su").show();
        $("#kaiki_kikan").hide();
        $("#houyou_date").hide();
      }
    }
    /******/ })()
    ;