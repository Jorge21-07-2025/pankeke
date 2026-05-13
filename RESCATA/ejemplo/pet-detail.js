// Pet Detail Page Logic

let currentPet = null;

// Get pet ID from URL parameters
function getPetIdFromURL() {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get('id');
}

// Load pet data from JSON
async function loadPetData(petId) {
  try {
    const response = await fetch('pets.json');
    const data = await response.json();
    
    currentPet = data.pets.find(pet => pet.id === parseInt(petId));
    
    if (currentPet) {
      displayPetDetails(currentPet);
      hideLoading();
    } else {
      showError('Mascota no encontrada');
    }
  } catch (error) {
    console.error('Error loading pet data:', error);
    showError('Error al cargar los datos');
  }
}

// Display pet details on the page
function displayPetDetails(pet) {
  // Update page title
  document.title = `${pet.name} - Adopción de Mascotas`;
  
  // Set pet image
  const petImage = document.getElementById('petImage');
  petImage.src = pet.image;
  petImage.alt = pet.name;
  
  // Handle image load error - use emoji as fallback
  petImage.onerror = function() {
    const wrapper = this.parentElement;
    wrapper.innerHTML = `<div style="font-size: 120px; background: ${pet.color}; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; border-radius: var(--radius-lg);">${pet.emoji}</div>`;
  };
  
  // Update header background color based on pet
  const petHeader = document.querySelector('.pet-header');
  if (pet.color) {
    petHeader.style.background = `linear-gradient(135deg, ${pet.color}, ${pet.color}dd)`;
  }
  
  // Set basic info
  document.getElementById('petName').textContent = pet.name;
  document.getElementById('petBasicInfo').textContent = 
    `${pet.breed} · ${pet.age} ${pet.ageUnit} · ${pet.gender} · ${pet.city}`;
  document.getElementById('petStatus').textContent = pet.status;
  
  // Set info cards
  document.getElementById('petWeight').textContent = pet.weight;
  document.getElementById('petSize').textContent = pet.size;
  document.getElementById('petShelter').textContent = pet.shelter;
  
  // Set description
  document.getElementById('petDescription').textContent = pet.description;
  
  // Render traits
  renderTraits(pet.traits);
  
  // Check if pet is in favorites
  updateFavoriteButton();
}

// Render traits badges
function renderTraits(traits) {
  const container = document.getElementById('traitsContainer');
  container.innerHTML = '';
  
  traits.forEach(trait => {
    const badge = document.createElement('div');
    badge.className = `trait-badge ${trait.value ? 'active' : 'inactive'}`;
    badge.innerHTML = `
      <span class="trait-icon">${trait.icon}</span>
      <span>${trait.label}</span>
    `;
    container.appendChild(badge);
  });
}

// Show/hide loading state
function hideLoading() {
  document.getElementById('loadingState').style.display = 'none';
  document.getElementById('mainContent').style.display = 'block';
}

function showError(message) {
  const loadingState = document.getElementById('loadingState');
  loadingState.innerHTML = `
    <div style="text-align: center;">
      <p style="font-size: 48px; margin-bottom: 16px;">😕</p>
      <p style="font-size: 18px; color: var(--text-dark);">${message}</p>
      <button onclick="goBack()" class="btn-primary" style="margin-top: 20px;">Volver</button>
    </div>
  `;
}

// Navigation functions
function goBack() {
  if (window.history.length > 1) {
    window.history.back();
  } else {
    window.location.href = 'home.html';
  }
}

// Favorites management
function toggleFavorite() {
  if (!currentPet) return;
  
  let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
  const index = favorites.indexOf(currentPet.id);
  
  if (index > -1) {
    favorites.splice(index, 1);
  } else {
    favorites.push(currentPet.id);
  }
  
  localStorage.setItem('favorites', JSON.stringify(favorites));
  updateFavoriteButton();
}

function updateFavoriteButton() {
  if (!currentPet) return;
  
  const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
  const favoriteBtn = document.getElementById('favoriteBtn');
  const isFavorite = favorites.includes(currentPet.id);
  
  if (isFavorite) {
    favoriteBtn.classList.add('active');
    favoriteBtn.querySelector('.icon').textContent = '♥';
  } else {
    favoriteBtn.classList.remove('active');
    favoriteBtn.querySelector('.icon').textContent = '♡';
  }
}

// Adopt button handler
function handleAdopt() {
  if (!currentPet) return;
  
  // Show adoption confirmation
  const confirmed = confirm(
    `¿Estás seguro que quieres iniciar el proceso de adopción de ${currentPet.name}?\n\n` +
    `En una aplicación real, aquí se abriría un formulario de solicitud de adopción.`
  );
  
  if (confirmed) {
    // In a real app, this would open an adoption form
    alert(`¡Excelente! Pronto nos pondremos en contacto contigo para continuar con la adopción de ${currentPet.name}. 🐾`);
  }
}

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
  const petId = getPetIdFromURL();
  
  if (!petId) {
    showError('No se especificó una mascota');
    return;
  }
  
  loadPetData(petId);
  
  // Favorite button
  const favoriteBtn = document.getElementById('favoriteBtn');
  if (favoriteBtn) {
    favoriteBtn.addEventListener('click', toggleFavorite);
  }
  
  // Adopt button
  const adoptBtn = document.getElementById('adoptBtn');
  if (adoptBtn) {
    adoptBtn.addEventListener('click', handleAdopt);
  }
});
