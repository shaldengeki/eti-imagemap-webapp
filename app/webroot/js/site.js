function split(val) {
  return val.split(/\s+/);
}
function extractLastTag(tags) {
  return split(tags).pop();
}

// TODO: refactor item select menu functions into an ItemSelectMenu class.
function saveItemSelectMenu(elt, parentElt) {
  // submits an item-select menu to the provided endpoint given by the data-url attribute of elt.
  // parentElt is the parent interface element that is initialized when elt is rendered.

  // accumulate items to apply action to.
  var items = $($(elt).attr('data-target')).find('.btn-item-selected');
  var ids = $.map(items, function (elt) {
    var href = $(elt).find('a').attr('href');
    return parseInt(href.split('/').slice(-1)[0]);
  });

  var postParams = $($(elt).attr('data-form')).find('input').serialize();
  postParams = postParams + "&ids=" + ids.join(',');

  $.post($(elt).attr('data-url'), 
         postParams,
         function(data, textStatus, XHR) {
          switch (data) {
            case '1':
              // deselect all selected items and close the power-tag menu.
              $(items).removeClass('btn-item-selected');
              $(parentElt).find('.btn-item-select-active').each(function() {
                toggleItemSelectMenu(this, parentElt);
              });
              break;
            case '-1':
            case '-2':
              // errors.
              $(elt).addClass('btn-danger').removeClass('btn-primary');
              break;
          }
          console.log("Saved: " + data);
         }
         );
}

function toggleItemSelectMenu(elt, parentElt) {
  // toggles an item-select menu given by the dom element elt
  // parentElt is the parent interface element that is initialized when elt is rendered.

  if ($(elt).hasClass('btn-item-select-disabled')) {
    return;
  }

  $(elt).toggleClass('btn-item-select-active');
  if ($(elt).hasClass('btn-item-select-active')) {
    $(parentElt).find(".btn-item-select").each(function() {
      $(this).addClass('btn-item-select-disabled');
    });
    $(elt).removeClass('btn-item-select-disabled');

    // inject data-url data into target.
    if ($(elt).attr('data-inject-url') && $(elt).attr('data-inject-target')) {
      var injectUrl = $(elt).attr('data-inject-url') + "?url=" + encodeURIComponent($(elt).attr('data-url'));
      var injectTarget = $(elt).attr('data-inject-target');

      $.get(injectUrl, function (data) {
        $(injectTarget).html(data);
        initInterface(injectTarget);
      });
    }

    // override click behaviour of data-target items.
    $($(elt).attr('data-target')).find('li').each(function() {
      $(this).click(function(e) {
        e.preventDefault();
        $(this).toggleClass('btn-item-selected');
      });
    });

    // set save-button target.
    $(parentElt).find(".btn-item-select-save").attr('data-url', $(elt).attr('data-url'))
                                        .attr('data-form', $(elt).attr('data-inject-target'))
                                        .attr('data-target', $(elt).attr('data-target'))
                                        .show();
  } else {
    $(parentElt).find(".btn-item-select").each(function() {
      $(this).removeClass('btn-item-select-disabled');
    });
    // unset click behaviour of data-target.
    $($(elt).attr('data-target')).find('li').each(function() {
      $(this).unbind('click');
    });

    // clear inject target.
    if ($(elt).attr('data-inject-target')) {
      $($(elt).attr('data-inject-target')).html('');
    }
    // clear save-button target.
    $(parentElt).find(".btn-item-select-save").attr('data-url', '')
                                        .attr('data-form', '')
                                        .attr('data-target', '')
                                        .hide();
  }
}

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
    saveItemSelectMenu(this, elt);
  });
  $(elt).find(".btn-item-select").each(function() {
    $(this).click(function() {
      toggleItemSelectMenu(this, elt);
    });
  });

  // autocompletion fields.
  $('.autocomplete').each(function() {
    var inputElt = this;
    var minLength = 2;
    $(this).autocomplete({
        source: function(request, response) {
          var searchTag = extractLastTag($(inputElt).val());
          if (searchTag.length < minLength) {
            return;
          }
          var url = $(inputElt).attr('data-url') + encodeURIComponent(searchTag);
          $.getJSON(url,
                    function(data) {
                      response($.map(data, function(tag) {
                        return {
                          label: tag.name,
                          value: tag.name
                        }
                      }));
                    }
          );
        },
        minLength: minLength,
        select: function(event, ui) {
          var terms = split(this.value);
          terms.pop();
          terms.push(ui.item.value);
          terms.push("");
          this.value = terms.join(" ");
          console.log(ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was: " + this.value);
          return false;
        },
        open: function() {
          $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function() {
          $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        }        
    });
  });
}
$(document).ready(function () {
  initInterface(document);
});