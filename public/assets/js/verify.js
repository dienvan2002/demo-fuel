document.addEventListener('DOMContentLoaded', function () {
  const inputs = document.querySelectorAll('#otp input');

  // Auto-focus và auto-move giữa các input
  inputs.forEach((input, index) => {
    input.addEventListener('input', function (e) {
      // Chỉ cho phép số
      this.value = this.value.replace(/[^0-9]/g, '');

      // Tự động chuyển sang input tiếp theo
      if (this.value.length === 1 && index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    });

    // Xử lý phím Backspace
    input.addEventListener('keydown', function (e) {
      if (e.key === 'Backspace' && this.value === '' && index > 0) {
        inputs[index - 1].focus();
      }
    });

    // Xử lý phím Paste
    input.addEventListener('paste', function (e) {
      e.preventDefault();
      const paste = (e.clipboardData || window.clipboardData).getData('text');
      const pasteNumbers = paste.replace(/[^0-9]/g, '');

      if (pasteNumbers.length === 6) {
        for (let i = 0; i < 6; i++) {
          if (inputs[i]) {
            inputs[i].value = pasteNumbers[i];
          }
        }
        inputs[5].focus();
      }
    });
  });

  // Xử lý form submit
  const form = document.querySelector('form');
  form.addEventListener('submit', function (e) {
    const code = Array.from(inputs).map(input => input.value).join('');

    if (code.length !== 6) {
      e.preventDefault();
      alert('Vui lòng nhập đầy đủ 6 số');

      // Highlight input trống
      inputs.forEach(input => {
        if (input.value === '') {
          input.classList.add('error');
          setTimeout(() => input.classList.remove('error'), 500);
        }
      });

      // Focus vào input trống đầu tiên
      const firstEmpty = Array.from(inputs).find(input => input.value === '');
      if (firstEmpty) {
        firstEmpty.focus();
      }
    }
  });

  // Auto-focus vào input đầu tiên
  inputs[0].focus();
});
