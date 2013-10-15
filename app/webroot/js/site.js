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

  // item-select buttons.
  $(elt).find(".btn-item-select-save").hide().click(function() {
    // accumulate images to apply action to.
    var items = $($(this).attr('data-target')).find('.btn-item-selected');
    var ids = $.map(items, function (elt) {
      var href = $(elt).find('a').attr('href');
      return parseInt(href.split('/').slice(-1)[0]);
    });

    var postParams = $($(this).attr('data-form')).find('input').serialize();
    postParams = postParams + "&ids=" + ids.join(',');

    $.post($(this).attr('data-url'), 
           postParams,
           function(data, textStatus, XHR) {
            switch (data) {
              case '1':
                // deselect all selected items.
                $(items).removeClass('btn-item-selected');
                break;
              case '-1':
              case '-2':
                // errors.
                $(this).addClass('btn-danger').removeClass('btn-primary');
                break;
            }
            console.log("Saved: " + data);
           }
           );
  });
  $(elt).find(".btn-item-select").each(function() {
    $(this).click(function() {
      // toggle-able "active" property.
      if ($(this).hasClass('btn-item-select-disabled')) {
        return;
      }

      $(this).toggleClass('btn-item-select-active');
      if ($(this).hasClass('btn-item-select-active')) {
        $(elt).find(".btn-item-select").each(function() {
          $(this).addClass('btn-item-select-disabled');
        });
        $(this).removeClass('btn-item-select-disabled');

        // inject data-url data into target.
        if ($(this).attr('data-inject-url') && $(this).attr('data-inject-target')) {
          var injectUrl = $(this).attr('data-inject-url') + "?url=" + encodeURIComponent($(this).attr('data-url'));
          var injectTarget = $(this).attr('data-inject-target');

          $.get(injectUrl, function (data) {
            $(injectTarget).html(data);
          });
        }

        // override click behaviour of data-target items.
        $($(this).attr('data-target')).find('li').each(function() {
          $(this).click(function(e) {
            e.preventDefault();
            $(this).toggleClass('btn-item-selected');
          });
        });

        // set save-button target.
        $(elt).find(".btn-item-select-save").attr('data-url', $(this).attr('data-url'))
                                            .attr('data-form', $(this).attr('data-inject-target'))
                                            .attr('data-target', $(this).attr('data-target'))
                                            .show();
      } else {
        $(elt).find(".btn-item-select").each(function() {
          $(this).removeClass('btn-item-select-disabled');
        });
        // unset click behaviour of data-target.
        $($(this).attr('data-target')).find('li').each(function() {
          $(this).unbind('click');
        });

        // clear inject target.
        if ($(this).attr('data-inject-target')) {
          $($(this).attr('data-inject-target')).html('');
        }
        // clear save-button target.
        $(elt).find(".btn-item-select-save").attr('data-url', '')
                                            .attr('data-form', '')
                                            .attr('data-target', '')
                                            .hide();
      }
    });
  });
}
$(document).ready(function () {
  initInterface(document);
});