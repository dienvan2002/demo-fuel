document.addEventListener('DOMContentLoaded', function () {
      // Auto hide success messages after 4 seconds
      const successAlerts = document.querySelectorAll('.alert-success');

      successAlerts.forEach(alert => {
            setTimeout(() => {
                  alert.style.transition = 'all 0.5s ease';
                  alert.style.opacity = '0';
                  alert.style.height = '0';
                  alert.style.padding = '0';
                  alert.style.margin = '0';
                  alert.style.overflow = 'hidden';

                  setTimeout(() => {
                        alert.remove();
                  }, 500);
            }, 4000);
      });

      // Form validation
      const form = document.querySelector('form');
      const nameInput = document.querySelector('input[name="name"]');

      if (form && nameInput) {
            form.addEventListener('submit', function (e) {
                  const name = nameInput.value.trim();

                  if (!name) {
                        e.preventDefault();
                        alert('Vui lòng nhập tên danh mục');
                        nameInput.focus();
                        return false;
                  }

                  if (name.length < 2) {
                        e.preventDefault();
                        alert('Tên danh mục phải có ít nhất 2 ký tự');
                        nameInput.focus();
                        return false;
                  }
            });

            // Clear input on focus if there was an error
            nameInput.addEventListener('focus', function () {
                  this.style.borderColor = '#667eea';
            });
      }
});