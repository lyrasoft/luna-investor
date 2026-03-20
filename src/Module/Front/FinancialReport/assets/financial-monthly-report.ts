const select = document.querySelector<HTMLSelectElement>('[data-role="year-select"]');

select?.addEventListener('change', () => {
  const selected = select!.selectedOptions.item(0);

  if (selected && selected.dataset.dest) {
    location.href = selected.dataset.dest;
  }
});
