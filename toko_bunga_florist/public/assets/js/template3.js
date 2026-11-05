document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.querySelector('[data-sidebar]');
  const siteNav = document.querySelector('[data-site-nav]');
  const toast = document.querySelector('[data-toast]');

  document.querySelectorAll('[data-menu-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
      if (sidebar) sidebar.classList.toggle('open');
      if (siteNav) siteNav.classList.toggle('open');
    });
  });

  document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (!window.confirm(form.dataset.confirm || 'Lanjutkan tindakan ini?')) {
        event.preventDefault();
      }
    });
  });

  const searchInput = document.querySelector('[data-search-input]');
  const statusFilter = document.querySelector('[data-status-filter]');
  const pageControls = document.querySelectorAll('.pagination button');
  const pageSize = 10;
  let currentPage = 1;
  const filterRows = () => {
    const query = (searchInput?.value || '').trim().toLocaleLowerCase('id');
    const status = statusFilter?.value || '';
    const rows = Array.from(document.querySelectorAll('[data-search-row]'));
    const filteredRows = rows.filter((row) => {
      const matchesText = row.textContent.toLocaleLowerCase('id').includes(query);
      const matchesStatus = !status || row.dataset.status === status;
      return matchesText && matchesStatus;
    });
    const pageCount = Math.max(1, Math.ceil(filteredRows.length / pageSize));
    currentPage = Math.min(currentPage, pageCount);
    const firstIndex = (currentPage - 1) * pageSize;
    const visibleRows = new Set(filteredRows.slice(firstIndex, firstIndex + pageSize));
    rows.forEach((row) => {
      row.hidden = !visibleRows.has(row);
    });
    const summary = document.querySelector('.table-footer > span');
    if (summary) {
      summary.textContent = filteredRows.length
        ? `Menampilkan ${firstIndex + 1}–${Math.min(firstIndex + pageSize, filteredRows.length)} dari ${filteredRows.length} website`
        : 'Tidak ada website yang cocok';
    }
    const tableCount = document.querySelector('.table-count');
    if (tableCount) tableCount.textContent = `${filteredRows.length} website`;
    if (pageControls.length >= 3) {
      pageControls[0].disabled = currentPage === 1;
      pageControls[1].textContent = String(currentPage);
      pageControls[2].disabled = currentPage >= pageCount;
    }
  };
  searchInput?.addEventListener('input', filterRows);
  statusFilter?.addEventListener('change', filterRows);
  if (pageControls.length >= 3) {
    pageControls[0].addEventListener('click', () => {
      currentPage = Math.max(1, currentPage - 1);
      filterRows();
    });
    pageControls[2].addEventListener('click', () => {
      currentPage += 1;
      filterRows();
    });
  }
  if (document.querySelector('[data-search-row]')) filterRows();

  document.querySelectorAll('.template-option input[type="radio"]').forEach((input) => {
    input.addEventListener('change', () => {
      document.querySelectorAll('.template-option').forEach((option) => option.classList.remove('selected'));
      input.closest('.template-option')?.classList.add('selected');
    });
  });

  const quantityInput = document.querySelector('[data-order-quantity]');
  const unitPriceElement = document.querySelector('[data-unit-price]');
  const totalElement = document.querySelector('[data-order-total]');
  if (quantityInput && unitPriceElement && totalElement) {
    const unitPrice = Number(unitPriceElement.dataset.unitPrice);
    const formatPrice = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(value)}`;
    const updateTotal = () => {
      const quantity = Math.max(1, Math.min(20, Number(quantityInput.value) || 1));
      quantityInput.value = String(quantity);
      totalElement.textContent = formatPrice(unitPrice * quantity);
    };
    quantityInput.addEventListener('input', updateTotal);
    document.querySelectorAll('[data-quantity-step]').forEach((button) => {
      button.addEventListener('click', () => {
        quantityInput.value = String((Number(quantityInput.value) || 1) + Number(button.dataset.quantityStep));
        updateTotal();
      });
    });
    updateTotal();
  }

  const nameInput = document.querySelector('input[name="nama_umkm"]');
  const slugInput = document.querySelector('input[name="slug_toko"]');
  if (nameInput && slugInput && !slugInput.value) {
    nameInput.addEventListener('input', () => {
      slugInput.value = nameInput.value
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    });
  }

  document.querySelectorAll('[data-toast-message]').forEach((button) => {
    button.addEventListener('click', () => showToast(button.dataset.toastMessage));
  });

  function showToast(message) {
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('visible');
    window.setTimeout(() => toast.classList.remove('visible'), 3200);
  }
});
