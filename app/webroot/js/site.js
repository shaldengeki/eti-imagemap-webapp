function initInterface(elt) {
  // initializes all interface elements and events within a given element.

  // highlight copy-url inputs upon focus or click.
  $(elt).find(".copy-field").click(function(e){
    e.preventDefault();
    this.select();
  });
  // copy eti image url to clipboard when clicking button.
  $(elt).find(".copy-button").each(function() {
    var clip = new ZeroClipboard($(this));
  });
}
$(document).ready(function () {
  initInterface(document);
});