document.addEventListener("DOMContentLoaded", function () {
  console.log("🚀 App iniciada");

  // ==========================================
  // POPOVER - OPEN/CLOSE
  // ==========================================
  const toggleBtn = document.getElementById("filters-toggle");
  const popup = document.getElementById("filters-popup");

  if (toggleBtn && popup) {
    toggleBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      popup.classList.toggle("hidden");
      console.log(
        "🔄 Popup:",
        popup.classList.contains("hidden") ? "oculto" : "visible",
      );
    });

    document.addEventListener("click", function (e) {
      if (
        !popup.classList.contains("hidden") &&
        !popup.contains(e.target) &&
        !toggleBtn.contains(e.target)
      ) {
        popup.classList.add("hidden");
        console.log("📦 Popup cerrado");
      }
    });
  }

  // ==========================================
  // FILTERS - SELECT OPTIONS (CORREGIDO)
  // ==========================================
  let selectedFilters = {
    character: "All",
    species: "All",
  };

  // ==========================================
  // FILTER BUTTON - HABILITAR SOLO CON CAMBIOS
  // ==========================================
  function checkFilterChanges() {
    const applyBtn = document.getElementById('apply-filters');
    if (!applyBtn) return;
    
    const params = new URLSearchParams(window.location.search);
    const currentStatus = params.get('status') || '';
    const currentSpecies = params.get('species') || '';
    
    let currentCharacter = 'All';
    if (currentStatus === 'starred') {
      currentCharacter = 'Starred';
    } else if (currentStatus === 'others') {
      currentCharacter = 'Others';
    }
    
    const currentSpeciesUI = currentSpecies || 'All';
    
    const characterChanged = selectedFilters.character !== currentCharacter;
    const speciesChanged = selectedFilters.species !== currentSpeciesUI;
    const hasChanges = characterChanged || speciesChanged;
    
    if (hasChanges) {
      applyBtn.disabled = false;
      applyBtn.classList.remove('opacity-50', 'cursor-not-allowed');
      applyBtn.classList.add('hover:bg-[#5B38B0]');
      console.log('✅ Botón Filter habilitado (hay cambios)');
    } else {
      applyBtn.disabled = true;
      applyBtn.classList.add('opacity-50', 'cursor-not-allowed');
      applyBtn.classList.remove('hover:bg-[#5B38B0]');
      console.log('⛔ Botón Filter deshabilitado (sin cambios)');
    }
  }

  // Función para actualizar el estado visual de los filtros
  function updateFilterUI() {
    document.querySelectorAll('.filter-option[data-filter="character"]').forEach(function(btn) {
      const value = btn.dataset.value;
      if (value === selectedFilters.character) {
        btn.className = btn.className
          .replace(/border-\[\#E8E8E8\]/g, 'border-[#6B46C1]')
          .replace(/bg-white/g, 'bg-[#EEE3FF]')
          .replace(/text-\[\#1E1E1E\]/g, 'text-[#6B46C1]');
      } else {
        btn.className = btn.className
          .replace(/border-\[\#6B46C1\]/g, 'border-[#E8E8E8]')
          .replace(/bg-\[\#EEE3FF\]/g, 'bg-white')
          .replace(/text-\[\#6B46C1\]/g, 'text-[#1E1E1E]');
      }
    });

    document.querySelectorAll('.filter-option[data-filter="species"]').forEach(function(btn) {
      const value = btn.dataset.value;
      if (value === selectedFilters.species) {
        btn.className = btn.className
          .replace(/border-\[\#E8E8E8\]/g, 'border-[#6B46C1]')
          .replace(/bg-white/g, 'bg-[#EEE3FF]')
          .replace(/text-\[\#1E1E1E\]/g, 'text-[#6B46C1]');
      } else {
        btn.className = btn.className
          .replace(/border-\[\#6B46C1\]/g, 'border-[#E8E8E8]')
          .replace(/bg-\[\#EEE3FF\]/g, 'bg-white')
          .replace(/text-\[\#6B46C1\]/g, 'text-[#1E1E1E]');
      }
    });
    
    checkFilterChanges();
  }

  // Event listeners para los botones de filtro
  document.querySelectorAll(".filter-option").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const filterType = this.dataset.filter;
      const value = this.dataset.value;

      console.log("🎯 Click en filtro:", filterType, "=", value);

      if (filterType === "character") {
        selectedFilters.character = value;
      } else if (filterType === "species") {
        selectedFilters.species = value;
      }

      updateFilterUI();
      console.log("📋 Filtros actuales:", selectedFilters);
    });
  });

  // Inicializar filtros desde URL
  (function initFiltersFromURL() {
    const params = new URLSearchParams(window.location.search);
    const status = params.get("status") || "";
    const species = params.get("species") || "";

    if (status === "starred") {
      selectedFilters.character = "Starred";
    } else if (status === "others") {
      selectedFilters.character = "Others";
    } else {
      selectedFilters.character = "All";
    }

    if (species && ["Human", "Alien"].includes(species)) {
      selectedFilters.species = species;
    } else {
      selectedFilters.species = "All";
    }

    const statusInput = document.getElementById("filter-status-hidden");
    const speciesInput = document.getElementById("filter-species-hidden");
    if (statusInput) statusInput.value = status;
    if (speciesInput) speciesInput.value = species;

    setTimeout(updateFilterUI, 50);
    console.log("📋 Filtros inicializados:", selectedFilters);
  })();

  // ==========================================
  // APPLY FILTERS
  // ==========================================
  document.getElementById("apply-filters")?.addEventListener("click", function() {
    console.log("🔍 Aplicando filtros...", selectedFilters);

    // Deshabilitar el botón inmediatamente
    this.disabled = true;
    this.classList.add('opacity-50', 'cursor-not-allowed');
    this.classList.remove('hover:bg-[#5B38B0]');

    const statusInput = document.getElementById("filter-status-hidden");
    const speciesInput = document.getElementById("filter-species-hidden");
    const searchForm = document.getElementById("search-form");

    let statusValue = "";
    if (selectedFilters.character === "Starred") {
      statusValue = "starred";
    } else if (selectedFilters.character === "Others") {
      statusValue = "others";
    }

    let speciesValue = "";
    if (selectedFilters.species !== "All") {
      speciesValue = selectedFilters.species;
    }

    if (statusInput) statusInput.value = statusValue;
    if (speciesInput) speciesInput.value = speciesValue;

    if (searchForm) {
      const params = new URLSearchParams(window.location.search);
      if (statusValue) {
        params.set("status", statusValue);
      } else {
        params.delete("status");
      }
      if (speciesValue) {
        params.set("species", speciesValue);
      } else {
        params.delete("species");
      }

      const nameInput = searchForm.querySelector('input[name="name"]');
      if (nameInput && nameInput.value) {
        params.set("name", nameInput.value);
      } else {
        params.delete("name");
      }

      window.location.href = "?" + params.toString();
    }

    if (popup) popup.classList.add("hidden");
  });

  // ==========================================
  // FAVORITES
  // ==========================================
  var STORAGE_KEY = "rm-favorites";

  function getFavs() {
    try {
      return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    } catch (e) {
      return [];
    }
  }
  function saveFavs(ids) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
  }

  function initFavButtons() {
    document.querySelectorAll(".favorite-btn").forEach(function (btn) {
      if (btn.dataset.init) return;
      btn.dataset.init = "1";
      var id = parseInt(btn.dataset.id);
      var icon = btn.querySelector(".heart-icon");
      if (getFavs().indexOf(id) > -1 && icon) setHeart(icon, true);

      btn.onclick = function (e) {
        e.preventDefault();
        e.stopPropagation();
        var id = parseInt(this.dataset.id);
        var f = getFavs();
        var idx = f.indexOf(id);
        if (idx > -1) f.splice(idx, 1);
        else f.push(id);
        saveFavs(f);
        var active = f.indexOf(id) > -1;

        document
          .querySelectorAll('.favorite-btn[data-id="' + id + '"]')
          .forEach(function (b) {
            var i = b.querySelector(".heart-icon");
            if (i) setHeart(i, active);
          });

        rebuildStarredFromLocalStorage();
        return false;
      };
    });
  }

  function setHeart(icon, active) {
    if (active) {
      icon.classList.add("text-secondary-600");
      icon.classList.remove("text-gray-400");
      icon.setAttribute("fill", "currentColor");
      icon.setAttribute("stroke", "none");
    } else {
      icon.classList.remove("text-secondary-600");
      icon.classList.add("text-gray-400");
      icon.setAttribute("fill", "none");
      icon.setAttribute("stroke", "currentColor");
    }
  }

  function rebuildStarredFromLocalStorage() {
    var favs = getFavs();
    var starredSection = document.getElementById("starred-section");
    var starredList = document.getElementById("starred-list");
    var starredCount = document.getElementById("starred-count");

    if (favs.length === 0) {
      starredSection.style.display = "none";
      starredList.innerHTML = "";
      return;
    }

    fetch("api/characters.php?ids=" + favs.join(","))
      .then(function (r) {
        return r.json();
      })
      .then(function (data) {
        if (data.html) {
          starredList.innerHTML = data.html;
          starredCount.textContent = favs.length;
          starredSection.style.display = "block";
          initFavButtons();
        }
      });
  }

  initFavButtons();
  rebuildStarredFromLocalStorage();

  // ==========================================
  // INFINITE SCROLL
  // ==========================================
  var list = document.getElementById("characters-list");
  if (!list) return;

  var loading = false;
  var done = false;
  var page = 1;

  function loadMore() {
    if (loading || done) return;
    loading = true;
    page++;
    var params = new URLSearchParams(window.location.search);
    var name = params.get("name") || "";
    var status = params.get("status") || "";
    var species = params.get("species") || "";

    fetch(
      "api/characters.php?page=" + page + 
      "&name=" + encodeURIComponent(name) +
      "&status=" + encodeURIComponent(status) +
      "&species=" + encodeURIComponent(species)
    )
      .then(function (r) {
        return r.json();
      })
      .then(function (data) {
        loading = false;
        if (data.html && data.count > 0) {
          var div = document.createElement("div");
          div.innerHTML = data.html;
          while (div.firstChild) list.appendChild(div.firstChild);
          initFavButtons();
          var countEl = document.getElementById("characters-count");
          if (countEl)
            countEl.textContent = parseInt(countEl.textContent) + data.count;
        }
        if (!data.hasMore) done = true;
      })
      .catch(function () {
        loading = false;
        page--;
      });
  }

  list.addEventListener("scroll", function () {
    if (list.scrollTop + list.clientHeight >= list.scrollHeight - 200)
      loadMore();
  });

  setTimeout(function () {
    if (list.scrollHeight <= list.clientHeight + 100) loadMore();
  }, 500);

  console.log("✅ Ready");
});