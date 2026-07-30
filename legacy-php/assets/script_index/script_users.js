// tải menu 
function loadMenu() {
    fetch('menu.html') // để tải nội dung file menu.html 
        .then(response => response.text())// chèn nội dung thành dạng văn bản .text()
        .then(data => document.getElementById('menu-container').innerHTML = data); // chèn nội dung vào phần tử có id 
}

function loadPage(page) { // tải nội dung trang động 
    fetch(page)
    .then(response => response.text())
    .then(data => {
        document.getElementById('content').innerHTML = data;//lấy dữ liệu trang và chèn vào phần tử có id là content 
        attachEventListeners(); // Gọi lại sự kiện sau khi tải trang
    });
}
// nut gui bao cao ngay
function attachEventListeners() {
    const reportBtn = document.querySelector(".btnGuiBaoCao");
    if (reportBtn) {
        reportBtn.addEventListener("click", showForm);
    }
}
//pop-up bao cao vi pham
function showForm() {
    const form = document.getElementById('reportForm');
    const overlay = document.createElement('div');
    overlay.className = 'overlay';
    document.body.appendChild(overlay);
    form.classList.add('show');
    
    overlay.addEventListener('click', closeForm);
}
// dong form bao cao vi pham 
function closeForm() {
    const form = document.getElementById('reportForm');
    const overlay = document.querySelector('.overlay');
    form.classList.remove('show');
    if (overlay) overlay.remove();
}
window.onload = function() {
    loadMenu();
    loadPage('home.html');
};
//xử lý tiếng anh và tiếng việt
function changeLanguage(lang) {
    if (lang === 'vi') {
        alert("Đã chọn Tiếng Việt 🇻🇳");
    } else if (lang === 'en') {
        alert("English selected 🇺🇸");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    fetchViolations(""); // Hiển thị tất cả vi phạm khi mở trang
});

function searchLicensePlate(event) {
    if (event.keyCode !== 13) return; // Chỉ tìm kiếm khi nhấn Enter
    let licensePlate = document.getElementById("searchInput").value.trim();
    fetchViolations(licensePlate);
}

function fetchViolations(licensePlate) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "timKiem.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let responseHTML = xhr.responseText;
            let parser = new DOMParser();
            let newDocument = parser.parseFromString(responseHTML, "text/html");
            let newResultsContainer = newDocument.getElementById("resultsContainer");
            document.getElementById("resultsContainer").innerHTML = newResultsContainer.innerHTML;

            // Nếu có kết quả tìm kiếm, mở pop-up và ẩn danh sách cũ
            let foundItem = document.querySelector("#resultsContainer .news-item");
            if (foundItem) {
                let foundModalId = foundItem.getAttribute("onclick").match(/'([^']+)'/)[1];
                openModal(foundModalId);
                document.getElementById("newsContainer").style.display = "none"; // Ẩn danh sách vi phạm
            }
        }
    };
    xhr.send("bien_so_xe=" + encodeURIComponent(licensePlate));
}

// tin tuc


function openModal(id){
    document.getElementById(id).style.display = "block";
}

function closeModal(id){
    document.getElementById(id).style.display = "none";
    if(id === "searchResultModal"){
        document.getElementById("newsContainer").style.display = "block";
    }
}



