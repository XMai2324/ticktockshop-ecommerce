//header
const header  = document.querySelector("header")
window.addEventListener("scroll", function(){
  x = window.pageYOffset  //cuộn dọc
  //console.log(x)
  if (x>0){
    header.classList.add("sticky")
  }
  else{
    header.classList.remove("sticky")
  }
})


// slider
  const imgPosition = document.querySelectorAll(".aspect-ratio-169 img")
  const imgContainer = document.querySelector(".aspect-ratio-169")
  const dotItem = document.querySelectorAll(".dot")
  let imgNum = imgPosition.length
  let index = 0
//   console.log(imgPosition)
  imgPosition.forEach(function(image, index){
    // console.log(image, index)
    image.style.left = index*100 + "%"
    dotItem[index].addEventListener("click", function(){
        slider (index)
    })
  })
  function imgSlide()
  {
    index++;
    if (index >= imgNum){
        index=0
    }
    slider(index)
  }

  function slider(index){
    imgContainer.style.left = "-" +index*100+ "%"
    const dotActive = document.querySelector(".active")
    dotActive.classList.remove("active")
    dotItem[index].classList.add("active")

  }
  setInterval(imgSlide, 5000);

  // Lịch sử tìm kiếm
  document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.querySelector('#searchForm');
    const searchInput = document.querySelector('#searchInput');
    const historyBox = document.querySelector('#searchHistory');
    const historyList = historyBox.querySelector('.search-history-list');

    if (!searchForm || !searchInput || !historyList) return;

    loadSearchHistory();

    // Khi submit form bằng Enter hoặc nút bấm
    searchForm.addEventListener('submit', function (e) {
        const keyword = searchInput.value.trim();
        if (keyword === '') {
            e.preventDefault(); // chặn gửi nếu trống
            return;
        }
        saveToHistory(keyword);
        // Không cần chặn submit nếu muốn chuyển trang
    });

    // Click item trong lịch sử tìm kiếm
    historyList.addEventListener('click', function (e) {
        if (e.target.tagName === 'A') {
            e.preventDefault();
            const keyword = e.target.textContent.trim();
            searchInput.value = keyword;
            searchForm.submit();
        }
    });

    function saveToHistory(keyword) {
        let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
        history = history.filter(item => item.toLowerCase() !== keyword.toLowerCase());
        history.unshift(keyword);
        if (history.length > 5) history = history.slice(0, 5);
        localStorage.setItem('searchHistory', JSON.stringify(history));
        loadSearchHistory();
    }

   function loadSearchHistory() {
    let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
    historyList.innerHTML = '';

    // Hiện chữ nếu rỗng
    if (history.length === 0) {
        const liEmpty = document.createElement('li');
        liEmpty.textContent = 'Không có lịch sử tìm kiếm';
        liEmpty.style.textAlign = 'center';
        liEmpty.style.fontSize = '14px';
        liEmpty.style.color = '#777';
        liEmpty.style.padding = '8px 0';
        historyList.appendChild(liEmpty);

       // Chỉ hiện lại nếu người dùng đang focus vào ô tìm kiếm
      if (document.activeElement === searchInput) {
          document.getElementById('searchHistory').classList.add('show');
      }
        return;
    }

    history.forEach(keyword => {
        const li = document.createElement('li');
        li.classList.add('item');

        const wrapper = document.createElement('div');
        wrapper.classList.add('history-item');

        const a = document.createElement('a');
        a.href = '#';
        a.classList.add('keyword-link');
        a.textContent = keyword;

        const del = document.createElement('span');
        del.classList.add('delete-history');
        del.innerHTML = '&times;';
        del.addEventListener('click', function (e) {
            e.stopPropagation();
            e.preventDefault();
            deleteFromHistory(keyword);
        });

        wrapper.appendChild(a);
        wrapper.appendChild(del);
        li.appendChild(wrapper);
        historyList.appendChild(li);
    });

    // Nút "Xóa tất cả"
    const liClear = document.createElement('li');
    liClear.classList.add('clear-history');
    liClear.textContent = 'Xóa lịch sử tìm kiếm';
    liClear.style.textAlign = 'center';
    liClear.style.fontSize = '14px';
    liClear.style.cursor = 'pointer';
    liClear.style.color = 'black';
    liClear.style.padding = '8px 0';
    liClear.addEventListener('click', function () {
        localStorage.removeItem('searchHistory');
        loadSearchHistory();
        if (document.activeElement === searchInput) {
    document.getElementById('searchHistory').classList.add('show');
}

    });
    historyList.appendChild(liClear);
}


function deleteFromHistory(keyword) {
    let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
    history = history.filter(item => item.toLowerCase() !== keyword.toLowerCase());
    localStorage.setItem('searchHistory', JSON.stringify(history));
    loadSearchHistory();
    // ✅ Giữ lại form sau khi xóa
    document.getElementById('searchHistory').classList.add('show');
}

    // Hiện lịch sử khi focus
    searchInput.addEventListener('focus', function () {
        historyBox.classList.add('show');
    });

    // Ẩn lịch sử khi click ra ngoài form tìm kiếm
    document.addEventListener('click', function (e) {
      const isInsideSearchArea = e.target.closest('#searchForm') || e.target.closest('#searchHistory');
      if (!isInsideSearchArea) {
          historyBox.classList.remove('show');
      }
    });
});







  



