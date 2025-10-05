<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Tweet</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-light py-5">

  <div class="container">
    <div class="card shadow-sm p-4 mx-auto" style="max-width: 600px;">
      <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Текст твита -->
        <div class="mb-3">
          <label for="tweet" class="form-label">Что у тебя нового?</label>
          <textarea id="tweet" name="tweet" rows="3" maxlength="280"
            class="form-control" placeholder="Напиши свой твит..."></textarea>
          <div class="d-flex justify-content-end small text-muted mt-1" id="charCount">0/280</div>
        </div>

        <!-- Скрытый input для файла (будет заполнен через JS) -->
        <input type="file" name="image" id="formImage" class="d-none" accept="image/*">

        <!-- Кнопки -->
        <div class="d-flex justify-content-between align-items-center">
          <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#imageModal">
            <i class="fa-solid fa-camera"></i> Прикрепить фото
          </button>

          <button type="submit" class="btn btn-primary">Опубликовать</button>
        </div>

        <!-- Превью выбранного файла -->
        <div id="previewContainer" class="mt-3 d-none text-center">
          <img id="preview" src="" alt="Предпросмотр" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
        </div>
      </form>
    </div>
  </div>

  <!-- Модальное окно -->
  <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Прикрепить изображение</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
        </div>
        <div class="modal-body">
          <input type="file" id="imageInput" accept="image/*" class="form-control">
          <div class="mt-3 text-center">
            <img id="modalPreview" src="" alt="" class="img-fluid rounded d-none" style="max-height: 250px;">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
          <button type="button" class="btn btn-primary" id="confirmImage" data-bs-dismiss="modal">Готово</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Подсчёт символов
    const textarea = document.getElementById('tweet');
    const charCount = document.getElementById('charCount');
    textarea.addEventListener('input', () => {
      charCount.textContent = `${textarea.value.length}/280`;
    });

    // Работа с изображением
    const imageInput = document.getElementById('imageInput');
    const modalPreview = document.getElementById('modalPreview');
    const previewContainer = document.getElementById('previewContainer');
    const preview = document.getElementById('preview');
    const confirmBtn = document.getElementById('confirmImage');
    const formImage = document.getElementById('formImage');

    // Превью в модалке
    imageInput.addEventListener('change', () => {
      const file = imageInput.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          modalPreview.src = e.target.result;
          modalPreview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
      }
    });

    // Подтверждение выбора изображения
    confirmBtn.addEventListener('click', () => {
      const file = imageInput.files[0];
      if (file) {
        // Копируем файл в скрытый input формы
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        formImage.files = dataTransfer.files;

        // Показываем превью в форме
        preview.src = URL.createObjectURL(file);
        previewContainer.classList.remove('d-none');
      }
    });
  </script>

</body>
</html>
