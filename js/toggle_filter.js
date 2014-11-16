$(document).ready(function() {
 // hides the slickbox as soon as the DOM is ready
 // (a little sooner than page load)
  $('.filter-section').hide();

 // toggles the slickbox on clicking the noted link
  $('a.toggle-filter').click(function() {
 $('.filter-section').toggle();

 return false;
  });
});