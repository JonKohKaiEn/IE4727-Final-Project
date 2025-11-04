let scrollPosition = 0;

function scrollReviews(direction) {
  const carousel = document.getElementById("review-carousel");
  const cardWidth = carousel.querySelector(".review-card").offsetWidth + 30; // card + margin
  const totalWidth = carousel.scrollWidth;
  const visibleWidth = carousel.offsetWidth;

  scrollPosition += direction * cardWidth * 2; // scroll 2 reviews at a time

  if (scrollPosition > 0) scrollPosition = totalWidth - visibleWidth;
  else if (scrollPosition > totalWidth - visibleWidth) scrollPosition = 0;

  carousel.scrollTo({
    left: scrollPosition,
    behavior: "smooth",
  });
}