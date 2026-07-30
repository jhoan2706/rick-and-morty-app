document.addEventListener('DOMContentLoaded', function() {
    
    var STORAGE_KEY = 'rm-favorites';
    
    function getFavs() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; } catch(e) { return []; }
    }
    
    function saveFavs(ids) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
    }
    
    var favs = getFavs();
    var buttons = document.querySelectorAll('.favorite-btn');
    
    buttons.forEach(function(btn) {
        var id = parseInt(btn.dataset.id);
        var icon = btn.querySelector('.heart-icon');
        
        if (favs.indexOf(id) > -1 && icon) {
            setActive(icon, true);
        }
        
        btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var id = parseInt(this.dataset.id);
            var f = getFavs();
            var idx = f.indexOf(id);
            
            if (idx > -1) { f.splice(idx, 1); }
            else { f.push(id); }
            
            saveFavs(f);
            var active = f.indexOf(id) > -1;
            
            document.querySelectorAll('.favorite-btn[data-id="' + id + '"]').forEach(function(b) {
                var i = b.querySelector('.heart-icon');
                if (i) setActive(i, active);
            });
            
            return false;
        };
    });
    
    function setActive(icon, active) {
        if (active) {
            // Verde sólido
            icon.classList.add('text-secondary-600');
            icon.classList.remove('text-gray-400');
            icon.setAttribute('fill', 'currentColor');
            icon.setAttribute('stroke', 'none');
        } else {
            // Borde gris, fondo blanco
            icon.classList.remove('text-secondary-600');
            icon.classList.add('text-gray-400');
            icon.setAttribute('fill', 'none');
            icon.setAttribute('stroke', 'currentColor');
        }
    }
    
    console.log('✅ Favorites ready. Buttons:', buttons.length);
});
