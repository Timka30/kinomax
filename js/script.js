// let input_search = document.querySelector('.input-search')
// let open_btn = document.querySelector('.search-btn')

// open_btn.addEventListener('click',()=>{
//     input_search.classList.toggle('visible')
// })



function openSearchModal() {
    document.getElementById("searchModal").style.display = "block";
    document.getElementById("menu-toggle").checked = false; // Сбрасываем бургер-меню
}

function closeSearchModal() {
    document.getElementById("searchModal").style.display = "none";
    document.getElementById("searchInput").value = ''; // Очистить поле ввода 
    document.getElementById("searchResults").innerHTML = ''; // Очистить результаты
}

function fetchResults() {
    const query = document.getElementById("searchInput").value;

    if (query.length < 1) {
        document.getElementById("searchResults").innerHTML = ''; // Очистить результаты, если пустой запрос
        return;
    }

    // Отправка AJAX-запроса на сервер
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `vendor/search.php?q=${encodeURIComponent(query)}`, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById("searchResults").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function selectResult(name) {
    document.getElementById("searchInput").value = name; // Установить выбранное имя в поле ввода 
    closeSearchModal(); // Закрыть модальное окно
}

function openSearchModal() {
    document.getElementById('searchModal').style.display = 'block';
    document.body.classList.add('modal-open'); // Добавляем класс для отключения скролла
}

function closeSearchModal() {
    document.getElementById('searchModal').style.display = 'none';
    document.body.classList.remove('modal-open'); // Убираем класс для восстановления скролла
}










// Объявляем переменную в глобальной области видимости
let isVisible = true;

document.addEventListener('DOMContentLoaded', function() {
    const podpiska = document.querySelector('.podpiska');
    const bezpodpiski = document.querySelector('.bez-podpiski');

    // Устанавливаем начальные классы
    if (podpiska && bezpodpiski) {
        bezpodpiski.classList.add('visible');
        podpiska.classList.add('hidden');
    }

    function toggleBlocks() {
        if (podpiska && bezpodpiski) {
            if (isVisible) {
                bezpodpiski.classList.remove('visible');
                bezpodpiski.classList.add('hidden');
                podpiska.classList.remove('hidden');
                podpiska.classList.add('visible');
            } else {
                podpiska.classList.remove('visible');
                podpiska.classList.add('hidden');
                bezpodpiski.classList.remove('hidden');
                bezpodpiski.classList.add('visible');
            }
            isVisible = !isVisible;
        }
    }

    function cycleBlocks() {
        if (podpiska && bezpodpiski) {
            toggleBlocks();
            setTimeout(cycleBlocks, 20000);
        }
    }

    // Запускаем цикл только если элементы существуют
    if (podpiska && bezpodpiski) {
        setTimeout(cycleBlocks, 5000);
    }
});


const gap = 16;
const carousels = document.querySelectorAll(".carousel");
const contentBlocks = document.querySelectorAll(".content");
const nextBtns = document.querySelectorAll(".next");
const prevBtns = document.querySelectorAll(".prev");

if (carousels.length > 0 && contentBlocks.length > 0 && nextBtns.length > 0 && prevBtns.length > 0) {
    carousels.forEach((carousel, index) => {
        let width = carousel.offsetWidth;
        let scrollPosition = 0;

        window.addEventListener("resize", () => {
            width = carousel.offsetWidth;
            toggleCarouselButtons(carousel, nextBtns[index], prevBtns[index], contentBlocks[index].scrollWidth, scrollPosition);
        });

        nextBtns[index].addEventListener("click", () => {
            scrollPosition += width + gap;
            carousel.scrollTo(scrollPosition, 0);
            toggleCarouselButtons(carousel, nextBtns[index], prevBtns[index], contentBlocks[index].scrollWidth, scrollPosition);
        });

        prevBtns[index].addEventListener("click", () => {
            scrollPosition -= width + gap;
            carousel.scrollTo(scrollPosition, 0);
            toggleCarouselButtons(carousel, nextBtns[index], prevBtns[index], contentBlocks[index].scrollWidth, scrollPosition);
        });

        toggleCarouselButtons(carousel, nextBtns[index], prevBtns[index], contentBlocks[index].scrollWidth, scrollPosition);
    });

    window.addEventListener("resize", () => {
        carousels.forEach((carousel, index) => {
            width = carousel.offsetWidth;
            toggleCarouselButtons(carousel, nextBtns[index], prevBtns[index], contentBlocks[index].scrollWidth, scrollPosition);
        });
    });
}

function toggleCarouselButtons(carousel, nextBtn, prevBtn, contentWidth, scrollPosition) {
    if (nextBtn && prevBtn) {
        nextBtn.style.display = scrollPosition + carousel.offsetWidth + gap < contentWidth ? "flex" : "none";
        prevBtn.style.display = scrollPosition > 0 ? "flex" : "none";
    }
}