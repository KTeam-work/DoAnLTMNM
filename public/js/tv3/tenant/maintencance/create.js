(function () {
  var contractSelect = document.getElementById('contractSelect');
  var roomName = document.getElementById('roomName');

  function syncRoom() {
    if (!contractSelect || !roomName) return;
    var opt = contractSelect.options[contractSelect.selectedIndex];
    roomName.textContent = opt ? (opt.getAttribute('data-room') || '—') : '—';
  }

  if (contractSelect) {
    contractSelect.addEventListener('change', syncRoom);
    syncRoom();
  }

  var zone = document.getElementById('uploadZone');
  var input = document.getElementById('imageInput');
  var hint = document.getElementById('uploadHint');
  var previewContainer = document.getElementById('imagePreviewContainer');

  if (!zone || !input) return;

  // Sử dụng DataTransfer để lưu trữ danh sách file đã chọn
  var dt = new DataTransfer();

  function updatePreview() {
    input.files = dt.files;

    if (!previewContainer) return;

    previewContainer.innerHTML = '';

    if (dt.files.length === 0) {
      previewContainer.style.setProperty('display', 'none', 'important');
      if (hint) {
        hint.textContent = 'Hỗ trợ định dạng: JPG, PNG, WEBP (Tối đa 10MB/ảnh, có thể tải lên nhiều ảnh)';
      }
      return;
    }

    previewContainer.style.setProperty('display', 'flex', 'important');
    if (hint) {
      hint.textContent = 'Đã chọn ' + dt.files.length + ' ảnh. Nhấn hoặc kéo thêm ảnh vào khung để bổ sung.';
    }

    Array.from(dt.files).forEach(function (file, index) {
      if (!file.type.startsWith('image/')) return;

      var col = document.createElement('div');
      col.style.cssText = 'position: relative; width: 100px; height: 100px; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; background: #f8fafc; box-shadow: 0 1px 3px rgba(0,0,0,0.05);';

      var img = document.createElement('img');
      img.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';
      img.src = URL.createObjectURL(file);
      img.onload = function () { URL.revokeObjectURL(img.src); };

      var removeBtn = document.createElement('button');
      removeBtn.type = 'button';
      removeBtn.innerHTML = '&times;';
      removeBtn.title = 'Xóa ảnh này';
      removeBtn.style.cssText = 'position: absolute; top: 4px; right: 4px; width: 22px; height: 22px; border-radius: 50%; background: rgba(0,0,0,0.65); color: #fff; border: none; font-size: 16px; line-height: 1; display: flex; align-items: center; justify-content: center; cursor: pointer; padding: 0;';

      removeBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        removeFile(index);
      });

      col.appendChild(img);
      col.appendChild(removeBtn);
      previewContainer.appendChild(col);
    });
  }

  function addFiles(newFiles) {
    if (!newFiles || !newFiles.length) return;
    Array.from(newFiles).forEach(function (file) {
      if (file.type.startsWith('image/')) {
        // Kiểm tra xem file đã có trong danh sách chưa (tránh trùng lặp)
        var exists = Array.from(dt.files).some(function (f) {
          return f.name === file.name && f.size === file.size && f.lastModified === file.lastModified;
        });
        if (!exists) {
          dt.items.add(file);
        }
      }
    });
    updatePreview();
  }

  function removeFile(index) {
    var newDt = new DataTransfer();
    Array.from(dt.files).forEach(function (file, i) {
      if (i !== index) {
        newDt.items.add(file);
      }
    });
    dt = newDt;
    updatePreview();
  }

  // Nhấn vào zone để mở chọn file
  zone.addEventListener('click', function (e) {
    input.click();
  });

  input.addEventListener('change', function () {
    addFiles(input.files);
  });

  // Hỗ trợ Kéo & Thả (Drag & Drop)
  ['dragenter', 'dragover'].forEach(function (eventName) {
    zone.addEventListener(eventName, function (e) {
      e.preventDefault();
      e.stopPropagation();
      zone.style.borderColor = '#16834b';
      zone.style.background = '#f0fdf4';
    }, false);
  });

  ['dragleave', 'drop'].forEach(function (eventName) {
    zone.addEventListener(eventName, function (e) {
      e.preventDefault();
      e.stopPropagation();
      zone.style.borderColor = '';
      zone.style.background = '';
    }, false);
  });

  zone.addEventListener('drop', function (e) {
    var dtTransfer = e.dataTransfer;
    if (dtTransfer && dtTransfer.files) {
      addFiles(dtTransfer.files);
    }
  }, false);
})();
