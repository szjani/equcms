function initTranslate(urlString) {
  $.ajax({
    url: urlString,
    success: function(data) {
      $.jsperanto.init(function(t) {}, {
        interpolationPrefix: '%',
        interpolationSuffix: '%',
        dictionary: data
      });
    },
    error: function() {
      $.t = function(key, options) {
        return key;
      }
    },
    async: false
  });
}
