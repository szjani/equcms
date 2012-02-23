/* =============================================================
 * Based on: bootstrap-typeaheadajax.js v2.0.1
 * http://twitter.github.com/bootstrap/javascript.html#typeaheadajax
 * =============================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================ */

!function( $ ){

  "use strict"

  var TypeaheadAjax = function ( element, options ) {
    this.$element = $(element)
    this.options = $.extend({}, $.fn.typeaheadajax.defaults, options)
    this.matcher = this.options.matcher || this.matcher
    this.sorter = this.options.sorter || this.sorter
    this.highlighter = this.options.highlighter || this.highlighter
    this.$menu = $(this.options.menu).appendTo('body')
    this.source = this.options.source
    this.delay = this.options.delay || 280
    this.lastTypeTime = new Date().getTime()
    this.shown = false
    this.listen()
  }

  TypeaheadAjax.prototype = {

    constructor: TypeaheadAjax

  , select: function () {
      var label = this.$menu.find('.active').attr('data-label')
      var val   = this.$menu.find('.active').attr('data-value')
      this.$element.val(label)
      this.options.hidden.val(val)
      return this.hide()
    }

  , show: function () {
      var pos = $.extend({}, this.$element.offset(), {
        height: this.$element[0].offsetHeight
      })

      this.$menu.css({
        top: pos.top + pos.height
      , left: pos.left
      })

      this.$menu.show()
      this.shown = true
      return this
    }

  , hide: function () {
      this.$menu.hide()
      this.shown = false
      return this
    }

  , lookup: function (event) {
      var that = this
        , items
        , q

      this.query = this.$element.val()

      if (!this.query) {
        return this.shown ? this.hide() : this
      }
      
      $.get(
        this.source + encodeURI(this.query),
        function (data) {
          return that.render(data).show()
        }
      )
    }

  , highlighter: function (item) {
      return item.replace(new RegExp('(' + this.query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    }

  , render: function (items) {
      var that = this

      items = $(items).map(function (i, item) {
        i = $(that.options.item)
          .attr('data-value', item.id)
          .attr('data-label', item.value)
        i.find('a').html(that.highlighter(item.value))
        return i[0]
      })

      items.first().addClass('active')
      this.$menu.html(items)
      return this
    }

  , next: function (event) {
      var active = this.$menu.find('.active').removeClass('active')
        , next = active.next()

      if (!next.length) {
        next = $(this.$menu.find('li')[0])
      }

      next.addClass('active')
    }

  , prev: function (event) {
      var active = this.$menu.find('.active').removeClass('active')
        , prev = active.prev()

      if (!prev.length) {
        prev = this.$menu.find('li').last()
      }

      prev.addClass('active')
    }

  , listen: function () {
      this.$element
        .on('blur',     $.proxy(this.blur, this))
        .on('keypress', $.proxy(this.keypress, this))
        .on('keyup',    $.proxy(this.keyup, this))

      if ($.browser.webkit || $.browser.msie) {
        this.$element.on('keydown', $.proxy(this.keypress, this))
      }

      this.$menu
        .on('click', $.proxy(this.click, this))
        .on('mouseenter', 'li', $.proxy(this.mouseenter, this))
    }

  , keyup: function (e) {
      e.stopPropagation()
      e.preventDefault()

      switch(e.keyCode) {
        case 40: // down arrow
        case 38: // up arrow
          break

        case 9: // tab
        case 13: // enter
          if (!this.shown) return
          this.select()
          break

        case 27: // escape
          this.hide()
          break

        default:
          var self = this;
          clearTimeout(this.lastTypeTime);
          this.lastTypeTime = setTimeout(function() {
            self.lookup();
          }, 210);
      }

  }

  , keypress: function (e) {
      e.stopPropagation()
      if (!this.shown) return

      switch(e.keyCode) {
        case 9: // tab
        case 13: // enter
        case 27: // escape
          e.preventDefault()
          break

        case 38: // up arrow
          e.preventDefault()
          this.prev()
          break

        case 40: // down arrow
          e.preventDefault()
          this.next()
          break
      }
    }

  , blur: function (e) {
      var that = this
      e.stopPropagation()
      e.preventDefault()
      setTimeout(function () {that.hide()}, 150)
    }

  , click: function (e) {
      e.stopPropagation()
      e.preventDefault()
      this.select()
    }

  , mouseenter: function (e) {
      this.$menu.find('.active').removeClass('active')
      $(e.currentTarget).addClass('active')
    }

  }


  /* TYPEAHEAD PLUGIN DEFINITION
   * =========================== */

  $.fn.typeaheadajax = function ( option ) {
    
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('typeaheadajax')
        , options = typeof option == 'object' && option
        , hidden = $('<input type="hidden" value="' + $this.val() + '" name="' + $this.attr('name') + '" id="' + $this.attr('id') + '_hidden" />')
      $this.before(hidden).removeAttr('name').attr('autocomplete', 'off')
      if ($this.val() > 0) {
        $.get(
          options.findone + $this.val(),
          function (data) {
            return $this.val(data.value)
          }
        )
      }
      options.hidden = hidden
      if (!data) $this.data('typeaheadajax', (data = new TypeaheadAjax(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.typeaheadajax.defaults = {
    source: []
  , items: 8
  , menu: '<ul class="typeaheadajax dropdown-menu"></ul>'
  , item: '<li><a href="#"></a></li>'
  }

  $.fn.typeaheadajax.Constructor = TypeaheadAjax


 /* TYPEAHEAD DATA-API
  * ================== */

  $(function () {
    $('[data-provide="typeaheadajax"]').each(function () {
      var $this = $(this)
      if ($this.data('typeaheadajax')) return
      $this.typeaheadajax($this.data())
    }).click(function() {
      $(this).select()
    })
  })

}( window.jQuery );