function scrollCarousel(id, direction) {
  const carousel = document.getElementById(id);
  const scrollAmount = 270; // roughly one card width
  carousel.scrollBy({
    left: direction * scrollAmount,
    behavior: 'smooth'
  });

  // Optional infinite scroll effect
  setTimeout(() => {
    if (carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth) {
      carousel.scrollTo({ left: 0, behavior: 'smooth' });
    } else if (carousel.scrollLeft <= 0 && direction < 0) {
      carousel.scrollTo({ left: carousel.scrollWidth, behavior: 'smooth' });
    }
  }, 600);
}
