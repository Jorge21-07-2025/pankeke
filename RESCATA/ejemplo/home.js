// Home page logic

let allPets = [];

// Load pets data from JSON
async function loadPets() {
  try {
    const response = await fetch('pets.json');
    const data = await response.json();
    allPets = data.pets;
    renderPets(allPets);
  } catch (error) {
    console.error('Error loading pets:', error);
    showError();
  }
}

// Render pet cards
function renderPets(pets) {
  const petsGrid = document.getElementById('petsGrid');
  
  if (!petsGrid) return;
  
  if (pets.length === 0) {
    petsGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: var(--text-medium);">No se encontraron mascotas</p>';
    return;
  }
  
  petsGrid.innerHTML = pets.map(pet => `
    <div class="pet-card" data-pet-id="${pet.id}" onclick="viewPetDetail(${pet.id})">
      <div class="pet-image-container" style="background: linear-gradient(135deg, ${pet.color}, ${pet.color}dd)">
        <img src="${pet.image}" alt="${pet.name}" class="pet-emoji" 
             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" 
             style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-md);" />
        <span class="pet-emoji" style="display: none; font-size: 48px;">${pet.emoji}</span>
      </div>
      <div class="pet-info">
        <h3 class="pet-name">${pet.name}</h3>
        <p class="pet-details">${pet.age} ${pet.ageUnit} · ${pet.city}</p>
        <span class="badge badge-success">${pet.status}</span>
      </div>
    </div>
  `).join('');
}

// Show error message
function showError() {
  const petsGrid = document.getElementById('petsGrid');
  if (petsGrid) {
    petsGrid.innerHTML = `
      <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
        <p style="font-size: 48px; margin-bottom: 16px;">😕</p>
        <p style="color: var(--text-medium);">Error al cargar las mascotas</p>
      </div>
    `;
  }
}

// Navigate to pet detail page
function viewPetDetail(petId) {
  window.location.href = `pet-detail.html?id=${petId}`;
}

// Search functionality
function filterPets(searchTerm) {
  if (!searchTerm) {
    renderPets(allPets);
    return;
  }
  
  const filtered = allPets.filter(pet => 
    pet.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    pet.breed.toLowerCase().includes(searchTerm.toLowerCase()) ||
    pet.city.toLowerCase().includes(searchTerm.toLowerCase()) ||
    pet.species.toLowerCase().includes(searchTerm.toLowerCase())
  );
  
  renderPets(filtered);
}

// Initialize page
document.addEventListener('DOMContentLoaded', () => {
  // Set user name
  const userName = localStorage.getItem('userName') || 'Usuario';
  const userNameElement = document.getElementById('userName');
  
  if (userNameElement) {
    userNameElement.textContent = userName;
  }
  
  // Set greeting based on time of day
  const greetingText = document.querySelector('.greeting-text');
  if (greetingText) {
    const hour = new Date().getHours();
    let greeting = 'Hola';
    
    if (hour < 12) {
      greeting = 'Hola, buenos días';
    } else if (hour < 18) {
      greeting = 'Hola, buenas tardes';
    } else {
      greeting = 'Hola, buenas noches';
    }
    
    greetingText.textContent = greeting + ' 👋';
  }
  
  // Load pets data
  loadPets();
  
  // Search input handler
  const searchInput = document.querySelector('.search-input');
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      const searchTerm = e.target.value.toLowerCase();
      filterPets(searchTerm);
    });
  }
  
  // Navigation items click handlers
  const navItems = document.querySelectorAll('.nav-item');
  navItems.forEach((item, index) => {
    item.addEventListener('click', () => {
      navItems.forEach(nav => nav.classList.remove('active'));
      item.classList.add('active');
      
      const labels = ['Inicio', 'Explorar', 'Reportar', 'Guardados', 'Perfil'];
    });
  });
});

