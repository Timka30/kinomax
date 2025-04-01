let input_search = document.querySelector('.input-search')
let open_btn = document.querySelector('.search-btn')

open_btn.addEventListener('click',()=>{
    input_search.classList.toggle('visible')
})

const gap = 16;
const carousels = document.querySelectorAll(".carousel");
const contentBlocks = document.querySelectorAll(".content");
const nextBtns = document.querySelectorAll(".next");
const prevBtns = document.querySelectorAll(".prev");

if (carousels.length > 0 && contentBlocks.length > 0 && nextBtns.length > 0 && prevBtns.length > 0) {
  carousels.forEach((carousel, index) => {
    let width = carousel.offsetWidth;
    let scrollPos = 0;

    window.addEventListener("resize", () => {
      width = carousel.offsetWidth;
      toggleCarouselButtons(carousel, nextBtns[index], prevBtns[index], contentBlocks[index].scrollWidth, scrollPos);
    });

    nextBtns[index].addEventListener("click", () => {
      scrollPos += width + gap;
      carousel.scrollTo(scrollPos, 0);
      toggleCarouselButtons(carousel, nextBtns[index], prevBtns[index], contentBlocks[index].scrollWidth, scrollPos);
    });

    prevBtns[index].addEventListener("click", () => {
      scrollPos -= width + gap;
      carousel.scrollTo(scrollPos, 0);
      toggleCarouselButtons(carousel, nextBtns[index], prevBtns[index], contentBlocks[index].scrollWidth, scrollPos);
    });
  });

  window.addEventListener("resize", () => {
    carousels.forEach((carousel, index) => {
      width = carousel.offsetWidth;
      toggleCarouselButtons(carousel, nextBtns[index], prevBtns[index], contentBlocks[index].scrollWidth, scrollPos);
    });
  });
}

function toggleCarouselButtons(carousel, nextBtn, prevBtn, contentWidth, scrollPos) {
  nextBtn.style.display = scrollPos + carousel.offsetWidth + gap < contentWidth ? "flex" : "none";
  prevBtn.style.display = scrollPos > 0 ? "flex" : "none";
}