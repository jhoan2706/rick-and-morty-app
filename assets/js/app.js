document.addEventListener("DOMContentLoaded", function () {
  // ==========================================
  // POPOVER - OPEN/CLOSE (MODIFIED)
  // ==========================================
  const toggleBtn = document.getElementById("filters-toggle");
  const popup = document.getElementById("filters-popup");
  const searchInput = document.getElementById("search-input");

  // ==========================================
  // FUNCTION TO UPDATE FILTERS BADGE (MODIFIED)
  // ==========================================
  function updateFiltersBadge() {
    const badge = document.getElementById("active-filters-badge");
    if (!badge) return;

    const params = new URLSearchParams(window.location.search);
    const status = params.get("status") || "";
    const species = params.get("species") || "";
    const name = params.get("name") || "";

    let activeFilters = 0;
    if (status) activeFilters++;
    if (species) activeFilters++;
    if (name) activeFilters++;

    const filterText =
      activeFilters + " " + (activeFilters === 1 ? "Filter" : "Filters");
    badge.textContent = filterText;
  }

  function openPopup() {
    if (popup) {
      popup.classList.remove("hidden");
      // Focus the search input when the popup opens
      if (searchInput) {
        searchInput.removeAttribute("readonly");
        setTimeout(function () {
          searchInput.focus();
        }, 100);
      }
    }
  }

  function closePopup() {
    if (popup) {
      popup.classList.add("hidden");
      // Put the search input back to readonly when the popup closes
      if (searchInput) {
        searchInput.setAttribute("readonly", true);
      }
    }
  }

  if (toggleBtn && popup) {
    toggleBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      if (popup.classList.contains("hidden")) {
        openPopup();
      } else {
        closePopup();
      }
    });

    // Open popup when clicking on the search input
    if (searchInput) {
      searchInput.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        if (popup.classList.contains("hidden")) {
          openPopup();
        }
      });
    }

    document.addEventListener("click", function (e) {
      if (
        !popup.classList.contains("hidden") &&
        !popup.contains(e.target) &&
        !toggleBtn.contains(e.target) &&
        !(searchInput && searchInput.contains(e.target))
      ) {
        closePopup();
      }
    });
  }

  // ==========================================
  // FILTERS - SELECT OPTIONS (fixed)
  // ==========================================
  let selectedFilters = {
    character: "All",
    species: "All",
  };

  function checkFilterChanges() {
    const applyBtn = document.getElementById("apply-filters");
    if (!applyBtn) return;

    const params = new URLSearchParams(window.location.search);
    const currentStatus = params.get("status") || "";
    const currentSpecies = params.get("species") || "";
    const currentName = params.get("name") || "";

    let currentCharacter = "All";
    if (currentStatus === "starred") {
      currentCharacter = "Starred";
    } else if (currentStatus === "others") {
      currentCharacter = "Others";
    }

    const currentSpeciesUI = currentSpecies || "All";

    // Get the current value of the search input
    const searchInputValue = searchInput ? searchInput.value.trim() : "";

    const hasChanges =
      selectedFilters.character !== currentCharacter ||
      selectedFilters.species !== currentSpeciesUI ||
      searchInputValue !== currentName;

    if (hasChanges) {
      applyBtn.disabled = false;
      applyBtn.classList.remove("opacity-50", "cursor-not-allowed");
      applyBtn.classList.add("hover:bg-[#5B38B0]");
    } else {
      applyBtn.disabled = true;
      applyBtn.classList.add("opacity-50", "cursor-not-allowed");
      applyBtn.classList.remove("hover:bg-[#5B38B0]");
    }
  }

  // Function to update the UI of filter buttons based on selectedFilters
  function updateFilterUI() {
    document
      .querySelectorAll('.filter-option[data-filter="character"]')
      .forEach(function (btn) {
        const value = btn.dataset.value;
        if (value === selectedFilters.character) {
          btn.className = btn.className
            .replace(/border-\[\#E8E8E8\]/g, "border-[#6B46C1]")
            .replace(/bg-white/g, "bg-[#EEE3FF]")
            .replace(/text-\[\#1E1E1E\]/g, "text-[#6B46C1]");
        } else {
          btn.className = btn.className
            .replace(/border-\[\#6B46C1\]/g, "border-[#E8E8E8]")
            .replace(/bg-\[\#EEE3FF\]/g, "bg-white")
            .replace(/text-\[\#6B46C1\]/g, "text-[#1E1E1E]");
        }
      });

    document
      .querySelectorAll('.filter-option[data-filter="species"]')
      .forEach(function (btn) {
        const value = btn.dataset.value;
        if (value === selectedFilters.species) {
          btn.className = btn.className
            .replace(/border-\[\#E8E8E8\]/g, "border-[#6B46C1]")
            .replace(/bg-white/g, "bg-[#EEE3FF]")
            .replace(/text-\[\#1E1E1E\]/g, "text-[#6B46C1]");
        } else {
          btn.className = btn.className
            .replace(/border-\[\#6B46C1\]/g, "border-[#E8E8E8]")
            .replace(/bg-\[\#EEE3FF\]/g, "bg-white")
            .replace(/text-\[\#6B46C1\]/g, "text-[#1E1E1E]");
        }
      });

    checkFilterChanges();
  }

  // Event listeners for buttons to update selectedFilters and UI
  document.querySelectorAll(".filter-option").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const filterType = this.dataset.filter;
      const value = this.dataset.value;

      if (filterType === "character") {
        selectedFilters.character = value;
      } else if (filterType === "species") {
        selectedFilters.species = value;
      }

      updateFilterUI();
    });
  });

  // Detect changes in the search input to enable/disable the apply button
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      checkFilterChanges();
    });
  }

  // Init filters from URL on page load
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
  })();

  // ==========================================
  // FAVORITES (OPTIMIZED)
  // ==========================================
  var STORAGE_KEY = "rm-favorites";
  var CACHE_KEY = "rm-favorites-cache";

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

  // Cache of starred characters to avoid re-fetching from API
  function getStarredCache() {
    try {
      return JSON.parse(localStorage.getItem(CACHE_KEY)) || {};
    } catch (e) {
      return {};
    }
  }

  function saveStarredCache(cache) {
    try {
      localStorage.setItem(CACHE_KEY, JSON.stringify(cache));
    } catch (e) {
      localStorage.removeItem(CACHE_KEY);
    }
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
        if (idx > -1) {
          f.splice(idx, 1);
          // Delete from cache to avoid stale data
          var cache = getStarredCache();
          delete cache[id];
          saveStarredCache(cache);
        } else {
          f.push(id);
        }
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

  // Render the starred section from cache first, then update from API in background
  function renderStarredFromCache() {
    var favs = getFavs();
    var starredSection = document.getElementById("starred-section");
    var starredList = document.getElementById("starred-list");
    var starredCount = document.getElementById("starred-count");

    if (!starredSection || !starredList || !starredCount) return;

    if (favs.length === 0) {
      starredSection.style.display = "none";
      starredList.innerHTML = "";
      return;
    }

    var cache = getStarredCache();
    var params = new URLSearchParams(window.location.search);
    var selectedId = params.get("id") || "";
    var html = "";
    var cachedCount = 0;
    var missingIds = [];

    favs.forEach(function (id) {
      if (cache[id]) {
        var c = cache[id];
        var isActive = selectedId && parseInt(id) === parseInt(selectedId);
        var bg = isActive ? "bg-primary-100" : "hover:bg-gray-50";
        var encodedName = c.name.replace(/"/g, "&quot;");
        var encodedSpecies = c.species.replace(/"/g, "&quot;");
        var encodedImage = c.image.replace(/"/g, "&quot;");

        html +=
          '<div class="flex w-full items-center rounded-none border-t border-gray-200 px-5 py-4 ' +
          bg +
          ' transition-colors group">' +
          '<a href="?id=' +
          id +
          '" class="flex items-center flex-1 min-w-0 character-link" data-id="' +
          id +
          '">' +
          '<img src="' +
          encodedImage +
          '" alt="' +
          encodedName +
          '" class="h-8 w-8 rounded-full object-cover flex-shrink-0" loading="lazy" />' +
          '<div class="ml-4 flex-1 text-left min-w-0">' +
          '<p class="font-semibold text-gray-900 text-sm truncate">' +
          encodedName +
          "</p>" +
          '<p class="text-gray-500 text-sm">' +
          encodedSpecies +
          "</p>" +
          "</div></a>" +
          '<button class="favorite-btn flex-shrink-0 ml-2" data-id="' +
          id +
          '" aria-label="Toggle favorite">' +
          '<svg class="w-5 h-5 heart-icon text-secondary-600" fill="currentColor" stroke="none" viewBox="0 0 24 24">' +
          '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>' +
          "</svg></button></div>";
        cachedCount++;
      } else {
        missingIds.push(id);
      }
    });

    // If there are missing IDs, fetch them from the API and update the cache
    if (missingIds.length > 0) {
      var url = "api/characters.php?ids=" + missingIds.join(",");
      fetch(url)
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.html) {
            // Update the cache with the newly fetched data
            var tempDiv = document.createElement("div");
            tempDiv.innerHTML = data.html;
            var items = tempDiv.querySelectorAll(".favorite-btn");
            items.forEach(function (btn) {
              var id = parseInt(btn.dataset.id);
              var img = btn.parentElement.querySelector("img");
              var nameEl = btn.parentElement.querySelector("p.font-semibold");
              var speciesEl = btn.parentElement.querySelectorAll("p.text-gray-500")[0];
              if (id && img && nameEl && speciesEl) {
                var cache = getStarredCache();
                cache[id] = {
                  name: nameEl.textContent.trim(),
                  species: speciesEl.textContent.trim(),
                  image: img.src,
                };
                saveStarredCache(cache);
              }
            });
            // Re-render the starred section from cache after updating it
            renderStarredFromCache();
          }
        });
      return;
    }

    if (cachedCount > 0) {
      starredList.innerHTML = html;
      starredCount.textContent = favs.length;
      starredSection.style.display = "block";
      initFavButtons();
      initMobileLinks();
    } else {
      // If there are no cached items, show a loading message while fetching from the API
      starredList.innerHTML = '<p class="px-6 py-4 text-sm text-gray-400">Loading starred characters...</p>';
      starredCount.textContent = favs.length;
      starredSection.style.display = "block";
    }
  }

  function rebuildStarredFromLocalStorage() {
    var favs = getFavs();
    var starredSection = document.getElementById("starred-section");
    var starredList = document.getElementById("starred-list");
    var starredCount = document.getElementById("starred-count");

    if (!starredSection || !starredList || !starredCount) return;

    if (favs.length === 0) {
      starredSection.style.display = "none";
      starredList.innerHTML = "";
      return;
    }

    renderStarredFromCache();
    // Make sure to initialize mobile links after a short delay to ensure the DOM is updated
    setTimeout(initMobileLinks, 100);
  }

  initFavButtons();
  if (document.getElementById("starred-section")) {
    rebuildStarredFromLocalStorage();
  }

  // ==========================================
  // INFINITE SCROLL
  // ==========================================
  var list = document.getElementById("characters-list");
  if (!list) return;

  var loading = false;
  var done = false;
  var page = 1;
  var retryCount = 0;
  var maxRetries = 3;

  function updateCharacterCounts(count) {
    var totalCountEl = document.getElementById("total-characters-count");
    var charactersCountEl = document.getElementById("characters-count");

    if (totalCountEl) {
      totalCountEl.textContent = count;
    }
    if (charactersCountEl) {
      charactersCountEl.textContent = count;
    }
  }

  function loadMore() {
    if (loading || done) return;

    // If we have retried, apply a delay before the next attempt
    if (retryCount > 0) {
      var delay = retryCount * 1000;
      setTimeout(function () {
        loading = false;
        loadMore();
      }, delay);
      return;
    }

    loading = true;
    page++;

    var params = new URLSearchParams(window.location.search);
    var name = params.get("name") || "";
    var status = params.get("status") || "";
    var species = params.get("species") || "";
    var selectedId = params.get("id") || "";

    var url = "api/characters.php?page=" + page;
    if (name) url += "&name=" + encodeURIComponent(name);
    if (status) url += "&status=" + encodeURIComponent(status);
    if (species) url += "&species=" + encodeURIComponent(species);
    if (selectedId) url += "&selected_id=" + selectedId;

    // If the filter is "starred" or "others", we need to pass the IDs of the favorites to the API
    if (status === "starred" || status === "others") {
      var favs = getFavs();
      if (favs.length > 0) {
        url += "&starred_ids=" + favs.join(",");
      }
    }

    fetch(url)
      .then(function (r) {
        if (!r.ok) {
          if (r.status === 429) {
            throw new Error("RATE_LIMIT");
          }
          throw new Error("HTTP " + r.status);
        }
        return r.json();
      })
      .then(function (data) {
        loading = false;
        retryCount = 0; // Reset counter on success

        if (data.html && data.count > 0) {
          var div = document.createElement("div");
          div.innerHTML = data.html;
          while (div.firstChild) list.appendChild(div.firstChild);
          initFavButtons();

          var totalCountEl = document.getElementById("total-characters-count");
          var currentTotal = totalCountEl
            ? parseInt(totalCountEl.textContent)
            : 0;
          var newTotal = currentTotal + data.count;
          updateCharacterCounts(newTotal);
          updateFiltersBadge();
          
          // Init mobile links for newly loaded items
          initMobileLinks();
        }

        if (!data.hasMore) {
          done = true;
        }
      })
      .catch(function (error) {
        loading = false;
        if (error.message === "RATE_LIMIT") {
          retryCount++;
          page--;
          if (retryCount <= maxRetries) {
            loadMore();
          } else {
            page++;
            retryCount = 0;
          }
        } else {
          page--;
          retryCount = 0;
        }
      });
  }

  function reloadCharacters() {
    var list = document.getElementById("characters-list");
    if (!list) return;

    page = 1;
    done = false;
    loading = false;
    retryCount = 0;
    list.innerHTML = "";

    var params = new URLSearchParams(window.location.search);
    var name = params.get("name") || "";
    var status = params.get("status") || "";
    var species = params.get("species") || "";
    var selectedId = params.get("id") || "";

    var url = "api/characters.php?page=1";
    if (name) url += "&name=" + encodeURIComponent(name);
    if (status) url += "&status=" + encodeURIComponent(status);
    if (species) url += "&species=" + encodeURIComponent(species);
    if (selectedId) url += "&selected_id=" + selectedId;

    if (status === "starred" || status === "others") {
      var favs = getFavs();
      if (favs.length > 0) {
        url += "&starred_ids=" + favs.join(",");
      }
    }

    fetch(url)
      .then(function (r) {
        if (!r.ok) throw new Error("HTTP " + r.status);
        return r.json();
      })
      .then(function (data) {
        if (data.html) {
          list.innerHTML = data.html;
          updateCharacterCounts(data.count || 0);
          updateFiltersBadge();
          if (!data.hasMore) done = true;
          initFavButtons();
          initMobileLinks();
        }
      })
      .catch(function (error) {
        console.error("Error reloading characters:", error);
      });
  }

  var scrollTimeout;
  list.addEventListener("scroll", function () {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(function () {
      if (list.scrollTop + list.clientHeight >= list.scrollHeight - 200) {
        loadMore();
      }
    }, 300);
  });

  setTimeout(function () {
    if (list.scrollHeight <= list.clientHeight + 100) loadMore();
  }, 1000);

  setTimeout(function () {
    updateFiltersBadge();
  }, 100);

  // ==========================================
  // APPLY FILTERS
  // ==========================================
  document
    .getElementById("apply-filters")
    ?.addEventListener("click", function () {
      this.disabled = true;
      this.classList.add("opacity-50", "cursor-not-allowed");
      this.classList.remove("hover:bg-[#5B38B0]");

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

      const params = new URLSearchParams();

      const nameInput = searchForm
        ? searchForm.querySelector('input[name="name"]')
        : null;
      if (nameInput && nameInput.value.trim()) {
        params.set("name", nameInput.value.trim());
      }

      if (statusValue) params.set("status", statusValue);
      if (speciesValue) params.set("species", speciesValue);

      const newUrl = params.toString()
        ? "?" + params.toString()
        : window.location.pathname;
      window.history.pushState({}, "", newUrl);

      reloadCharacters();

      if (popup) popup.classList.add("hidden");
    });

  // ==========================================
  // MOBILE REDIRECT
  // ==========================================
  function isMobile() {
    return window.innerWidth < 768;
  }

  function initMobileLinks() {
    document.querySelectorAll(".character-link").forEach(function (link) {
      if (link.dataset.mobileInit) return;
      link.dataset.mobileInit = "1";

      link.addEventListener("click", function (e) {
        if (isMobile()) {
          e.preventDefault();
          e.stopPropagation();
          var id = this.dataset.id;
          if (id) {
            window.location.href = "detail.php?id=" + id;
          }
          return false;
        }
      });
    });
  }

  initMobileLinks();

  // Only override loadMore if it exists, to ensure we don't break existing functionality
  var originalLoadMore = loadMore;
  loadMore = function () {
    originalLoadMore();
    // initMobileLinks is already called after new items are loaded, so we don't need to call it here again
  };
});