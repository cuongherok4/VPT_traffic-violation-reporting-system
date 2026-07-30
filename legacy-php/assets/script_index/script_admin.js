// tải menu 
function loadMenu() {
    fetch('menu.php')// hàm này dung phương  thức fetch để gửi yêu cấu http đến menu. phpphp
        .then(response => response.text())
        .then(data => document.getElementById('menu-container').innerHTML = data);
}

function loadPage(page) {
    fetch(page)
    .then(response => response.text())
    .then(data => {
        document.getElementById('content').innerHTML = data;
      //  attachEventListeners(); // Gọi lại sự kiện sau khi tải trang
    });
}

//pop-up bao cao vi pham
function showForm() {
    document.getElementById("reportForm").classList.add("show");
}

function closeForm() {
    document.getElementById("reportForm").classList.remove("show");
}

window.onload = function() {
    loadMenu();
    loadPage('home.php');
};

// Đóng modal
function closeModal() {
    document.getElementById("modal3").style.display = "none";
}

// Phóng to ảnh khi click vào ảnh trong modal
document.addEventListener("DOMContentLoaded", function() {
    const images = document.querySelectorAll(".modal-image");

    images.forEach(img => {
        img.addEventListener("click", function() {
            this.classList.toggle("zoomed");
        });
    });
});



// Hàm tải danh sách báo cáo
function loadReports() {
    fetch('/BTL/frm/frm_admin/get_reports.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('reportList').innerHTML = html;
        })
        .catch(error => console.error('Lỗi tải danh sách:', error));
}

// Tải danh sách ban đầu và thiết lập interval
loadReports();
setInterval(loadReports, 5000); // Cập nhật mỗi 5 giây

// Xử lý hành động admin
function handleAdminAction(button, action) {
    const formData = new FormData(button.closest('form'));
    formData.append('action', action);

    fetch('/BTL/frm/frm_admin/home.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            loadReports(); // Tải lại danh sách sau khi xử lý
            alert('Thao tác thành công!');
        } else {
            alert('Có lỗi xảy ra khi xử lý');
        }
    })
    .catch(error => console.error('Error:', error));
}



