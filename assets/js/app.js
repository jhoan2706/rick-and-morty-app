document.addEventListener('DOMContentLoaded', function() {
    
    // ========== FAVORITES ==========
    var STORAGE_KEY = 'rm-favorites';
    
    function getFavs() { try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; } catch(e) { return []; } }
    function saveFavs(ids) { localStorage.setItem(STORAGE_KEY, JSON.stringify(ids)); }
    
    function initFavButtons() {
        document.querySelectorAll('.favorite-btn').forEach(function(btn) {
            if (btn.dataset.init) return;
            btn.dataset.init = '1';
            var id = parseInt(btn.dataset.id);
            var icon = btn.querySelector('.heart-icon');
            if (getFavs().indexOf(id) > -1 && icon) setHeart(icon, true);
            btn.onclick = function(e) {
                e.preventDefault(); e.stopPropagation();
                var id = parseInt(this.dataset.id);
                var f = getFavs(); var idx = f.indexOf(id);
                if (idx > -1) f.splice(idx, 1); else f.push(id);
                saveFavs(f); var active = f.indexOf(id) > -1;
                document.querySelectorAll('.favorite-btn[data-id="' + id + '"]').forEach(function(b) {
                    var i = b.querySelector('.heart-icon');
                    if (i) setHeart(i, active);
                });
                return false;
            };
        });
    }
    
    function setHeart(icon, active) {
        if (active) {
            icon.classList.add('text-secondary-600'); icon.classList.remove('text-gray-400');
            icon.setAttribute('fill', 'currentColor'); icon.setAttribute('stroke', 'none');
        } else {
            icon.classList.remove('text-secondary-600'); icon.classList.add('text-gray-400');
            icon.setAttribute('fill', 'none'); icon.setAttribute('stroke', 'currentColor');
        }
    }
    
    initFavButtons();
    
    // ========== INFINITE SCROLL ==========
    var list = document.getElementById('characters-list');
    if (!list) return;
    
    var loading = false;
    var done = false;
    var page = 1;
    
    function loadMore() {
        if (loading || done) return;
        loading = true;
        page++;
        
        var name = new URLSearchParams(window.location.search).get('name') || '';
        
        fetch('api/characters.php?page=' + page + '&name=' + encodeURIComponent(name))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                loading = false;
                
                if (data.html && data.count > 0) {
                    var div = document.createElement('div');
                    div.innerHTML = data.html;
                    while (div.firstChild) list.appendChild(div.firstChild);
                    initFavButtons();
                    var countEl = document.getElementById('characters-count');
                    if (countEl) countEl.textContent = parseInt(countEl.textContent) + data.count;
                }
                
                if (!data.hasMore) {
                    done = true;
                    var end = document.createElement('p');
                    end.className = 'text-center py-4 text-gray-400 text-xs';
                    end.textContent = 'All ' + (document.getElementById('characters-count')?.textContent || '') + ' characters loaded';
                    list.appendChild(end);
                }
            })
            .catch(function() { loading = false; page--; });
    }
    
    // SCROLL: check every time user scrolls
    list.addEventListener('scroll', function() {
        if (list.scrollTop + list.clientHeight >= list.scrollHeight - 200) {
            loadMore();
        }
    });
    
    // CLICK: also try on click (fallback)
    list.addEventListener('click', function() {
        setTimeout(function() {
            if (list.scrollTop + list.clientHeight >= list.scrollHeight - 200) {
                loadMore();
            }
        }, 100);
    });
    
    // Initial load if content doesn't fill the container
    setTimeout(function() {
        if (list.scrollHeight <= list.clientHeight + 100) {
            loadMore();
        }
    }, 500);
    
    console.log('✅ Ready - Scroll to load more');
});
