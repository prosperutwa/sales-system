document.addEventListener('DOMContentLoaded', function () {
  const forms = document.querySelectorAll('.needs-validation');

  forms.forEach(form => {
    form.addEventListener('submit', function (e) {
      form.classList.add('was-validated');

      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
        return;
      }

      const submitBtn = form.querySelector('[type="submit"]');
      if (submitBtn) {
        submitBtn.dataset.originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;

        submitBtn.innerHTML = `
          <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                 </div>`;
      }
    }, false);
  });
});
