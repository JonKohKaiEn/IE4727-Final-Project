// Utility function to create elements
const create = (tag, cls = "", text = "") => {
  const el = document.createElement(tag);
  if (cls) el.className = cls;
  if (text) el.textContent = text;
  return el;
};

// Fetch and render products
async function loadProducts(category, carouselId) {
  const res = await fetch(`api/get_products.php?category=${category}`);
  const products = await res.json();
  const track = document.querySelector(`#${carouselId} .carousel-track`);
  track.innerHTML = "";

  products.forEach(p => {
    const card = create("div", "carousel-card");
    const img = create("img");
    img.src = p.image_url || "assets/images/placeholder.jpg";
    const info = create("div", "info");
    const title = create("h3");
    title.innerHTML = `${p.name} <span>$${parseFloat(p.price).toFixed(2)}</span>`;
    const desc = create("p", "", p.description);
    info.append(title, desc);
    card.append(img, info);
    track.append(card);
  });

  setupCarousel(carouselId);
}

// Carousel logic
function setupCarousel(id) {
  const carousel = document.getElementById(id);
  const track = carousel.querySelector(".carousel-track");
  const left = carousel.querySelector(".carousel-btn.left");
  const right = carousel.querySelector(".carousel-btn.right");
  let index = 0;

  function move(dir) {
    const cards = carousel.querySelectorAll(".carousel-card");
    if (cards.length <= 3) return;
    index = (index + dir + cards.length) % cards.length;
    const offset = -index * (cards[0].offsetWidth + 20);
    track.style.transform = `translateX(${offset}px)`;
  }

  left.onclick = () => move(-1);
  right.onclick = () => move(1);
  setInterval(() => move(1), 4000);
}

// Load reviews
async function loadReviews() {
  const res = await fetch("api/get_reviews.php");
  const reviews = await res.json();
  const grid = document.getElementById("reviewsGrid");
  grid.innerHTML = "";

  reviews.forEach(r => {
    const card = create("div", "review-card");
    const img = create("img");
    img.src = r.avatar_url || "assets/images/avatar-placeholder.png";
    const name = create("h4", "", r.name);
    const stars = create("div", "stars", "★".repeat(r.rating));
    const comment = create("p", "", r.comment);
    card.append(img, stars, comment, name);
    grid.append(card);
  });
}

// Init
document.addEventListener("DOMContentLoaded", () => {
  loadProducts("offer", "offersCarousel");
  loadProducts("new", "newCarousel");
  loadReviews();
  document.getElementById("orderNow").onclick = () => {
    document.querySelector(".carousel-section").scrollIntoView({ behavior: "smooth" });
  };
  document.getElementById("newsletterForm").addEventListener("submit", e => {
    e.preventDefault();
    alert("Thanks for subscribing!");
    e.target.reset();
  });
});
