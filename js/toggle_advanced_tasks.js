$(document).ready(function() {
 // hides the slickbox as soon as the DOM is ready
 // (a little sooner than page load)
  $('#advanced_task_items').hide();

 // toggles the slickbox on clicking the noted link
  $('a.tasks-filter').click(function() {
 $('#advanced_task_items').slideToggle("fast");

 return false;
  });
});