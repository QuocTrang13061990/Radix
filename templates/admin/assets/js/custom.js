

// Sử dụng ckeditor với class
let classTextareas = document.querySelectorAll('.editor');
if (classTextareas !== null) {
    classTextareas.forEach(function (item, index) { // (item)=>{}: cũng được
        item.id = 'editor_' + index;
        CKEDITOR.replace(item.id);
    })
}
// Xử lý mở popup ckfinder
function openCkfinder() {
    chooseImages = document.querySelectorAll('.choose-image');
    // console.log(chooseImages);
    if (chooseImages !== null) {
        chooseImages.forEach(function (item) {
            item.addEventListener('click', function () {
                // lấy thẻ cha của item
                let parentElementObj = this.parentElement;
                while (parentElementObj) {
                    // lấy thẻ ông nội của item
                    parentElementObj = parentElementObj.parentElement;
                    // Nếu trong thẻ ông nội có class: ckfinder-group
                    if (parentElementObj.classList.contains('ckfinder-group')) {
                        break;
                    }
                }
                CKFinder.popup({
                    chooseFiles: true,
                    width: 800,
                    height: 600,
                    onInit: function (finder) {
                        finder.on('files:choose', function (evt) {
                            let fileUrl = evt.data.files.first().getUrl();
                            //Xử lý chèn link ảnh vào input: Khi click vào button => xuất hiện popup, chọn ảnh (click đôi) thì gán value của input bằng fileUrl (link ảnh)
                            parentElementObj.querySelector('.image-render').value = fileUrl;
                        });
                        finder.on('file:choose:resizedImage', function (evt) {
                            let fileUrl = evt.data.resizedUrl;
                            //Xử lý chèn link ảnh vào input
                        });
                    }
                });
            })
        })
    }
}
openCkfinder();
/* 
 * NOTE: 
 *  - Sự kiện bàn phím:
 *      - onkeyup: Khi user đang nhập từ bàn phím
 *      - onchange: Khi user nhập xong và click ra ngoài input
 */
// Chuyển title thành slug
function toSlug(title) {
    // Chuyển thành chữ thường
    let slug = title.toLowerCase();
    slug = slug.trim(); //Xoá khoảng trắng 2 đầu
    //lọc dấu
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    slug = slug.replace(/đ/gi, 'd');
    //chuyển dấu cách (khoảng trắng) thành gạch ngang
    slug = slug.replace(/ /gi, '-');
    //Xoá tất cả các ký tự đặc biệt
    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
    return slug;
}

let moduleTitle = document.querySelector('.module_title');
let moduleSlug = document.querySelector('.module_slug');
let renderLink = document.querySelector('.render-link');

if (renderLink !== null) {
    // lay slug
    slug = '';
    if (moduleSlug !== null) {
        slug = '/' + moduleSlug.value.trim();
    }
    renderLink.querySelector('span').innerHTML = `<a href="${rootUrl + slug}" target="_blank">${rootUrl + slug}</a>`;
}
if (moduleTitle !== null && moduleSlug !== null) {
    moduleTitle.addEventListener('keyup', (e) => {
        // Khi thay đổi title thì slug vẫn không đổi (Kỹ hơn: Khi không có sự change title thì không có sessionStorage: slug_change, lúc đó: chạy if bên dưới (slug ăn theo), còn title change thì không ăn theo nữa)
        if (!sessionStorage.getItem('slug_save')) {
            title = e.target.value; // lấy nội dung nhập trong input của field name
            if (title !== null) {
                moduleSlug.value = toSlug(title);// Tự động điền vào field slug
            }
        }
    })
    // Khi title change đổi (click ra ngoài input) thì lưu lại slug cũ (sử dụng sessionStorage)
    moduleTitle.addEventListener('change', (e) => {
        sessionStorage.setItem('slug_save', 1);
        // lấy link hiện tại của thẻ a: 
        let currentLink = rootUrl + '/' + PrefixUrl + '/' + moduleSlug.value.trim() + '.html';
        // Cập nhập lại nội dung của thẻ a: http://localhost/unicode/modules06/radix/dich-vu/dich-vu-13.html
        renderLink.querySelector('span a').innerHTML = currentLink;
        // Cập nhật lại href của thẻ a: http://localhost/unicode/modules06/radix/dich-vu/dich-vu-13.html
        renderLink.querySelector('span a').href = currentLink;
    })
    // Khi xóa slug và click ra ngoài (change) thì cập nhật lại slug theo title mới nhất
    moduleSlug.addEventListener('change', (e) => {
        let slugValue = e.target.value;
        if (slugValue.trim() == '') {
            sessionStorage.removeItem('slug_save');// Xóa cái này để khi viết thêm title thì slug tiếp tục cập nhật
            e.target.value = toSlug(moduleTitle.value); // ở đây không dùng lại biến slugValue nữa
        }
        // lấy link hiện tại của thẻ a: 
        let currentLink = rootUrl + '/' + PrefixUrl + '/' + moduleSlug.value.trim() + '.html';
        // Cập nhập lại nội dung của thẻ a: http://localhost/unicode/modules06/radix/dich-vu/dich-vu-13.html
        renderLink.querySelector('span a').innerHTML = currentLink;
        // Cập nhật lại href của thẻ a: http://localhost/unicode/modules06/radix/dich-vu/dich-vu-13.html
        renderLink.querySelector('span a').href = currentLink;
    })
    // Khi load lại Page và nhập title thì slug không ăn theo nữa => do chưa xóa sessionStorage (xử lý)
    if (moduleSlug.value.trim() == '') {
        sessionStorage.removeItem('slug_save');
    }
}
/* BÀI 172: BEGIN */
// Xử lý thêm dữ liệu dưới dạng Repeater (Bài 172)
const galleryItemHtml = `<div class="gallery-item">
<div class="row">
    <div class="col-11">
        <div class="row ckfinder-group">
            <div class="col-10">
                <input type="text" class="form-control image-render" name="gallery[]" placeholder="Đường dẫn ảnh..." value="">
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-success btn-block choose-image">Chọn ảnh</button>
            </div>
        </div>
    </div>
    <div class="col-1">
        <button type="button" class="remove btn btn-danger btn-block"><i class="fa fa-trash"></i></button>
    </div>
</div>
</div>`;

const galleryImageObject = document.querySelector('.gallery-image');
const galleryAddBtnObject = document.querySelector('.add-gallery');

if (galleryImageObject !== null && galleryAddBtnObject !== null) {
    galleryAddBtnObject.addEventListener('click', function () {
        /* 
         * Hướng 1 (không được): Sử dụng innerHTML, tuy nhiên: nội dung galleryItemHtml là cứng nên không lưu dữ liệu nhập vào khi add * * thêm ảnh mới 
         */
        // galleryImageObject.innerHTML += galleryItemHtml;

        /* 
         * Hướng 2 (OK): Sử dụng hàm appendChild để thêm chuỗi HTML galleryItemHtml (phải chuyển qua DOM NODE rồi mới thêm vào NODE cha) * vào galleryImageObject. 
         * Bước 1: Chuyển chuỗi HTML galleryItemHtml sang DOM NODE
         * Bước 2: thêm DOM NODE này vào DOM NODE galleryImageObject
         */
        const galleryItemHtmlNode = new DOMParser().parseFromString(galleryItemHtml, 'text/html').querySelector('.gallery-item');
        galleryImageObject.appendChild(galleryItemHtmlNode);
        openCkfinder();
    })
    /* Ở đây ta chỉ lấy được những element trước khi add (ví dụ như: gallery-item là không lấy được) */
    galleryImageObject.addEventListener('click', function(e){
        // Nếu thẻ đó có class là remove hoặc cha của nó có class là remove
        if(e.target.classList.contains('remove') || e.target.parentElement.classList.contains('remove')){
            if(confirm('Bạn có chắc chắn muốn xóa?')){
                let galleryItem = e.target;
                while (galleryItem) {
                    galleryItem = galleryItem.parentElement;
                    if (galleryItem.classList.contains('gallery-item')) {
                        break;
                    }
                }
                if(galleryItem !== null){
                    galleryItem.remove(); 
                }
            }
        }
    })
}
/* BÀI 172: END */