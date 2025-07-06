document.addEventListener("DOMContentLoaded", function () {
  const tagsDropdown = document.getElementById('tagsDropdown');
  const tagsList = document.getElementById('tagsList');

  function fetchAndRenderTags() {
    fetch('/tags/search', {
      headers: { 'Accept': 'application/json' }
    })
      .then(response => response.json())
      .then(data => {
        if (data.tags) {
          renderTagsDropdown(data.tags);
        }
      })
      .catch(error => console.error('Erro ao carregar tags:', error));
  }

  function renderTagsDropdown(tags) {
    tagsList.innerHTML = '';

    const allTagsItem = document.createElement('li');
    allTagsItem.innerHTML = `<a class="dropdown-item" href="/posts">Todas as Tags</a>`;
    tagsList.appendChild(allTagsItem);

    tags.forEach(tag => {
      const tagItem = document.createElement('li');
      tagItem.innerHTML = `<a class="dropdown-item" href="/posts/filter/${tag.id}">${tag.name}</a>`;
      tagsList.appendChild(tagItem);
    });
  }


  fetchAndRenderTags();

  setInterval(fetchAndRenderTags, 1000);
});
