$(document).ready(function () {
    var mostPopularProductsCarousel = document.querySelector(
        "#mostPopularProductsCarousel"
      );
      if (window.matchMedia("(min-width: 768px)").matches) {
        new bootstrap.Carousel(mostPopularProductsCarousel, {
          interval: false,
        });
        var carouselWidth = $(".carousel-inner")[0].scrollWidth;
        var cardWidth = $(".carousel-item").width();
        var scrollPosition = 0;
        $("#mostPopularProductsCarousel .carousel-control-next").on("click", function () {
          if (scrollPosition < carouselWidth - cardWidth * 4) {
            scrollPosition += cardWidth * 2;
            $("#mostPopularProductsCarousel .carousel-inner").animate(
              { scrollLeft: scrollPosition },
              600
            );
          }
        });
        $("#mostPopularProductsCarousel .carousel-control-prev").on("click", function () {
          if (scrollPosition > 0) {
            scrollPosition -= cardWidth * 2;
            $("#mostPopularProductsCarousel .carousel-inner").animate(
              { scrollLeft: scrollPosition },
              600
            );
          }
        });
      } else {
        $(mostPopularProductsCarousel).addClass("slide");
      }

      
      var recentlyBoughtProductsCarousel = document.querySelector(
        "#recentlyBoughtProductsCarousel"
      );
      if (window.matchMedia("(min-width: 768px)").matches) {
        new bootstrap.Carousel(recentlyBoughtProductsCarousel, {
          interval: false,
        });
        var carouselWidth = $(".carousel-inner")[0].scrollWidth;
        var cardWidth = $(".carousel-item").width();
        var scrollPosition = 0;
        $("#recentlyBoughtProductsCarousel .carousel-control-next").on("click", function () {
          if (scrollPosition < carouselWidth - cardWidth * 4) {
            scrollPosition += cardWidth * 2;
            $("#recentlyBoughtProductsCarousel .carousel-inner").animate(
              { scrollLeft: scrollPosition },
              600
            );
          }
        });
        $("#recentlyBoughtProductsCarousel .carousel-control-prev").on("click", function () {
          if (scrollPosition > 0) {
            scrollPosition -= cardWidth * 2;
            $("#recentlyBoughtProductsCarousel .carousel-inner").animate(
              { scrollLeft: scrollPosition },
              600
            );
          }
        });
      } else {
        $(recentlyBoughtProductsCarousel).addClass("slide");
      }

      function selectSort(item) {
        const sortDropDown = document.getElementById('sortProductsDropDown')
        sortDropDown.innerHTML = item.innerHTML
      }      

});